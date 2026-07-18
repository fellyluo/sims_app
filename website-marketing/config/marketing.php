<?php

return [
    'domain' => env('MARKETING_DOMAIN'),
    'app_url' => rtrim(env('SIMS_APP_URL', 'https://app.example.sch.id'), '/'),
    'ppn_rate' => (int) env('PPN_RATE', 11),

    'contact' => [
        'email' => env('CONTACT_EMAIL', 'btivesolution@gmail.com'),
        'whatsapp' => env('CONTACT_WHATSAPP', '6285668330050'),
        'address' => env('CONTACT_ADDRESS', 'Tanjungpinang, Kepulauan Riau'),
    ],

    'leads' => [
        'notification_email' => env('LEAD_NOTIFICATION_EMAIL', 'btivesolution@gmail.com'),
    ],

    /*
    | Harga paket (rupiah penuh, belum termasuk PPN).
    | Survei pasar ID 2026: SaaS sekolah umum ~Rp150–600rb/bulan; enterprise custom.
    | SIMS diposisikan menengah–atas (rapor KM + kelas digital + SPP + absensi wajah + AI).
    | Diskon prepaid: 12 bulan paling hemat ≈ setara bulanan terendah.
    */
    'prices' => [
        'dasar' => [
            3 => 1_200_000,   // ≈ Rp400rb/bulan
            6 => 2_100_000,   // ≈ Rp350rb/bulan
            12 => 3_600_000,  // ≈ Rp300rb/bulan
        ],
        'pro' => [
            3 => 1_950_000,   // ≈ Rp650rb/bulan
            6 => 3_600_000,   // ≈ Rp600rb/bulan
            12 => 6_000_000,  // ≈ Rp500rb/bulan
        ],
        // Enterprise: ditampilkan sebagai "mulai dari"; final bisa dinegosiasi.
        'enterprise' => [
            3 => 3_600_000,   // ≈ Rp1,2jt/bulan
            6 => 6_600_000,   // ≈ Rp1,1jt/bulan
            12 => 12_000_000, // ≈ Rp1jt/bulan
        ],
    ],
];
