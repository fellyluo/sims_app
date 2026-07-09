<?php

namespace Tests\Feature;

use App\Models\RolePermission;
use App\Models\User;
use App\Models\UserFeedback;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FeedbackTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $access, string $username): User
    {
        return User::create([
            'username' => $username,
            'password' => Hash::make('password'),
            'access' => $access,
        ]);
    }

    public function test_user_bisa_kirim_dan_melihat_feedback_sendiri(): void
    {
        $user = $this->makeUser('siswa', 'feedback_siswa');

        $response = $this->actingAs($user)->post('/masukan', [
            'category' => 'bug',
            'rating' => 2,
            'subject' => 'Jadwal tidak tampil',
            'message' => 'Saat membuka halaman jadwal, daftar pelajaran hari ini tidak muncul.',
            'context_url' => '/jadwal/guru',
        ]);

        $feedback = UserFeedback::firstOrFail();

        $response->assertRedirect(route('feedback.show', $feedback));
        $this->assertDatabaseHas('user_feedback', [
            'user_uuid' => $user->uuid,
            'category' => 'bug',
            'status' => 'baru',
            'subject' => 'Jadwal tidak tampil',
        ]);

        $this->actingAs($user)->get('/masukan/'.$feedback->uuid)
            ->assertOk()
            ->assertSee('Jadwal tidak tampil');
    }

    public function test_user_tidak_bisa_melihat_feedback_user_lain(): void
    {
        $owner = $this->makeUser('guru', 'feedback_owner');
        $other = $this->makeUser('siswa', 'feedback_other');

        $feedback = UserFeedback::create([
            'user_uuid' => $owner->uuid,
            'category' => 'ide',
            'status' => 'baru',
            'subject' => 'Tambah export',
            'message' => 'Butuh export data nilai per kelas.',
        ]);

        $this->actingAs($other)->get('/masukan/'.$feedback->uuid)->assertNotFound();
    }

    public function test_admin_bisa_merespon_feedback(): void
    {
        $admin = $this->makeUser('superadmin', 'feedback_admin');
        $user = $this->makeUser('orangtua', 'feedback_ortu');

        $feedback = UserFeedback::create([
            'user_uuid' => $user->uuid,
            'category' => 'data',
            'status' => 'baru',
            'subject' => 'Tagihan belum cocok',
            'message' => 'Nominal tagihan SPP anak saya belum sesuai.',
        ]);

        $this->actingAs($admin)->post('/masukan/'.$feedback->uuid.'/respon', [
            'status' => 'diproses',
            'admin_response' => 'Kami cek ke bendahara dan akan update setelah validasi.',
        ])->assertRedirect();

        $this->assertDatabaseHas('user_feedback', [
            'uuid' => $feedback->uuid,
            'status' => 'diproses',
            'responded_by' => $admin->uuid,
        ]);
    }

    public function test_role_dengan_permission_manage_feedback_bisa_merespon(): void
    {
        RolePermission::create(['role' => 'kesiswaan', 'permission' => 'manage_feedback']);

        $staff = $this->makeUser('kesiswaan', 'feedback_staff');
        $user = $this->makeUser('siswa', 'feedback_siswa_perm');

        $feedback = UserFeedback::create([
            'user_uuid' => $user->uuid,
            'category' => 'tampilan',
            'status' => 'baru',
            'subject' => 'Tombol terlalu kecil',
            'message' => 'Tombol pada halaman absensi terlalu kecil di HP.',
        ]);

        $this->actingAs($staff)->post('/masukan/'.$feedback->uuid.'/respon', [
            'status' => 'selesai',
            'admin_response' => 'Ukuran tombol sudah masuk backlog perbaikan UI.',
        ])->assertRedirect();

        $this->assertSame('selesai', $feedback->fresh()->status);
    }

    public function test_user_tanpa_permission_tidak_bisa_merespon(): void
    {
        $staff = $this->makeUser('guru', 'feedback_staff_forbidden');
        $user = $this->makeUser('siswa', 'feedback_user_forbidden');

        $feedback = UserFeedback::create([
            'user_uuid' => $user->uuid,
            'category' => 'lainnya',
            'status' => 'baru',
            'subject' => 'Butuh bantuan',
            'message' => 'Saya butuh bantuan menggunakan fitur pengumuman.',
        ]);

        $this->actingAs($staff)->post('/masukan/'.$feedback->uuid.'/respon', [
            'status' => 'dibaca',
        ])->assertForbidden();
    }

    public function test_validasi_feedback_wajib_detail_cukup(): void
    {
        $user = $this->makeUser('siswa', 'feedback_validasi');

        $this->actingAs($user)->post('/masukan', [
            'category' => 'bug',
            'subject' => '',
            'message' => 'pendek',
        ])->assertSessionHasErrors(['subject', 'message']);
    }
}
