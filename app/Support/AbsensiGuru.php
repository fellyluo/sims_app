<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Metode absensi sekolah yang sedang aktif (berlaku untuk GURU & SISWA).
 * Hanya satu metode self-service aktif: wajah ATAU barcode/QR — metode lain dikunci.
 * Input MANUAL oleh admin selalu diperbolehkan (tidak terikat pilihan ini).
 * Key setting: cara_absensi_guru.
 */
class AbsensiGuru
{
    public const LABEL = [
        'wajah'   => 'Scan Wajah',
        'barcode' => 'Barcode / QR',
    ];

    public static function cara(): string
    {
        $c = Setting::get('cara_absensi_guru', 'wajah');
        return array_key_exists($c, self::LABEL) ? $c : 'wajah';   // legacy 'manual' → wajah
    }

    public static function label(?string $c = null): string
    {
        $c = $c ?: self::cara();
        return self::LABEL[$c] ?? ucfirst($c);
    }

    public static function bolehWajah(): bool { return self::cara() === 'wajah'; }
    public static function bolehQr(): bool    { return self::cara() === 'barcode'; }

    /** Pesan standar saat sebuah metode dikunci. */
    public static function pesanKunci(string $metode): string
    {
        return 'Absensi via ' . ($metode) . ' sedang dikunci. Metode aktif: ' . self::label() . '. Ubah di Pengaturan → Absensi.';
    }
}
