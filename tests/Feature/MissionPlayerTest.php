<?php

namespace Tests\Feature;

use App\Models\Mission;
use App\Models\MissionAttempt;
use App\Models\MissionReflection;
use App\Models\MissionReflectionPrompt;
use App\Models\MissionStep;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MissionPlayerTest extends TestCase
{
    use RefreshDatabase;

    public function test_siswa_can_complete_recall_quiz_mission(): void
    {
        [$mission, $user] = $this->createPlayerFixture();

        $response = $this->actingAs($user)->postJson(route('jagat-misi.api.player.attempts', $mission), [
            'duration_seconds' => 90,
            'responses' => [
                'recall_quiz' => [
                    'answers' => ['Produsen', 'Katak', 'Mengurai sisa makhluk hidup'],
                ],
                'matching' => [
                    'matches' => [
                        'Produsen' => 'Padi',
                        'Konsumen' => 'Belalang',
                        'Pengurai' => 'Jamur',
                    ],
                ],
            ],
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.status', 'awaiting_reflection');
        $this->assertGreaterThanOrEqual(95, $response->json('data.score'));

        $this->assertDatabaseCount('mission_attempts', 1);
    }

    private function createPlayerFixture(): array
    {
        Setting::create(['key' => 'nama_sekolah', 'value' => 'Test School']);

        $user = User::create([
            'username' => 'siswa_player',
            'password' => Hash::make('password'),
            'access' => 'siswa',
        ]);
        Siswa::create([
            'id_login' => $user->uuid,
            'nama' => 'Siswa Player',
            'nis' => '9003',
            'jk' => 'L',
            'face_descriptor' => [0.1, 0.2],
        ]);

        $mission = Mission::factory()->recallQuiz()->create();
        MissionStep::factory()->recallQuiz()->create(['mission_id' => $mission->uuid]);
        MissionStep::factory()->matching()->create(['mission_id' => $mission->uuid]);
        MissionReflectionPrompt::create([
            'mission_id' => $mission->uuid,
            'position' => 1,
            'prompt_text' => 'Apa yang kamu pahami?',
            'is_required' => true,
        ]);

        return [$mission, $user];
    }
}
