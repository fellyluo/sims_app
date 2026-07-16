<?php

namespace Tests\Feature;

use App\Models\Mission;
use App\Models\MissionActivityLog;
use App\Models\MissionAttempt;
use App\Models\MissionStep;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MissionNalarTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_loads_a_mission_bundle_for_authorized_user(): void
    {
        [$mission, $user] = $this->createMissionFixture();

        $response = $this->actingAs($user)->getJson(route('jagat-misi.api.show', $mission));

        $response->assertOk()
            ->assertJsonPath('data.mission.title', 'Jejak Pagi di Hutan')
            ->assertJsonCount(3, 'data.steps');
    }

    public function test_it_scores_and_persists_attempts_in_a_transaction(): void
    {
        [$mission, $user] = $this->createMissionFixture();

        $response = $this->actingAs($user)->postJson(route('jagat-misi.api.attempts', $mission), [
            'responses' => [
                'interactive_narrative' => [
                    'path' => ['Periksa sumber air', 'Bersihkan aliran', 'Selesaikan laporan'],
                    'final_node' => 'finish',
                ],
                'strategic_decision' => [
                    'choices' => ['Bersihkan drainase', 'Buka forum warga', 'Buka jalur tambahan'],
                    'stats' => [
                        'stability' => 88,
                        'trust' => 74,
                        'budget' => 44,
                    ],
                ],
                'puzzle_sequencing' => [
                    'order' => ['survey', 'materials', 'foundation', 'bridge', 'test'],
                ],
            ],
            'duration_seconds' => 123,
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.attempt.score', 100)
            ->assertJsonPath('data.attempt.result_meta.points_awarded', 100);

        $this->assertDatabaseCount('mission_attempts', 1);
        $this->assertDatabaseCount('mission_attempt_responses', 3);
        $this->assertDatabaseCount('mission_activity_logs', 1);

        $attempt = MissionAttempt::query()->first();
        $this->assertSame('completed', $attempt->status);
        $this->assertSame(100, $attempt->score);

        $log = MissionActivityLog::query()->first();
        $this->assertSame('mission_attempt.completed', $log->action);
    }

    public function test_catalog_page_renders_published_missions(): void
    {
        [$mission, $user] = $this->createMissionFixture();

        $response = $this->actingAs($user)->get(route('jagat-misi.index'));

        $response->assertOk()
            ->assertSee('Jagat Misi')
            ->assertSee($mission->title);
    }

    private function createMissionFixture(): array
    {
        Setting::create(['key' => 'nama_sekolah', 'value' => 'Test School']);

        $user = User::create([
            'username' => 'siswa_jm',
            'password' => Hash::make('password'),
            'access' => 'siswa',
        ]);
        Siswa::create([
            'id_login' => $user->uuid,
            'nama' => 'Siswa JM',
            'nis' => '9001',
            'jk' => 'L',
            'face_descriptor' => [0.1, 0.2],
        ]);

        $mission = Mission::factory()->nalar()->create([
            'is_published' => true,
        ]);

        MissionStep::factory()->narrative()->create(['mission_id' => $mission->uuid]);
        MissionStep::factory()->decision()->create(['mission_id' => $mission->uuid]);
        MissionStep::factory()->puzzle()->create(['mission_id' => $mission->uuid]);

        return [$mission, $user];
    }
}
