<?php

$file = 'docs/PANDUAN_PENGGUNAAN_SIMS_APP.md';
$content = file_get_contents($file);

// 1. Remove Section 1
$content = preg_replace('/## 1\. Ringkasan Peran dan Akses.*?## 2\./s', '## 1.', $content);

// 2. Renumber sections. Since 2 became 1, let's fix the numbering of all H2s.
// We'll reset numbering to 1 based on appearance.
$counter = 1;
$content = preg_replace_callback('/^## \d+\./m', function($matches) use (&$counter) {
    return '## ' . ($counter++) . '.';
}, $content);

// 3. Clarify terse headings and phrases
$replacements = [
    '/^Cara pakai:$/m' => 'Berikut adalah panduan langkah demi langkah untuk menggunakannya:',
    '/^Cara pakai admin:$/m' => 'Berikut adalah langkah-langkah penggunaan khusus untuk Admin:',
    '/^Cara pakai pengguna:$/m' => 'Berikut adalah langkah-langkah penggunaan untuk pengguna umum:',
    '/^Cara pakai siswa\/guru:$/m' => 'Langkah-langkah untuk Siswa dan Guru:',
    '/^Cara admin\/kurikulum:$/m' => 'Langkah-langkah untuk Admin atau Kurikulum:',
    '/^Cara guru:$/m' => 'Langkah-langkah khusus untuk Guru:',
    '/^Cara guru membuat materi:$/m' => 'Langkah-langkah bagi Guru untuk membuat materi pembelajaran:',
    '/^Cara guru membuat tugas:$/m' => 'Langkah-langkah bagi Guru untuk membuat tugas:',
    '/^Cara siswa membaca materi:$/m' => 'Langkah-langkah bagi Siswa untuk membaca materi:',
    '/^Cara siswa mengumpulkan tugas:$/m' => 'Langkah-langkah bagi Siswa untuk mengumpulkan tugas:',
    '/^Cara guru menilai tugas:$/m' => 'Langkah-langkah bagi Guru untuk menilai tugas yang dikumpulkan:',
    '/^Cara guru mengisi nilai:$/m' => 'Langkah-langkah bagi Guru untuk mengisi nilai akhir:',
    '/^Cara tambah guru:$/m' => 'Langkah-langkah untuk menambahkan data guru baru:',
    '/^Cara impor guru:$/m' => 'Langkah-langkah untuk mengimpor data guru secara massal:',
    '/^Cara atur mengajar:$/m' => 'Langkah-langkah untuk mengatur jadwal mengajar:',
    '/^Cara reset akun guru:$/m' => 'Langkah-langkah untuk melakukan reset akun guru:',
    '/^Cara tambah siswa:$/m' => 'Langkah-langkah untuk menambahkan data siswa baru:',
    '/^Cara impor siswa:$/m' => 'Langkah-langkah untuk mengimpor data siswa secara massal:',
    '/^Cara reset akun:$/m' => 'Langkah-langkah untuk melakukan reset akun:',
    '/^Cara melihat pengumuman:$/m' => 'Langkah-langkah untuk melihat daftar pengumuman:',
    '/^Cara membuat pengumuman:$/m' => 'Langkah-langkah untuk membuat pengumuman baru:',
    '/^Cara mengubah\/menghapus:$/m' => 'Langkah-langkah untuk mengubah atau menghapus data:',
    '/^Fungsi:$/m' => 'Fitur ini menyediakan beberapa fungsi utama, di antaranya:',
    '/^Menu: (.*?)$/m' => 'Untuk mengakses fitur ini, silakan buka menu **$1** pada bilah navigasi (sidebar).',
    '/^1\. Buka `(.*?)`\.$/m' => '1. Silakan navigasikan ke menu `$1`.',
    '/^1\. Buka (.*?)$/m' => '1. Silakan buka halaman atau menu $1.',
    '/^2\. Masukkan nama pengguna dan kata sandi\.$/m' => '2. Masukkan nama pengguna (username) dan kata sandi (password) Anda dengan benar.',
    '/^3\. Klik masuk\.$/m' => '3. Klik tombol "Masuk" untuk melanjutkan ke dalam aplikasi.',
    '/^4\. Simpan\.$/m' => '4. Terakhir, klik tombol "Simpan" untuk menerapkan perubahan.',
    '/^5\. Simpan\.$/m' => '5. Terakhir, klik tombol "Simpan" untuk menerapkan perubahan.',
    '/^6\. Simpan\.$/m' => '6. Terakhir, klik tombol "Simpan" untuk menerapkan perubahan.',
    '/^7\. Simpan\.$/m' => '7. Terakhir, klik tombol "Simpan" untuk menerapkan perubahan.',
    '/^4\. Hapus (.*?)$/m' => '4. Anda juga dapat menghapus $1 jika data tersebut tidak lagi diperlukan.',
    '/^Atur profil dan tampilan/m' => 'Anda dapat mengatur informasi profil dan preferensi tampilan',
    '/^Pilih fitur: /m' => 'Silakan pilih fitur yang ingin digunakan: ',
    '/^Gunakan `(.*?)` untuk /m' => 'Anda dapat memanfaatkan fitur `$1` untuk ',
];

$content = preg_replace(array_keys($replacements), array_values($replacements), $content);

// Ensure we don't mess up the very first lines
file_put_contents($file, $content);
echo "Done";
