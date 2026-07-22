<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    @include('kartu-guru._card-style')
    <style>
        @page { margin: 0; }
        body { margin: 0; }
        /* Kertas PDF persis seukuran kartu — kartu menempel penuh tanpa tepi.
           Border dibuang & tinggi dikurangi 0,3mm: tinggi kartu + border yang melebihi
           tinggi kertas walau sepersekian mm membuat dompdf melempar elemen terakhir
           (kotak QR) ke halaman 2. */
        .kg-card { border-radius: 0; border: none; height: 85.3mm; }
    </style>
</head>
<body>
    @include('kartu-guru._card')
</body>
</html>
