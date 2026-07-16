<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\ClassroomMember;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mission;
use App\Models\MissionAssignment;
use App\Models\MissionAttempt;
use App\Models\MissionStep;
use App\Models\Ngajar;
use App\Models\Pelajaran;
use App\Models\Semester;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MissionClassroomTest extends TestCase
{
    use RefreshDatabase;

    protected User $guruUser;
    protected User $siswaUser;
    protected User $outsider;
    protected Classroom $classroom;
    protected Mission $mission;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::create(['key' => 'nama_sekolah', 'value' => 'Test School']);

        $this->guruUser = User::create([
            'username' => 'guru_jm',
            'password' => Hash::make('password'),
            'access' => 'guru',
        ]);
        $guru = Guru::create([
            'id_login' => $this->guruUser->uuid,
            'nama' => 'Guru JM',
            'nik' => '3001',
            'jk' => 'L',
            'face_descriptor' => [0.1],
        ]);

        $semester = Semester::create(['semester' => 1, 'tahun' => '2025/2026', 'aktif' => true]);
        $kelas = Kelas::create(['tingkat' => 5, 'kelas' => 'A']);
        $pelajaran = Pelajaran::create(['nama' => 'IPA', 'ringkasan' => 'IPA', 'kkm' => 75]);
        Ngajar::create([
            'id_guru' => $guru->uuid,
            'id_kelas' => $kelas->uuid,
            'id_pelajaran' => $pelajaran->uuid,
        ]);

        $this->classroom = Classroom::create([
            'id_semester' => $semester->id,
            'id_kelas' => $kelas->uuid,
            'id_pelajaran' => $pelajaran->uuid,
            'title' => 'IPA 5A',
            'status' => 'published',
            'class_code' => 'JM001',
            'created_by' => $this->guruUser->uuid,
            'cover_color' => '#111',
        ]);

        $this->siswaUser = User::create([
            'username' => 'siswa_jm_cls',
            'password' => Hash::make('password'),
            'access' => 'siswa',
        ]);
        Siswa::create([
            'id_login' => $this->siswaUser->uuid,
            'id_kelas' => $kelas->uuid,
            'nama' => 'Siswa JM',
            'nis' => '5001',
            'jk' => 'L',
            'face_descriptor' => [0.1],
        ]);
        ClassroomMember::create([
            'classroom_id' => $this->classroom->uuid,
            'user_id' => $this->siswaUser->uuid,
            'role_in_class' => 'siswa',
            'joined_at' => now(),
        ]);

        $this->outsider = User::create([
            'username' => 'siswa_luar_jm',
            'password' => Hash::make('password'),
            'access' => 'siswa',
        ]);
        $kelasB = Kelas::create(['tingkat' => 5, 'kelas' => 'B']);
        Siswa::create([
            'id_login' => $this->outsider->uuid,
            'id_kelas' => $kelasB->uuid,
            'nama' => 'Luar',
            'nis' => '5002',
            'jk' => 'P',
            'face_descriptor' => [0.1],
        ]);

        $this->mission = Mission::factory()->nalar()->create(['is_published' => true]);
        MissionStep::factory()->narrative()->create(['mission_id' => $this->mission->uuid]);
        MissionStep::factory()->decision()->create(['mission_id' => $this->mission->uuid]);
        MissionStep::factory()->puzzle()->create(['mission_id' => $this->mission->uuid]);
    }

    public function test_guru_can_assign_mission_to_classroom(): void
    {
        $response = $this->actingAs($this->guruUser)->post(route('classroom.jagat.assign', $this->classroom), [
            'mission_id' => $this->mission->uuid,
        ]);

        $response->assertRedirect(route('classroom.jagat.index', $this->classroom));
        $this->assertDatabaseHas('mission_assignments', [
            'mission_id' => $this->mission->uuid,
            'classroom_id' => $this->classroom->uuid,
            'status' => 'open',
        ]);
    }

    public function test_siswa_sees_assigned_mission_in_classroom_index(): void
    {
        MissionAssignment::create([
            'mission_id' => $this->mission->uuid,
            'classroom_id' => $this->classroom->uuid,
            'assigned_by' => $this->guruUser->uuid,
            'status' => 'open',
        ]);

        $response = $this->actingAs($this->siswaUser)->get(route('classroom.jagat.index', $this->classroom));

        $response->assertOk()
            ->assertSee($this->mission->title)
            ->assertSee('Jagat Misi');
    }

    public function test_siswa_can_play_assigned_mission_from_classroom(): void
    {
        MissionAssignment::create([
            'mission_id' => $this->mission->uuid,
            'classroom_id' => $this->classroom->uuid,
            'assigned_by' => $this->guruUser->uuid,
            'status' => 'open',
        ]);

        $response = $this->actingAs($this->siswaUser)->get(route('classroom.jagat.play', [$this->classroom, $this->mission]));

        $response->assertOk()
            ->assertSee($this->mission->title);
    }

    public function test_outsider_cannot_play_classroom_mission(): void
    {
        MissionAssignment::create([
            'mission_id' => $this->mission->uuid,
            'classroom_id' => $this->classroom->uuid,
            'assigned_by' => $this->guruUser->uuid,
            'status' => 'open',
        ]);

        $response = $this->actingAs($this->outsider)->get(route('classroom.jagat.play', [$this->classroom, $this->mission]));

        $response->assertForbidden();
    }

    public function test_attempt_persists_assignment_id_when_submitted_from_classroom(): void
    {
        $assignment = MissionAssignment::create([
            'mission_id' => $this->mission->uuid,
            'classroom_id' => $this->classroom->uuid,
            'assigned_by' => $this->guruUser->uuid,
            'status' => 'open',
        ]);

        $response = $this->actingAs($this->siswaUser)->postJson(route('jagat-misi.api.attempts', $this->mission), [
            'assignment_id' => $assignment->uuid,
            'responses' => [
                'interactive_narrative' => [
                    'path' => ['Periksa sumber air', 'Bersihkan aliran', 'Selesaikan laporan'],
                    'final_node' => 'finish',
                ],
                'strategic_decision' => [
                    'choices' => ['Bersihkan drainase', 'Buka forum warga', 'Buka jalur tambahan'],
                    'stats' => ['stability' => 88, 'trust' => 74, 'budget' => 44],
                ],
                'puzzle_sequencing' => [
                    'order' => ['survey', 'materials', 'foundation', 'bridge', 'test'],
                ],
            ],
            'duration_seconds' => 90,
        ]);

        $response->assertCreated();

        $attempt = MissionAttempt::query()->first();
        $this->assertSame($assignment->uuid, $attempt->assignment_id);
        $this->assertSame($this->siswaUser->uuid, $attempt->user_id);
    }

    public function test_guru_can_view_results_page(): void
    {
        $assignment = MissionAssignment::create([
            'mission_id' => $this->mission->uuid,
            'classroom_id' => $this->classroom->uuid,
            'assigned_by' => $this->guruUser->uuid,
            'status' => 'open',
        ]);

        MissionAttempt::create([
            'mission_id' => $this->mission->uuid,
            'assignment_id' => $assignment->uuid,
            'user_id' => $this->siswaUser->uuid,
            'status' => 'completed',
            'score' => 85,
            'completed_at' => now(),
        ]);

        $response = $this->actingAs($this->guruUser)->get(route('classroom.jagat.results', [$this->classroom, $this->mission]));

        $response->assertOk()
            ->assertSee('Monitor hasil misi')
            ->assertSee('85%');
    }

    public function test_classroom_show_has_jagat_misi_tab(): void
    {
        $response = $this->actingAs($this->guruUser)->get(route('classroom.show', $this->classroom));

        $response->assertOk()
            ->assertSee('Jagat Misi')
            ->assertSee(route('classroom.jagat.index', $this->classroom));
    }
}
