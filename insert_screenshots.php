<?php

$file = 'docs/PANDUAN_PENGGUNAAN_SIMS_APP.md';
$content = file_get_contents($file);

$insertions = [
    '/### Masuk\s*(.*?)\s*Berikut adalah panduan/' => "### Masuk\n\n![Tampilan Halaman Masuk (\/Login)](/images/panduan/login.png)\n\n$1\nBerikut adalah panduan",
    '/## \d+\. Dasbor dan Notifikasi\s*### Dasbor\s*(.*?)\s*Fitur ini/' => "## 3. Dasbor dan Notifikasi\n\n### Dasbor\n\n![Tampilan Dasbor Utama](/images/panduan/dashboard.png)\n\n$1\nFitur ini",
    '/## \d+\. Pengumuman\s*Untuk mengakses fitur ini/' => "## 4. Pengumuman\n\n![Halaman Pengumuman](/images/panduan/pengumuman.png)\n\nUntuk mengakses fitur ini",
    '/### Data Guru\s*(.*?)\s*Fitur ini/' => "### Data Guru\n\n![Halaman Data Guru](/images/panduan/data_guru.png)\n\n$1\nFitur ini",
    '/### Kalender Absensi\s*(.*?)\s*Fitur ini/' => "### Kalender Absensi\n\n![Tampilan Kalender Absensi](/images/panduan/kalender_absensi.png)\n\n$1\nFitur ini",
    '/### Ruang Kelas\s*(.*?)\s*Untuk mengakses fitur/' => "### Ruang Kelas\n\n![Tampilan Ruang Kelas Akademik](/images/panduan/ruang_kelas.png)\n\n$1\nUntuk mengakses fitur",
    '/### Penilaian \/ Buku Guru\s*(.*?)\s*Untuk mengakses fitur/' => "### Penilaian / Buku Guru\n\n![Tampilan Buku Nilai Guru](/images/panduan/penilaian.png)\n\n$1\nUntuk mengakses fitur",
];

foreach ($insertions as $pattern => $replacement) {
    $content = preg_replace($pattern, $replacement, $content, 1);
}

file_put_contents($file, $content);

$dir = 'public/images/panduan';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

// Generate placeholder text file to guide the user
file_put_contents($dir . '/README.txt', "Letakkan gambar-gambar screenshot di folder ini (Timpa jika file sudah ada):\n1. login.png\n2. dashboard.png\n3. pengumuman.png\n4. data_guru.png\n5. kalender_absensi.png\n6. ruang_kelas.png\n7. penilaian.png\n\nPastikan formatnya PNG agar langsung terbaca di dokumen panduan.");

echo "Done";
