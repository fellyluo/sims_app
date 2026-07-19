<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\RolePermission;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Walikelas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Regresi: user yang PUNYA DUA peran sekaligus (kesiswaan + wali kelas) sebelumnya melihat
 * SEMUA siswa se-sekolah di halaman "Poin Siswa Kelas"/"P3 Siswa Kelas" (menu Wali Kelas),
 * karena PoinController/P3Controller mengutamakan izin manage_disiplin (kesiswaan) di atas
 * status wali kelasnya sendiri. Wali kelas dgn peran lain harus tetap melihat kelasnya SAJA,
 * sama seperti wali kelas lain — akses "semua siswa" tetap ada, tapi lewat menu Poin & Aturan
 * (bukan menu Wali Kelas) untuk user yang BUKAN wali kelas.
 */
class PoinP3WalikelasScopeTest extends TestCase
{
    use RefreshDatabase;

    private function dualRoleWalikelas(Kelas $kelas): User
    {
        $user = User::create([
            'username' => 'kesiswaan_wali_' . $kelas->uuid,
            'password' => Hash::make('password'),
            'access'   => 'kesiswaan',
        ]);
        $guru = Guru::create([
            'id_login'        => $user->getKey(),
            'nama'            => 'Kesiswaan Sekaligus Wali',
            'nik'             => 'KSW' . $kelas->uuid,
            'face_descriptor' => [array_map(fn ($i) => $i % 2 === 0 ? 1.0 : -1.0, range(0, 63))],
        ]);
        Walikelas::create(['id_kelas' => $kelas->uuid, 'id_guru' => $guru->uuid]);
        RolePermission::firstOrCreate(['role' => 'kesiswaan', 'permission' => 'manage_disiplin']);

        return $user;
    }

    public function test_poin_siswa_index_wali_kelas_kesiswaan_hanya_lihat_kelasnya(): void
    {
        $kelasSaya = Kelas::create(['tingkat' => 7, 'kelas' => 'G']);
        $kelasLain = Kelas::create(['tingkat' => 7, 'kelas' => 'H']);
        $user = $this->dualRoleWalikelas($kelasSaya);

        $siswaSaya = Siswa::create(['nama' => 'Siswa Kelas Saya Poin', 'nis' => 'POIN001', 'jk' => 'L', 'id_kelas' => $kelasSaya->uuid]);
        $siswaLain = Siswa::create(['nama' => 'Siswa Kelas Lain Poin', 'nis' => 'POIN002', 'jk' => 'P', 'id_kelas' => $kelasLain->uuid]);

        $response = $this->actingAs($user)->get(route('poin.siswa.index'))->assertOk();
        $response->assertSee('Siswa Kelas Saya Poin');
        $response->assertDontSee('Siswa Kelas Lain Poin');

        $this->actingAs($user)->get(route('poin.siswa.show', $siswaSaya))->assertOk();
        $this->actingAs($user)->get(route('poin.siswa.show', $siswaLain))->assertForbidden();
    }

    public function test_p3_siswa_index_wali_kelas_kesiswaan_hanya_lihat_kelasnya(): void
    {
        $kelasSaya = Kelas::create(['tingkat' => 8, 'kelas' => 'G']);
        $kelasLain = Kelas::create(['tingkat' => 8, 'kelas' => 'H']);
        $user = $this->dualRoleWalikelas($kelasSaya);

        $siswaSaya = Siswa::create(['nama' => 'Siswa Kelas Saya P3', 'nis' => 'P3001', 'jk' => 'L', 'id_kelas' => $kelasSaya->uuid]);
        $siswaLain = Siswa::create(['nama' => 'Siswa Kelas Lain P3', 'nis' => 'P3002', 'jk' => 'P', 'id_kelas' => $kelasLain->uuid]);

        $response = $this->actingAs($user)->get(route('p3.siswa.index'))->assertOk();
        $response->assertSee('Siswa Kelas Saya P3');
        $response->assertDontSee('Siswa Kelas Lain P3');

        $this->actingAs($user)->get(route('p3.siswa.show', $siswaSaya))->assertOk();
        $this->actingAs($user)->get(route('p3.siswa.show', $siswaLain))->assertForbidden();
    }

    public function test_kesiswaan_murni_tanpa_wali_kelas_tetap_lihat_semua_siswa(): void
    {
        $kelasA = Kelas::create(['tingkat' => 9, 'kelas' => 'A']);
        $kelasB = Kelas::create(['tingkat' => 9, 'kelas' => 'B']);
        $siswaA = Siswa::create(['nama' => 'Siswa A Murni', 'nis' => 'MURNI001', 'jk' => 'L', 'id_kelas' => $kelasA->uuid]);
        $siswaB = Siswa::create(['nama' => 'Siswa B Murni', 'nis' => 'MURNI002', 'jk' => 'P', 'id_kelas' => $kelasB->uuid]);

        $user = User::create([
            'username' => 'kesiswaan_murni',
            'password' => Hash::make('password'),
            'access'   => 'kesiswaan',
        ]);
        RolePermission::firstOrCreate(['role' => 'kesiswaan', 'permission' => 'manage_disiplin']);

        $response = $this->actingAs($user)->get(route('poin.siswa.index'))->assertOk();
        $response->assertSee('Siswa A Murni');
        $response->assertSee('Siswa B Murni');

        $this->actingAs($user)->get(route('poin.siswa.show', $siswaA))->assertOk();
        $this->actingAs($user)->get(route('poin.siswa.show', $siswaB))->assertOk();
    }
}
