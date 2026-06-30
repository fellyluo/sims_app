<?php

namespace Tests\Feature;

use App\Models\User;
use App\Sarpras\Models\BookingRuangan;
use App\Sarpras\Models\Denah;
use App\Sarpras\Models\DenahRuangan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Alur Ruangan & Booking: pengajuan, deteksi bentrok, persetujuan, penolakan.
 */
class SarprasBookingTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create(['username' => 'sap_booking', 'password' => Hash::make('x'), 'access' => 'superadmin']);
    }

    private function room(): DenahRuangan
    {
        $denah = Denah::create(['nama' => 'Lantai 1', 'gambar_path' => 'x.png']);

        return DenahRuangan::create([
            'denah_id' => $denah->id, 'kode' => '7A', 'nama' => 'Kelas 7A',
            'pos_x' => 10, 'pos_y' => 10, 'status' => 'tersedia', 'kapasitas' => 32,
            'fasilitas' => ['Proyektor', 'AC'],
        ]);
    }

    public function test_pengajuan_booking_berstatus_diajukan(): void
    {
        $room = $this->room();

        $this->actingAs($this->admin())->post('/sarpras/booking', [
            'ruangan_id' => $room->id,
            'keperluan'  => 'Rapat Komite',
            'tanggal'    => '2026-07-01',
            'jam_mulai'  => '08:00',
            'jam_selesai' => '10:00',
        ])->assertRedirect();

        $this->assertDatabaseHas('sarpras_booking_ruangan', [
            'ruangan_id' => $room->id, 'keperluan' => 'Rapat Komite', 'status' => 'diajukan',
        ]);
    }

    public function test_booking_bentrok_ditolak(): void
    {
        $room = $this->room();
        $admin = $this->admin();

        // Booking yang sudah ada (disetujui) 09:00–11:00.
        BookingRuangan::create([
            'ruangan_id' => $room->id, 'pemohon_id' => $admin->uuid,
            'keperluan' => 'Awal', 'mulai' => Carbon::parse('2026-07-01 09:00'),
            'selesai' => Carbon::parse('2026-07-01 11:00'), 'status' => 'disetujui',
        ]);

        // Ajukan 08:00–10:00 → bentrok.
        $this->actingAs($admin)->post('/sarpras/booking', [
            'ruangan_id' => $room->id, 'keperluan' => 'Bentrok',
            'tanggal' => '2026-07-01', 'jam_mulai' => '08:00', 'jam_selesai' => '10:00',
        ])->assertSessionHas('gagal');

        // Tidak ada booking "Bentrok" yang dibuat.
        $this->assertDatabaseMissing('sarpras_booking_ruangan', ['keperluan' => 'Bentrok']);
    }

    public function test_setujui_dan_tolak_booking(): void
    {
        $room = $this->room();
        $admin = $this->admin();

        $b = BookingRuangan::create([
            'ruangan_id' => $room->id, 'pemohon_id' => $admin->uuid, 'keperluan' => 'Acara',
            'mulai' => Carbon::parse('2026-07-02 08:00'), 'selesai' => Carbon::parse('2026-07-02 10:00'),
            'status' => 'diajukan',
        ]);

        $this->actingAs($admin)->post('/sarpras/booking/' . $b->id . '/setujui')->assertRedirect();
        $this->assertSame('disetujui', $b->fresh()->status);

        $b2 = BookingRuangan::create([
            'ruangan_id' => $room->id, 'pemohon_id' => $admin->uuid, 'keperluan' => 'Acara 2',
            'mulai' => Carbon::parse('2026-07-03 08:00'), 'selesai' => Carbon::parse('2026-07-03 10:00'),
            'status' => 'diajukan',
        ]);
        $this->actingAs($admin)->post('/sarpras/booking/' . $b2->id . '/tolak')->assertRedirect();
        $this->assertSame('ditolak', $b2->fresh()->status);
    }
}
