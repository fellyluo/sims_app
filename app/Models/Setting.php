<?php

namespace App\Models;

use ArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    /** Penanda "baris tidak ada" di memo — dibedakan dari nilai null yang sah. */
    private const ABSENT = false;

    /**
     * Memo per-request. Satu render halaman membaca puluhan setting (layout saja
     * mengecek 22 modul), dan tiap pembacaan sebelumnya = 1 query. Memo disimpan
     * di container agar otomatis segar tiap request — dan tiap test, karena
     * Laravel membangun ulang aplikasi per test.
     */
    private static function memo(): ArrayObject
    {
        if (! app()->bound('setting.memo')) {
            app()->instance('setting.memo', new ArrayObject());
        }

        return app('setting.memo');
    }

    /**
     * Memo dijaga lewat event Eloquent, bukan hanya di set(), supaya penulisan
     * lewat jalur mana pun (create/update/delete langsung) tetap konsisten.
     */
    protected static function booted(): void
    {
        static::saved(function (self $setting): void {
            self::memo()[$setting->key] = ['value' => $setting->value];
        });

        static::deleted(function (self $setting): void {
            self::memo()->offsetUnset($setting->key);
        });
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $memo = self::memo();

        if (! $memo->offsetExists($key)) {
            $setting = static::where('key', $key)->first();
            $memo[$key] = $setting ? ['value' => $setting->value] : self::ABSENT;
        }

        $hit = $memo[$key];

        return $hit === self::ABSENT ? $default : $hit['value'];
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
