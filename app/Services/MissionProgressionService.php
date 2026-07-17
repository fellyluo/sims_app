<?php

namespace App\Services;

use App\Models\MissionAttempt;
use App\Models\MissionBadge;
use App\Models\MissionCollectionItem;
use App\Models\MissionStudentBadge;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MissionProgressionService
{
    public function profile(User $user): array
    {
        return DB::transaction(function () use ($user) {
            $summary = $this->summaryFor($user);
            $this->awardRewards($user, $summary);

            return $this->snapshotFor($user, $this->summaryFor($user));
        });
    }

    public function leaderboard(int $limit = 10): array
    {
        $students = User::query()
            ->where('access', 'siswa')
            ->where('leaderboard_visible', true)
            ->get();

        $rows = $students->map(function (User $student) {
            $summary = $this->summaryFor($student);

            return [
                'user_id' => $student->uuid,
                'name' => $student->displayName(),
                'leaderboard_visible' => (bool) $student->leaderboard_visible,
                'level' => $summary['level'],
                'xp' => $summary['xp'],
                'streak_days' => $summary['streak_days'],
                'missions_completed' => $summary['missions_completed'],
                'badges_count' => $summary['badges_count'],
                'rank' => 0,
            ];
        })->all();

        usort($rows, function (array $left, array $right): int {
            foreach (['xp', 'streak_days', 'missions_completed'] as $field) {
                if ($left[$field] !== $right[$field]) {
                    return $right[$field] <=> $left[$field];
                }
            }

            return strcmp($left['name'], $right['name']);
        });

        $rows = array_slice($rows, 0, $limit);

        foreach ($rows as $index => $row) {
            $rows[$index]['rank'] = $index + 1;
        }

        return [
            'count' => count($rows),
            'entries' => $rows,
        ];
    }

    public function setLeaderboardVisibility(User $user, bool $visible): User
    {
        return DB::transaction(function () use ($user, $visible) {
            $user->forceFill([
                'leaderboard_visible' => $visible,
            ])->save();

            return $user->refresh();
        });
    }

    private function snapshotFor(User $user, array $summary): array
    {
        $badges = MissionStudentBadge::query()
            ->with('badge')
            ->where('user_id', $user->uuid)
            ->orderByDesc('earned_at')
            ->get()
            ->map(fn (MissionStudentBadge $studentBadge) => [
                'id' => $studentBadge->uuid,
                'badge_id' => $studentBadge->badge_id,
                'code' => $studentBadge->badge?->code,
                'name' => $studentBadge->badge?->name,
                'description' => $studentBadge->badge?->description,
                'icon' => $studentBadge->badge?->icon,
                'earned_at' => optional($studentBadge->earned_at)->toISOString(),
            ])
            ->values()
            ->all();

        $collectionItems = MissionCollectionItem::query()
            ->with('badge')
            ->where('user_id', $user->uuid)
            ->orderByDesc('unlocked_at')
            ->get()
            ->map(fn (MissionCollectionItem $item) => [
                'id' => $item->uuid,
                'badge_id' => $item->badge_id,
                'code' => $item->code,
                'name' => $item->name,
                'kind' => $item->kind,
                'description' => $item->description,
                'unlocked_at' => optional($item->unlocked_at)->toISOString(),
            ])
            ->values()
            ->all();

        $recentAttempts = MissionAttempt::query()
            ->with('mission:uuid,title,subject,grade_level')
            ->where('user_id', $user->uuid)
            ->where('status', 'completed')
            ->orderByDesc('completed_at')
            ->limit(5)
            ->get()
            ->map(fn (MissionAttempt $attempt) => [
                'id' => $attempt->uuid,
                'mission_id' => $attempt->mission_id,
                'mission_title' => $attempt->mission?->title,
                'subject' => $attempt->mission?->subject,
                'grade_level' => $attempt->mission?->grade_level,
                'score' => $attempt->score,
                'completed_at' => optional($attempt->completed_at)->toISOString(),
            ])
            ->values()
            ->all();

        return [
            'student' => [
                'id' => $user->uuid,
                'name' => $user->displayName(),
                'role' => $user->access,
                'leaderboard_visible' => (bool) $user->leaderboard_visible,
            ],
            'summary' => $summary + [
                'leaderboard_visible' => (bool) $user->leaderboard_visible,
            ],
            'badges' => $badges,
            'collection_items' => $collectionItems,
            'recent_attempts' => $recentAttempts,
        ];
    }

    private function summaryFor(User $user): array
    {
        $attempts = MissionAttempt::query()
            ->where('user_id', $user->uuid)
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->orderBy('completed_at')
            ->get();

        $xp = (int) $attempts->sum('score');
        $missionsCompleted = $attempts->count();
        $streakDays = $this->calculateStreak($attempts);
        $level = max(1, intdiv($xp, 100) + 1);
        $nextLevelXp = $level * 100;
        $averageScore = $missionsCompleted > 0 ? (int) round($xp / $missionsCompleted) : 0;
        $badgesCount = MissionStudentBadge::query()
            ->where('user_id', $user->uuid)
            ->count();
        $collectionCount = MissionCollectionItem::query()
            ->where('user_id', $user->uuid)
            ->count();

        return [
            'xp' => $xp,
            'level' => $level,
            'next_level_xp' => $nextLevelXp,
            'missions_completed' => $missionsCompleted,
            'streak_days' => $streakDays,
            'average_score' => $averageScore,
            'badges_count' => $badgesCount,
            'collection_items_count' => $collectionCount,
        ];
    }

    private function awardRewards(User $user, array $summary): void
    {
        $badges = MissionBadge::query()
            ->where('is_active', true)
            ->orderBy('threshold_xp')
            ->get();

        foreach ($badges as $badge) {
            if (! $this->badgeQualifies($badge, $summary)) {
                continue;
            }

            MissionStudentBadge::firstOrCreate(
                [
                    'user_id' => $user->uuid,
                    'badge_id' => $badge->uuid,
                ],
                [
                    'earned_at' => now(),
                    'meta' => [
                        'source' => 'progression_service',
                    ],
                ]
            );

            MissionCollectionItem::firstOrCreate(
                [
                    'user_id' => $user->uuid,
                    'code' => $badge->code,
                ],
                [
                    'badge_id' => $badge->uuid,
                    'name' => $badge->name,
                    'kind' => $badge->meta['kind'] ?? 'badge',
                    'description' => $badge->description,
                    'unlocked_at' => now(),
                    'meta' => [
                        'source' => 'badge_reward',
                        'badge_code' => $badge->code,
                    ],
                ]
            );
        }
    }

    private function badgeQualifies(MissionBadge $badge, array $summary): bool
    {
        if ($badge->threshold_xp !== null && $summary['xp'] < $badge->threshold_xp) {
            return false;
        }

        if ($badge->threshold_streak !== null && $summary['streak_days'] < $badge->threshold_streak) {
            return false;
        }

        if ($badge->threshold_missions !== null && $summary['missions_completed'] < $badge->threshold_missions) {
            return false;
        }

        return true;
    }

    private function calculateStreak(Collection $attempts): int
    {
        $dates = $attempts
            ->sortByDesc('completed_at')
            ->pluck('completed_at')
            ->filter()
            ->map(fn ($value) => CarbonImmutable::parse($value)->startOfDay())
            ->unique(fn (CarbonImmutable $value) => $value->toDateString())
            ->values();

        if ($dates->isEmpty()) {
            return 0;
        }

        $streak = 0;
        $expected = $dates->first();

        foreach ($dates as $date) {
            if (! $date->equalTo($expected)) {
                break;
            }

            $streak++;
            $expected = $expected->subDay();
        }

        return $streak;
    }
}
