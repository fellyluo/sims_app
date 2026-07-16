<?php

namespace Tests\Feature;

use App\Models\Mission;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MissionBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_can_create_mission_via_builder(): void
    {
        Setting::create(['key' => 'nama_sekolah', 'value' => 'Test School']);
        $guru = User::create([
            'username' => 'guru_builder',
            'password' => Hash::make('password'),
            'access' => 'guru',
        ]);

        $response = $this->actingAs($guru)->post(route('jagat-misi.builder.store'), [
            'title' => 'Misi Uji Builder',
            'subject' => 'IPA',
            'grade_level' => 'SD 5',
            'mechanic_type' => 'recall_quiz_bundle',
            'duration_minutes' => 25,
            'summary' => 'Misi uji dari builder.',
            'reflections' => ['Apa yang kamu pelajari?'],
            'requires_reflection' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('missions', ['title' => 'Misi Uji Builder', 'created_by' => $guru->uuid]);
        $mission = Mission::where('title', 'Misi Uji Builder')->first();
        $this->assertDatabaseHas('mission_reflection_prompts', ['mission_id' => $mission->uuid]);
    }

    public function test_guru_can_publish_mission(): void
    {
        Setting::create(['key' => 'nama_sekolah', 'value' => 'Test School']);
        $guru = User::create([
            'username' => 'guru_pub',
            'password' => Hash::make('password'),
            'access' => 'guru',
        ]);

        $mission = Mission::factory()->recallQuiz()->create([
            'created_by' => $guru->uuid,
            'is_published' => false,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($guru)->post(route('jagat-misi.builder.publish', $mission));
        $response->assertRedirect();
        $this->assertTrue($mission->fresh()->is_published);
    }
}
