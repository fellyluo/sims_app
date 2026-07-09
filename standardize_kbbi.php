<?php

$file = 'docs/PANDUAN_PENGGUNAAN_SIMS_APP.md';
$content = file_get_contents($file);

// Split by code blocks and inline code
$parts = preg_split('/(`[^`]+`|```.*?```)/s', $content, -1, PREG_SPLIT_DELIM_CAPTURE);

$replacements = [
    '/\buser\b/i' => 'pengguna',
    '/\blogin\b/i' => 'masuk',
    '/\blogout\b/i' => 'keluar',
    '/\bpassword\b/i' => 'kata sandi',
    '/\busername\b/i' => 'nama pengguna',
    '/\bupload\b/i' => 'unggah',
    '/\bdownload\b/i' => 'unduh',
    '/\bedit\b/i' => 'sunting',
    '/\bdashboard\b/i' => 'dasbor',
    '/\breview\b/i' => 'tinjauan',
    '/\bfile\b/i' => 'berkas',
    '/\bchat\b/i' => 'obrolan',
    '/\bchatbot\b/i' => 'bot obrolan',
    '/\bonline\b/i' => 'daring',
    '/\boffline\b/i' => 'luring',
    '/\bscan\b/i' => 'pindai',
    '/\bprint\b/i' => 'cetak',
    '/\bgenerate\b/i' => 'buat',
    '/\bcopy\b/i' => 'salin',
    '/\bexport\b/i' => 'ekspor',
    '/\bimport\b/i' => 'impor',
    '/\bform\b/i' => 'formulir',
    '/\berror\b/i' => 'galat',
    '/\bdefault\b/i' => 'bawaan',
    '/\bcustom\b/i' => 'kustom',
    '/\bsetting\b/i' => 'pengaturan',
    '/\bsubmit\b/i' => 'kirim',
    '/\bdetail\b/i' => 'rincian',
    '/\bupdate\b/i' => 'perbarui',
    '/\bview\b/i' => 'tampilan',
    '/\btemplate\b/i' => 'templat',
    '/\brole\b/i' => 'peran',
    '/\bpasswordnya\b/i' => 'kata sandinya',
    '/\busernamenya\b/i' => 'nama penggunanya',
    '/\bdropdown\b/i' => 'menu tarik-turun',
    '/\bticker\b/i' => 'teks berjalan',
    '/\breal-time\b/i' => 'waktu nyata',
    '/\bwidget\b/i' => 'gawit',
    '/\bdrag\/drop\b/i' => 'seret/lepas',
    '/\bbadge\b/i' => 'lencana',
    '/\bfast\b/i' => 'cepat',
    '/\bFAQ\b/i' => 'Tanya Jawab',
    '/\bhandoff\b/i' => 'alih tugas',
    '/\bassign\b/i' => 'tugaskan',
    '/\bquick questions\b/i' => 'pertanyaan cepat',
    '/\bintent\b/i' => 'niat',
    '/\bfeedback\b/i' => 'umpan balik',
    '/\bfilter\b/i' => 'penyaring',
    '/\bsorting\b/i' => 'pengurutan',
    '/\bsort\b/i' => 'urutkan',
    '/\bbulk\b/i' => 'massal',
    '/\btoggle\b/i' => 'alih',
    '/\bauto-provision\b/i' => 'penyediaan otomatis',
    '/\bsubmission\b/i' => 'pengumpulan',
    '/\bevent\b/i' => 'peristiwa',
    '/\blink\b/i' => 'tautan',
    '/\bdeadline\b/i' => 'tenggat waktu',
    '/\bgrid\b/i' => 'kisi',
    '/\bexport excel\b/i' => 'ekspor ke Excel',
    '/\bmiss\b/i' => 'terlewat',
    '/\btraining\b/i' => 'pelatihan',
    '/\bmiss saat training\b/i' => 'terlewat saat pelatihan',
];

for ($i = 0; $i < count($parts); $i++) {
    // Only process text outside of backticks (even indices in preg_split with PREG_SPLIT_DELIM_CAPTURE)
    if ($i % 2 === 0) {
        foreach ($replacements as $pattern => $replacement) {
            // Use preg_replace_callback to preserve original case matching (basic implementation)
            $parts[$i] = preg_replace_callback($pattern, function($matches) use ($replacement) {
                $original = $matches[0];
                if (ctype_upper($original)) {
                    return strtoupper($replacement);
                } elseif (ctype_upper(substr($original, 0, 1))) {
                    return ucfirst($replacement);
                } else {
                    return $replacement;
                }
            }, $parts[$i]);
        }
    }
}

$newContent = implode('', $parts);
file_put_contents($file, $newContent);
echo "Done";
