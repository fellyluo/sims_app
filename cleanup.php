<?php
$file = 'docs/PANDUAN_PENGGUNAAN_SIMS_APP.md';
$c = file_get_contents($file);
$c = str_replace('..', '.', $c);
$c = str_replace('Silakan buka halaman atau menu aplikasi', 'Buka aplikasi', $c);
file_put_contents($file, $c);
echo "Done";
