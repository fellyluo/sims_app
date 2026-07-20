<?php

namespace App\Support;

use App\Models\Absensi;
use App\Models\Siswa;
use App\Notifications\StudentAttendanceRecorded;
use Illuminate\Support\Facades\Log;

class AttendanceParentNotifier
{
    /**
     * Kirim notifikasi ke orang tua saat status absensi siswa berubah
     * (hadir / izin / sakit / alpa). Diam jika status sama (anti-spam).
     */
    public static function notifyIfStatusChanged(?string $previousStatus, Absensi $absensi): void
    {
        if ($previousStatus === $absensi->status) {
            return;
        }

        self::notify($absensi);
    }

    public static function notify(Absensi $absensi): void
    {
        $siswa = Siswa::with(['kelas', 'orangtua.user'])->find($absensi->id_siswa);
        $parentUser = $siswa?->orangtua?->user;

        if (! $siswa) {
            return;
        }

        if (! $parentUser) {
            Log::warning('Absensi ortu: siswa belum punya akun orang tua terhubung', [
                'siswa_id' => $siswa->uuid,
                'siswa_nama' => $siswa->nama,
                'tanggal' => $absensi->tanggal?->format('Y-m-d'),
                'status' => $absensi->status,
            ]);

            return;
        }

        $parentUser->notify(new StudentAttendanceRecorded($siswa, $absensi));
    }
}
