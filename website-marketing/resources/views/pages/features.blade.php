@extends('layouts.marketing')

@section('title', 'Fitur SIMS — Akademik, Operasional, dan Asisten Guru')
@section('description', 'Jelajahi fitur nyata SIMS: akademik, absensi, ruang kelas digital, keuangan, sarpras, Asisten Guru, keamanan, dan layanan pendukung.')
@section('og_title', 'Fitur SIMS — Satu Ekosistem untuk Sekolah')
@section('og_description', 'Lihat modul SIMS yang menghubungkan pekerjaan akademik dan operasional sekolah.')

@section('content')
@php
    $groups = [
        [
            'id' => 'akademik',
            'label' => 'Akademik',
            'icon' => 'graduation-cap',
            'copy' => 'Alur pembelajaran, penilaian, dan pelaporan yang saling terhubung.',
            'items' => [
                ['Penilaian Kurikulum Merdeka', 'Formatif, sumatif, PTS, PAS, KKTP, materi dan Tujuan Pembelajaran, penjabaran, deskripsi, serta konfirmasi/kunci rapor.', 'notebook-tabs'],
                ['Rapor', 'Rekap nilai, cetak rapor PDF, deskripsi rapor, dan kop rapor yang dapat disesuaikan.', 'file-text'],
                ['Ruang Kelas digital', 'Materi, tugas, pengumpulan, penilaian, transfer nilai, komentar, mode kunci ujian, dan pemantauan anti-cheat.', 'presentation'],
                ['Forum Diskusi Kelas', 'Topik, komentar, reaksi, pin/lock, komentar terbaik, dan kehadiran real-time.', 'messages-square'],
            ],
        ],
        [
            'id' => 'kehadiran',
            'label' => 'Kehadiran',
            'icon' => 'scan-face',
            'copy' => 'Pilihan presensi yang sesuai dengan aktivitas harian sekolah.',
            'items' => [
                ['Absensi wajah', 'Mendukung pencatatan kehadiran melalui verifikasi wajah.', 'scan-face'],
                ['QR + validasi GPS', 'Absensi QR harian disertai validasi lokasi GPS.', 'map-pin-check'],
                ['Mode kiosk piket', 'Alur absensi kiosk tanpa login untuk kebutuhan piket.', 'monitor-smartphone'],
                ['Presensi guru', 'Pencatatan kehadiran guru dalam ekosistem yang sama.', 'user-check'],
            ],
        ],
        [
            'id' => 'keuangan',
            'label' => 'Keuangan',
            'icon' => 'wallet-cards',
            'copy' => 'Kelola SPP dan verifikasi pembayaran secara lebih tertata.',
            'items' => [
                ['Tagihan 12 bulan', 'Tagihan mengikuti tahun ajaran Juli hingga Juni.', 'calendar-range'],
                ['Virtual Account manual', 'Mendukung pencatatan Virtual Account secara manual.', 'landmark'],
                ['Bukti pembayaran', 'Upload bukti pembayaran untuk ditinjau oleh petugas.', 'receipt-text'],
                ['Verifikasi dua tahap', 'Alur pemeriksaan pembayaran melalui dua tahap verifikasi.', 'badge-check'],
            ],
        ],
        [
            'id' => 'sarpras',
            'label' => 'Sarpras',
            'icon' => 'warehouse',
            'copy' => 'Siklus sarana prasarana lengkap dari inventaris hingga laporan.',
            'items' => [
                ['Inventaris & denah', 'Aset, kategori, serta denah ruangan interaktif.', 'boxes'],
                ['Booking & peminjaman', 'Booking ruangan dan pencatatan peminjaman sarana.', 'calendar-check-2'],
                ['Kerusakan & perbaikan', 'Laporan kerusakan, tindak lanjut perbaikan, dan penugasan teknisi.', 'wrench'],
                ['Siklus aset', 'Pemeliharaan, mutasi, pengadaan, penghapusan, supplier, dan laporan.', 'refresh-cw'],
            ],
        ],
        [
            'id' => 'ai',
            'label' => 'Asisten Guru',
            'icon' => 'bot',
            'copy' => 'Google Gemini membantu guru dan peran lain bekerja dengan informasi sekolah.',
            'items' => [
                ['Asisten Guru', 'Membuat soal/kuis, ekspor Word, merangkum, menyusun feedback, dan alur mengajar.', 'wand-sparkles'],
                ['Chatbot semua peran', 'Bantuan percakapan yang tersedia sesuai konteks peran pengguna.', 'message-circle-more'],
                ['Narasi data', 'Membantu mengubah data menjadi narasi yang lebih mudah dibaca.', 'chart-no-axes-combined'],
                ['RAG + grounding web', 'Tanya-jawab dokumen dengan sitasi serta grounding web.', 'book-open-check'],
            ],
        ],
        [
            'id' => 'pendukung',
            'label' => 'Pendukung',
            'icon' => 'blocks',
            'copy' => 'Layanan tambahan untuk komunikasi, disiplin, akses, dan keamanan.',
            'items' => [
                ['Informasi sekolah', 'Pengumuman, kalender, agenda, ekstrakurikuler, dan jadwal pelajaran.', 'calendar-days'],
                ['Kedisiplinan', 'Poin siswa dan pencatatan pelanggaran (P3).', 'shield-alert'],
                ['Layanan pengguna', 'Kartu pelajar dan chatbot helpdesk dua arah.', 'badge-help'],
                ['Mobile & akses modern', 'Push Android (FCM), aplikasi Android, serta login WebAuthn dengan sidik jari atau Face ID.', 'smartphone'],
                ['Keamanan aplikasi', 'Rate-limit login, RBAC, CSRF, file sensitif di disk privat, anti-clickjacking, dan HSTS.', 'shield-check'],
            ],
        ],
    ];
