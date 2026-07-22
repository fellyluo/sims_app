<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Setting;
use App\Models\User;
use App\Models\Walikelas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Kartu ID Guru: generate PDF kartu identitas otomatis dari data guru —
 * jabatan mengikuti role akun, foto diunggah admin, QR berisi NIP/NIK.
 */
class KartuGuruTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Guru $guru;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::create(['key' => 'nama_sekolah', 'value' => 'SMP Test Kartu']);
        Setting::create(['key' => 'cara_absensi_guru', 'value' => 'wajah']);

        $this->admin = User::create([
            'username' => 'admin_kartu_guru',
            'password' => Hash::make('x'),
            'access' => 'superadmin',
        ]);

        $guruUser = User::create([
            'username' => 'guru_kartu',
            'password' => Hash::make('x'),
            'access' => 'guru',
        ]);
        $this->guru = Guru::create([
            'id_login' => $guruUser->uuid,
            'nama' => 'Guru Kartu Test',
            'nik' => '9001',
            'nip' => '198801012020121001',
            'jk' => 'L',
            // tanpa wajah terdaftar, middleware paksa-daftar-wajah me-redirect sebelum cek izin
            'face_descriptor' => [0.1],
        ]);
    }

    public function test_halaman_kelola_tampil_dengan_jabatan_dari_role(): void
    {
        $this->actingAs($this->admin)
            ->get(route('kartu-guru.kelola'))
            ->assertOk()
            ->assertSee('Kartu ID Guru', false)
            ->assertSee('Guru Kartu Test', false)
            ->assertSee('GURU', false); // teks background sesuai role
    }

    public function test_guru_biasa_tidak_boleh_akses(): void
    {
        $guruUser = User::where('username', 'guru_kartu')->first();
        $this->actingAs($guruUser)->get(route('kartu-guru.kelola'))->assertForbidden();
        $this->actingAs($guruUser)->get(route('kartu-guru.lihat', $this->guru->uuid))->assertForbidden();
    }

    public function test_unggah_dan_hapus_foto_guru(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)
            ->post(route('kartu-guru.foto', $this->guru->uuid), [
                'foto' => UploadedFile::fake()->image('pasfoto.jpg', 400, 500),
            ])
            ->assertRedirect();

        $this->guru->refresh();
        $this->assertNotNull($this->guru->foto);
        Storage::disk('public')->assertExists($this->guru->foto);

        $path = $this->guru->foto;
        $this->actingAs($this->admin)
            ->delete(route('kartu-guru.foto.hapus', $this->guru->uuid))
            ->assertRedirect();

        $this->guru->refresh();
        $this->assertNull($this->guru->foto);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_unggah_menolak_file_bukan_gambar(): void
    {
        $this->actingAs($this->admin)
            ->post(route('kartu-guru.foto', $this->guru->uuid), [
                'foto' => UploadedFile::fake()->create('virus.pdf', 100, 'application/pdf'),
            ])
            ->assertSessionHasErrors('foto');
    }

    public function test_pdf_kartu_tunggal_dan_massal_tergenerate(): void
    {
        $res = $this->actingAs($this->admin)->get(route('kartu-guru.lihat', $this->guru->uuid));
        $res->assertOk();
        $this->assertSame('application/pdf', $res->headers->get('content-type'));

        $res = $this->actingAs($this->admin)->get(route('kartu-guru.cetak'));
        $res->assertOk();
        $this->assertSame('application/pdf', $res->headers->get('content-type'));
    }

    public function test_jabatan_kepala_sekolah_dan_walikelas_ikut_role(): void
    {
        $kepalaUser = User::create([
            'username' => 'kepala_kartu',
            'password' => Hash::make('x'),
            'access' => 'kepala',
        ]);
        $kepala = Guru::create([
            'id_login' => $kepalaUser->uuid,
            'nama' => 'Kepala Kartu Test',
            'nik' => '9002',
            'jk' => 'P',
        ]);
        $kelas = Kelas::create(['tingkat' => 8, 'kelas' => 'B']);
        Walikelas::create(['id_guru' => $this->guru->uuid, 'id_kelas' => $kelas->uuid]);

        $res = $this->actingAs($this->admin)->get(route('kartu-guru.kelola'))->assertOk();
        $res->assertSee('Kepala Sekolah', false)
            ->assertSee('KEPSEK', false)
            ->assertSee('Wali Kelas 8B', false);
    }
}
