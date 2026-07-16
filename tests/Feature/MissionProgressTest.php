<?php

namespace Tests\Feature;

use App\Models\Mission;
use App\Models\MissionAttempt;
use App\Models\MissionBadge;
use App\Models\MissionStep;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MissionProgressTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_and_syncs_a_student_progress_profile(): void
    {
        [$mission, $user] = $this->createFixture();

        MissionAttempt::create([
            'mission_id' => $mission->uuid,
            'user_id' => $user->uuid,
            'status' => 'completed',
            'started_at' => now()->subMinutes(20),
            'completed_at' => now(),
            'score' => 94,
            'duration_seconds' => 1200,
            'result_meta' => [],
        ]);

        MissionBadge::factory()->firstMission()->create();

        $response = $this->actingAs($user)->getJson(route('jagat-misi.api.progress'));

        $response->assertOk()
            ->assertJsonPath('data.profile.summary.xp', 94)
            ->assertJsonPath('data.profile.summary.missions_completed', 1);
    }

    public function test_it_returns_school_leaderboard(): void
    {
        [$mission, $user] = $this->createFixture();

        MissionAttempt::create([
            'mission_id' => $mission->uuid,
            'user_id' => $user->uuid,
            'status' => 'completed',
            'completed_at' => now(),
            'score' => 80,
            'duration_seconds' => 600,
            'result_meta' => [],
        ]);

        $response = $this->actingAs($user)->getJson(route('jagat-misi.api.leaderboard'));

        $response->assertOk()
            ->assertJsonPath('data.leaderboard.count', 1)
            ->assertJsonPath('data.leaderboard.entries.0.xp', 80);
    }

    public function test_it_can_toggle_leaderboard_visibility(): void
    {
        [, $user] = $this->createFixture();

        $response = $this->actingAs($user)->patchJson(route('jagat-misi.api.leaderboard.visibility'), [
            'leaderboard_visible' => false,
        ]);

        $response->assertOk()
            ->assertJsonPath('data.leaderboard_visible', false);

        $this->assertFalse((bool) $user->fresh()->leaderboard_visible);
    }

    private function createFixture(): array
    {
        Setting::create(['key' => 'nama_sekolah', 'value' => 'Test School']);

        $user = User::create([
            'username' => 'siswa_prog',
            'password' => Hash::make('password'),
            'access' => 'siswa',
            'leaderboard_visible' => true,
        ]);
        Siswa::create([
            'id_login' => $user->uuid,
            'nama' => 'Siswa Prog',
            'nis' => '9002',
            'jk' => 'P',
            'face_descriptor' => [0.1, 0.2],
        ]);

        $mission = Mission::factory()->nalar()->create(['is_published' => true]);
        MissionStep::factory()->narrative()->create(['mission_id' => $mission->uuid]);

        return [$mission, $user];
    }
}