@endphp

<section class="relative overflow-hidden border-b border-slate-200 py-20 sm:py-28 dark:border-slate-800">
    <div class="grid-fade absolute inset-0 -z-10"></div>
    <div class="shell">
        <span class="eyebrow">Inventaris fitur nyata</span>
        <div class="mt-6 grid gap-8 lg:grid-cols-[1fr_.65fr] lg:items-end">
            <h1 class="max-w-4xl text-5xl font-bold tracking-[-0.055em] text-ink sm:text-6xl dark:text-white">Satu sistem untuk alur sekolah yang saling bergantung.</h1>
            <p class="text-lg leading-8 text-slate-600 dark:text-slate-400">Setiap modul di halaman ini berasal dari kemampuan yang benar-benar tersedia di SIMS—tanpa klaim fitur tambahan.</p>
        </div>
        <nav class="mt-10 flex flex-wrap gap-2" aria-label="Kategori fitur">
            @foreach ($groups as $group)
                <a href="#{{ $group['id'] }}" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-ink transition hover:border-tide hover:text-tide-dark dark:border-slate-700 dark:bg-slate-900 dark:text-white">{{ $group['label'] }}</a>
            @endforeach
        </nav>
    </div>
</section>

@foreach ($groups as $index => $group)
    <section id="{{ $group['id'] }}" class="scroll-mt-24 py-20 sm:py-24 {{ $index % 2 === 1 ? 'bg-mist dark:bg-slate-900/45' : '' }}">
        <div class="shell grid gap-10 lg:grid-cols-[.34fr_.66fr]">
            <div>
                <span class="grid size-14 place-items-center rounded-2xl bg-ink text-white dark:bg-tide dark:text-slate-950"><i data-lucide="{{ $group['icon'] }}" class="size-6" aria-hidden="true"></i></span>
                <h2 class="mt-5 text-3xl font-bold tracking-[-0.035em] text-ink dark:text-white">{{ $group['label'] }}</h2>
                <p class="mt-3 max-w-sm leading-7 text-slate-600 dark:text-slate-400">{{ $group['copy'] }}</p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach ($group['items'] as $item)
                    <article class="surface-card p-6">
                        <div class="flex items-start gap-4">
                            <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-tide/10 text-tide-dark dark:text-teal-300"><i data-lucide="{{ $item[2] }}" class="size-4" aria-hidden="true"></i></span>
                            <div>
                                <h3 class="font-bold text-ink dark:text-white">{{ $item[0] }}</h3>
                                <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">{{ $item[1] }}</p>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endforeach

<section class="bg-ink py-20 text-white dark:bg-slate-900">
    <div class="shell flex flex-col items-start justify-between gap-8 md:flex-row md:items-center">
        <div><p class="text-sm font-bold uppercase tracking-[.14em] text-teal-300">Lihat dalam konteks sekolah Anda</p><h2 class="mt-3 text-3xl font-bold tracking-[-0.03em] sm:text-4xl">Pilih fitur yang ingin Anda eksplorasi saat demo.</h2></div>
        <a href="{{ route('contact') }}" class="inline-flex min-h-12 shrink-0 items-center gap-2 rounded-xl bg-tide px-5 py-3 text-sm font-bold text-slate-950 transition hover:-translate-y-0.5 hover:bg-teal-300">Minta demo <i data-lucide="arrow-up-right" class="size-4" aria-hidden="true"></i></a>
    </div>
</section>
@endsection
