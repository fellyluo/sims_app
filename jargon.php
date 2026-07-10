<?php

$file = 'docs/PANDUAN_PENGGUNAAN_SIMS_APP.md';
$content = file_get_contents($file);

$replacements = [
    '/\bthrottle untuk mengurangi brute force\b/i' => 'sistem pembatasan waktu tunggu (jeda) otomatis jika salah memasukkan kata sandi berkali-kali untuk mencegah percobaan pembobolan paksa',
    '/\bgate wajah\b/i' => 'persyaratan wajib pemindaian wajah',
    '/\bFCM token dari Android WebView\b/i' => 'notifikasi langsung pada perangkat telepon pintar (Android) Anda',
    '/\bhandoff ke admin\b/i' => 'penyerahan percakapan bot kepada staf Admin sekolah',
    '/\bpolling pesan baru\b/i' => 'pemeriksaan pesan masuk secara otomatis',
    '/\bbadge unread\b/i' => 'tanda lingkaran merah untuk pesan atau pemberitahuan yang belum dibaca',
    '/\bgrounding Google Search sesuai intent\b/i' => 'penelusuran cerdas berbasis mesin pencari Google sesuai dengan pertanyaan Anda',
    '/\bRAG\b/i' => 'Pencarian Referensi',
    '/\bRBAC\b/i' => 'Hak Akses Sistem',
    '/\bGate berbasis users.access\b/i' => 'pembatasan otomatis berdasarkan peran masing-masing akun pengguna',
    '/\bAuto-provision\b/i' => 'Pembuatan otomatis',
    '/\bLock event\b/i' => 'Riwayat penguncian kelas',
    '/\bsubmission\b/i' => 'tugas yang telah dikumpulkan oleh siswa',
    '/\bGenerate default\b/i' => 'Buat secara otomatis memakai desain standar',
    '/\bfile ZIP\b/i' => 'kumpulan berkas terkompresi (ZIP)',
    '/\bZIP\b/i' => 'berkas terkompresi',
    '/\bUI\b/i' => 'antarmuka aplikasi',
    '/\bticker statistik\b/i' => 'teks berjalan yang berisi informasi statistik',
    '/\bdrag\/drop\b/i' => 'seret dan lepas (klik, tahan, lalu geser)',
    '/\bgateway\b/i' => 'jalur utama',
    '/\bWebAuthn\/Fingerprint\/Face ID\b/i' => 'sidik jari atau pemindai wajah pada perangkat Anda',
    '/\bsource aplikasi\b/i' => 'kode sumber aplikasi',
    '/\bcontroller, dan view utama\b/i' => 'pengendali dan antarmuka utama',
    '/\broute\b/i' => 'jalur halaman (rute)',
    '/\bSoft dan Analyzer\b/i' => 'Lembut dan Analitis',
    '/\bbulk\b/i' => 'massal (sekaligus banyak)',
    '/\btoggle\b/i' => 'tombol on/off (alih)',
    '/\bbulk set\b/i' => 'pengaturan massal',
    '/\bexport excel\b/i' => 'unduh rekap dalam bentuk format Excel',
    '/\blink meet\b/i' => 'tautan pertemuan tatap muka virtual (Video Conference)',
    '/\bgenerate\b/i' => 'buat secara otomatis',
    '/\bresize\b/i' => 'ubah ukuran',
    '/\bbaseline\b/i' => 'dasar penyesuaian',
    '/\bcache\b/i' => 'penyimpanan memori sementara',
    '/\btab\b/i' => 'halaman bagian',
    '/\bdropdown\b/i' => 'menu yang bisa diklik untuk memunculkan pilihan ke bawah',
    '/\bfloating chat\b/i' => 'tombol obrolan melayang di pojok layar',
    '/\brate limit\b/i' => 'batas jumlah permintaan',
];

$content = preg_replace(array_keys($replacements), array_values($replacements), $content);

file_put_contents($file, $content);
echo "Done";
