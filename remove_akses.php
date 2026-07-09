<?php
$file = 'docs/PANDUAN_PENGGUNAAN_SIMS_APP.md';
$content = file_get_contents($file);
$content = preg_replace('/^Akses:.*(\r?\n(?:- .*)*)?\r?\n?/m', '', $content);
file_put_contents($file, $content);
echo "Done";
