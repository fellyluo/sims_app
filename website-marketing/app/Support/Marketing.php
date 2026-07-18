<?php

namespace App\Support;

class Marketing
{
    public static function money(?int $amount): string
    {
        if ($amount === null) {
            return 'Rp —';
        }

        return 'Rp '.number_format($amount, 0, ',', '.');
    }

    public static function taxAmount(?int $amount, ?int $rate = null): ?int
    {
        if ($amount === null) {
            return null;
        }

        $rate ??= (int) config('marketing.ppn_rate');

        return (int) round($amount * $rate / 100);
    }

    public static function totalWithTax(?int $amount, ?int $rate = null): ?int
    {
        if ($amount === null) {
            return null;
        }

        return $amount + (self::taxAmount($amount, $rate) ?? 0);
    }

    public static function whatsappDigits(): string
    {
        return preg_replace('/\D+/', '', (string) config('marketing.contact.whatsapp')) ?: '';
    }

    public static function whatsappDisplay(): string
    {
        $digits = self::whatsappDigits();

        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '62')) {
            return '0'.substr($digits, 2);
        }

        return $digits;
    }
}
