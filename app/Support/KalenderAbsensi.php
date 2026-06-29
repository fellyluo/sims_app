<?php

namespace App\Support;

use App\Models\HariEfektif;
use App\Models\Setting;

/**
 * Kalender hari efektif: admin/kurikulum menentukan per tanggal apakah siswa boleh
 * absen dan apakah guru wajib mengisi agenda. Penegakan opsional via master toggle —
 * bila nonaktif, perilaku default (tanpa batas) dipertahankan.
 */
class KalenderAbsensi
{
    /** Cache per-request: ymd => HariEfektif|false. */
    private static array $cache = [];

    public static function absenEnforced(): bool
    {
        return Setting::get('kalender_absen_aktif', '0') === '1';
    }

    public static function agendaEnforced(): bool
    {
        return Setting::get('kalender_agenda_aktif', '0') === '1';
    }

    public static function row(string $tanggal): ?HariEfektif
    {
        $key = substr($tanggal, 0, 10);
        if (!array_key_exists($key, self::$cache)) {
            self::$cache[$key] = HariEfektif::whereDate('tanggal', $key)->first() ?: false;
        }
        return self::$cache[$key] ?: null;
    }

    /** Apakah siswa boleh absen pada tanggal ini. */
    public static function absenSiswaDibuka(string $tanggal): bool
    {
        if (!self::absenEnforced()) {
            return true;
        }
        return (bool) (self::row($tanggal)?->absen_siswa);
    }

    /** Apakah guru wajib mengisi agenda pada tanggal ini. */
    public static function agendaWajib(string $tanggal): bool
    {
        if (!self::agendaEnforced()) {
            return true;
        }
        return (bool) (self::row($tanggal)?->agenda_guru);
    }

    /** Buang cache (mis. setelah update massal). */
    public static function lupakanCache(): void
    {
        self::$cache = [];
    }
}
