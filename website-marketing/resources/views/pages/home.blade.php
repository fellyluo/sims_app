@extends('layouts.marketing')

@section('title', 'SIMS — Satu Sistem untuk Mengelola Sekolah')
@section('description', 'SIMS menyatukan akademik, kehadiran, keuangan, sarpras, ruang kelas digital, dan Asisten Guru dalam satu sistem sekolah.')
@section('og_title', 'SIMS — Sekolah Terhubung, Keputusan Lebih Terarah')
@section('og_description', 'Lihat bagaimana SIMS membantu sekolah mengelola akademik, operasional, dan layanan pendukung dalam satu alur.')

@section('content')
<section class="relative overflow-hidden">
    <div class="grid-fade absolute inset-0 -z-10"></div>
    <div class="absolute top-20 -right-24 -z-10 size-80 rounded-full bg-tide/15 blur-3xl dark:bg-tide/10"></div>
    <div class="absolute bottom-10 -left-40 -z-10 size-96 rounded-full bg-sun/18 blur-3xl dark:bg-sun/8"></div>

    <div class="shell grid min-h-[calc(100vh-4.5rem)] items-center gap-12 py-16 lg:grid-cols-[1.04fr_.96fr] lg:py-20">
        <div>
            <span class="eyebrow">
                <i data-lucide="sparkles" class="size-3.5" aria-hidden="true"></i>
                Sistem sekolah yang benar-benar terhubung
            </span>
            <h1 class="mt-6 max-w-3xl text-5xl font-bold leading-[1.04] tracking-[-0.055em] text-ink sm:text-6xl lg:text-7xl dark:text-white">
                Lebih sedikit urusan sistem. <span class="text-tide-dark dark:text-teal-300">Lebih banyak fokus untuk sekolah.</span>
            </h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600 sm:text-xl dark:text-slate-400">
                SIMS menyatukan akademik, kehadiran, keuangan, sarpras, ruang kelas digital, dan Asisten Guru dalam satu alur kerja yang rapi.
            </p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="#minta-demo" class="btn-primary">
                    Minta demo
                    <i data-lucide="arrow-up-right" class="size-4" aria-hidden="true"></i>
                </a>
                <a href="{{ config('marketing.app_url').'/login' }}" class="btn-secondary">
                    <i data-lucide="log-in" class="size-4" aria-hidden="true"></i>
                    Masuk ke aplikasi
                </a>
            </div>
            <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-slate-500 dark:text-slate-400">
                <span class="flex items-center gap-2"><i data-lucide="check" class="size-4 text-tide" aria-hidden="true"></i> Satu alur dari kelas ke laporan</span>
                <span class="flex items-center gap-2"><i data-lucide="check" class="size-4 text-tide" aria-hidden="true"></i> Akses sesuai peran</span>
                <span class="flex items-center gap-2"><i data-lucide="check" class="size-4 text-tide" aria-hidden="true"></i> Asisten Guru siap dipakai</span>
            </div>
        </div>

        <div class="relative mx-auto w-full max-w-2xl lg:mx-0">
            <div class="absolute -inset-5 -z-10 rotate-2 rounded-[2.2rem] bg-gradient-to-br from-tide/20 to-sun/30 blur-xl"></div>
            <figure class="overflow-hidden rounded-[2rem] border border-white/60 bg-ink p-2 shadow-2xl shadow-ink/25 dark:border-slate-700">
                <div class="flex items-center gap-2 px-3 py-2">
                    <span class="size-2.5 rounded-full bg-rose-400"></span>
                    <span class="size-2.5 rounded-full bg-sun"></span>
                    <span class="size-2.5 rounded-full bg-tide"></span>
                    <span class="ml-3 text-[11px] font-semibold tracking-wide text-slate-400">DASHBOARD SIMS</span>
                </div>
                <img
                    src="{{ asset('images/product/dashboard.png') }}"
                    alt="Tampilan dashboard SIMS yang menampilkan ringkasan aktivitas sekolah"
                    width="1280"
                    height="800"
                    class="rounded-[1.4rem] border border-white/10"
                    loading="eager"
                    decoding="async"
                >
                <figcaption class="sr-only">Cuplikan produk SIMS dari lingkungan demo (data sensitif telah di-redact).</figcaption>
            </figure>
            <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                <img src="{{ asset('images/product/ruang-kelas.png') }}" alt="Ruang kelas digital SIMS" class="h-20 w-full rounded-xl object-cover object-top ring-1 ring-slate-200 dark:ring-slate-700" loading="lazy" width="400" height="80">
                <img src="{{ asset('images/product/asisten-guru.png') }}" alt="Asisten Guru di SIMS" class="h-20 w-full rounded-xl object-cover object-top ring-1 ring-slate-200 dark:ring-slate-700" loading="lazy" width="400" height="80">
                <img src="{{ asset('images/product/login.png') }}" alt="Halaman masuk SIMS" class="col-span-2 h-20 w-full rounded-xl object-cover object-top ring-1 ring-slate-200 sm:col-span-1 dark:ring-slate-700" loading="lazy" width="400" height="80">
            </div>
        </div>
    </div>
</section>

<section class="border-y border-slate-200 bg-white/70 dark:border-slate-800 dark:bg-slate-900/45">
    <div class="shell grid gap-8 py-8 sm:grid-cols-3">
        <div class="flex items-center gap-4"><span class="grid size-11 shrink-0 place-items-center rounded-xl bg-ink/7 text-ink dark:bg-white/8 dark:text-white"><i data-lucide="git-merge" class="size-5" aria-hidden="true"></i></span><div><p class="font-bold text-ink dark:text-white">Satu alur kerja</p><p class="text-sm text-slate-500">Akademik sampai operasional terhubung</p></div></div>
        <div class="flex items-center gap-4"><span class="grid size-11 shrink-0 place-items-center rounded-xl bg-tide/10 text-tide-dark dark:text-teal-300"><i data-lucide="user-cog" class="size-5" aria-hidden="true"></i></span><div><p class="font-bold text-ink dark:text-white">Akses per peran</p><p class="text-sm text-slate-500">Setiap pengguna melihat yang relevan</p></div></div>
        <div class="flex items-center gap-4"><span class="grid size-11 shrink-0 place-items-center rounded-xl bg-sun/18 text-amber-700 dark:text-amber-300"><i data-lucide="sparkles" class="size-5" aria-hidden="true"></i></span><div><p class="font-bold text-ink dark:text-white">Asisten Guru</p><p class="text-sm text-slate-500">Bantu soal, ringkasan, dan keputusan</p></div></div>
    </div>
</section>

<section class="py-24 sm:py-30">
    <div class="shell">
        <div class="flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
            <div>
                <span class="eyebrow">Satu ekosistem</span>
                <h2 class="section-title mt-5">Dari kelas sampai ruang sarpras,<br class="hidden sm:block"> semuanya saling terhubung.</h2>
                <p class="section-copy">Bukan kumpulan fitur terpisah. SIMS membantu setiap peran bekerja pada data dan alur yang sama.</p>
            </div>
            <a href="{{ route('features') }}" class="btn-secondary self-start lg:self-auto">Lihat seluruh fitur <i data-lucide="arrow-right" class="size-4" aria-hidden="true"></i></a>
        </div>

        <div class="mt-12 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach ([
                ['icon' => 'graduation-cap', 'title' => 'Akademik & Rapor', 'copy' => 'Penilaian Kurikulum Merdeka, KKTP, rekap nilai, deskripsi, konfirmasi, dan cetak rapor PDF.', 'accent' => 'bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300'],
                ['icon' => 'scan-face', 'title' => 'Kehadiran Terverifikasi', 'copy' => 'Absensi wajah, QR harian dengan validasi GPS, mode kiosk piket, dan presensi guru.', 'accent' => 'bg-teal-50 text-teal-700 dark:bg-teal-950 dark:text-teal-300'],
                ['icon' => 'presentation', 'title' => 'Ruang Kelas Digital', 'copy' => 'Materi, tugas, pengumpulan, komentar, transfer nilai, mode kunci ujian, dan pemantauan.', 'accent' => 'bg-violet-50 text-violet-700 dark:bg-violet-950 dark:text-violet-300'],
                ['icon' => 'wallet-cards', 'title' => 'Keuangan / SPP', 'copy' => 'Tagihan 12 bulan, Virtual Account manual, bukti pembayaran, dan verifikasi dua tahap.', 'accent' => 'bg-amber-50 text-amber-700 dark:bg-amber-950 dark:text-amber-300'],
                ['icon' => 'warehouse', 'title' => 'Sarpras Menyeluruh', 'copy' => 'Inventaris, denah interaktif, booking, peminjaman, pemeliharaan, mutasi, dan laporan.', 'accent' => 'bg-rose-50 text-rose-700 dark:bg-rose-950 dark:text-rose-300'],
                ['icon' => 'bot', 'title' => 'Asisten Guru', 'copy' => 'Bantu membuat soal & kuis, merangkum, menyusun feedback, narasi data, tanya-jawab dokumen bersitasi, dan grounding web.', 'accent' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300'],
            ] as $feature)
                <article class="surface-card group p-6 transition duration-300 hover:-translate-y-1 hover:border-tide/40 hover:shadow-glow">
                    <span class="grid size-12 place-items-center rounded-2xl {{ $feature['accent'] }}"><i data-lucide="{{ $feature['icon'] }}" class="size-5" aria-hidden="true"></i></span>
                    <h3 class="mt-6 text-xl font-bold tracking-[-0.02em] text-ink dark:text-white">{{ $feature['title'] }}</h3>
                    <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-400">{{ $feature['copy'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-ink py-24 text-white sm:py-30 dark:bg-slate-900">
    <div class="shell">
        <div class="grid gap-12 lg:grid-cols-[.85fr_1.15fr] lg:items-center">
            <div>
                <span class="eyebrow border-white/15 bg-white/8 text-teal-300">Cara kerja</span>
                <h2 class="mt-5 text-4xl font-bold tracking-[-0.04em] sm:text-5xl">Satu alur, dari data sampai keputusan.</h2>
                <p class="mt-5 text-lg leading-8 text-slate-300">Setiap aktivitas sekolah masuk ke sistem yang sama, sehingga informasi lebih mudah ditindaklanjuti oleh peran yang tepat.</p>
            </div>
            <ol class="grid gap-4">
                @foreach ([
                    ['no' => '01', 'title' => 'Kelola aktivitas utama', 'copy' => 'Akademik, presensi, keuangan, sarpras, dan layanan pendukung berjalan dalam modul yang terhubung.'],
                    ['no' => '02', 'title' => 'Akses sesuai peran', 'copy' => 'Guru, siswa, orang tua, bendahara, operator, dan peran lain melihat informasi sesuai tugasnya.'],
                    ['no' => '03', 'title' => 'Tindak lanjuti lebih cepat', 'copy' => 'Pengumuman, laporan, notifikasi, dan Asisten Guru mendukung pekerjaan harian sekolah.'],
                ] as $step)
                    <li class="grid grid-cols-[auto_1fr] gap-5 rounded-2xl border border-white/10 bg-white/5 p-5">
                        <span class="text-sm font-bold text-teal-300">{{ $step['no'] }}</span>
                        <div><h3 class="font-bold text-white">{{ $step['title'] }}</h3><p class="mt-2 text-sm leading-6 text-slate-400">{{ $step['copy'] }}</p></div>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</section>

<section class="py-24 sm:py-30">
    <div class="shell">
        <div class="overflow-hidden rounded-[2rem] bg-gradient-to-br from-teal-50 via-white to-amber-50 p-7 sm:p-12 lg:p-16 dark:from-teal-950 dark:via-slate-900 dark:to-amber-950">
            <div class="grid gap-12 lg:grid-cols-[1fr_.9fr] lg:items-center">
                <div>
                    <span class="eyebrow">Asisten Guru · Google Gemini</span>
                    <h2 class="section-title mt-5">AI yang bekerja di dalam konteks sekolah.</h2>
                    <p class="section-copy">Bantu guru menyiapkan materi, bantu pengguna menemukan jawaban, dan bantu membaca data tanpa memutus alur kerja utama.</p>
                    <div class="mt-8 grid gap-3 sm:grid-cols-2">
                        @foreach (['Buat soal & kuis + ekspor Word', 'Rangkum dan susun feedback', 'Chatbot sesuai peran', 'Narasi data sekolah', 'Tanya-jawab dokumen + sitasi', 'Grounding web'] as $item)
                            <div class="flex items-center gap-3 text-sm font-semibold text-ink dark:text-slate-100"><span class="grid size-6 place-items-center rounded-full bg-tide text-slate-950"><i data-lucide="check" class="size-3.5" aria-hidden="true"></i></span>{{ $item }}</div>
                        @endforeach
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute -inset-5 rounded-full bg-tide/15 blur-3xl"></div>
                    <figure class="relative overflow-hidden rounded-3xl border border-white/70 bg-white p-2 shadow-xl dark:border-slate-700 dark:bg-slate-950">
                        <img
                            src="{{ asset('images/product/asisten-guru.png') }}"
                            alt="Tampilan Asisten Guru di SIMS"
                            class="rounded-2xl"
                            loading="lazy"
                            width="960"
                            height="640"
                        >
                        <figcaption class="px-3 py-3 text-xs font-semibold text-slate-500">Asisten Guru — cuplikan produk (data di-redact)</figcaption>
                    </figure>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="border-y border-slate-200 bg-mist py-24 dark:border-slate-800 dark:bg-slate-900/55">
    <div class="shell">
        <div class="text-center">
            <span class="eyebrow">Paket fleksibel</span>
            <h2 class="section-title mt-5">Mulai dari kebutuhan sekolah Anda.</h2>
            <p class="section-copy mx-auto">Pilih cakupan fitur Dasar, Pro, atau Enterprise dengan durasi 3, 6, atau 12 bulan.</p>
        </div>
        <div class="mt-12 grid gap-5 lg:grid-cols-3">
            @foreach ([
                ['key' => 'dasar', 'name' => 'Dasar', 'copy' => 'Fondasi administrasi akademik dan kehadiran sekolah.', 'featured' => false],
                ['key' => 'pro', 'name' => 'Pro', 'copy' => 'Alur sekolah lebih lengkap dengan kelas digital, keuangan, dan aplikasi Android.', 'featured' => true],
                ['key' => 'enterprise', 'name' => 'Enterprise', 'copy' => 'Cakupan menyeluruh termasuk sarpras, Asisten Guru, dan kustomisasi.', 'featured' => false],
            ] as $plan)
                @php
                    $fromPrice = config('marketing.prices.'.$plan['key'].'.12');
                    $fromPrice = is_int($fromPrice) ? $fromPrice : null;
                    $priceLabel = \App\Support\Marketing::money($fromPrice);
                @endphp
                <article @class(['rounded-3xl p-7', 'bg-ink text-white shadow-xl shadow-ink/20 lg:-translate-y-3' => $plan['featured'], 'surface-card' => ! $plan['featured']])>
                    @if ($plan['featured']) <span class="rounded-full bg-tide px-3 py-1 text-xs font-bold text-slate-950">Paling diminati</span> @endif
                    <h3 @class(['mt-5 text-2xl font-bold' => $plan['featured'], 'text-2xl font-bold text-ink dark:text-white' => ! $plan['featured']])>{{ $plan['name'] }}</h3>
                    <p @class(['mt-3 text-sm leading-6', 'text-slate-300' => $plan['featured'], 'text-slate-600 dark:text-slate-400' => ! $plan['featured']])>{{ $plan['copy'] }}</p>
                    <div class="mt-7 border-t pt-6 {{ $plan['featured'] ? 'border-white/10' : 'border-slate-200 dark:border-slate-800' }}">
                        <p class="text-xs font-semibold uppercase tracking-wider {{ $plan['featured'] ? 'text-teal-300' : 'text-slate-500' }}">
                            {{ $plan['key'] === 'enterprise' ? 'Mulai dari · 12 bulan' : '12 bulan' }}
                        </p>
                        <p class="mt-2 text-3xl font-bold">{{ $priceLabel }} <span class="text-sm font-medium opacity-60">· belum PPN</span></p>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="mt-8 text-center"><a href="{{ route('pricing') }}" class="btn-primary">Bandingkan paket & fitur <i data-lucide="arrow-right" class="size-4" aria-hidden="true"></i></a></div>
    </div>
</section>

<section class="py-24 sm:py-30">
    <div class="shell grid gap-12 lg:grid-cols-[.75fr_1.25fr]">
        <div>
            <span class="eyebrow">Cerita sekolah</span>
            <h2 class="section-title mt-5">Dibangun dari kebutuhan operasional sekolah nyata.</h2>
            <p class="section-copy">SIMS telah berjalan pada satu sekolah produksi. Ruang ini disiapkan untuk testimoni terverifikasi sebelum publikasi.</p>
        </div>
        <div class="surface-card relative overflow-hidden p-8 sm:p-10">
            <div class="absolute top-0 right-0 size-40 rounded-full bg-sun/15 blur-3xl"></div>
            <i data-lucide="quote" class="size-8 text-tide" aria-hidden="true"></i>
            <p class="mt-6 text-xl font-semibold leading-8 text-ink dark:text-white">Testimoni sekolah akan ditampilkan di sini setelah materi dan persetujuan publikasi diterima.</p>
            <div class="mt-8 flex items-center gap-4 border-t border-slate-200 pt-6 dark:border-slate-800">
                <span class="grid size-11 place-items-center rounded-full bg-ink/7 text-ink dark:bg-white/8 dark:text-white"><i data-lucide="school" class="size-5" aria-hidden="true"></i></span>
                <div><p class="text-sm font-bold text-ink dark:text-white">Placeholder terverifikasi</p><p class="text-xs text-slate-500">Tidak dipublikasikan sebagai klaim pelanggan</p></div>
            </div>
        </div>
    </div>
</section>

<section class="border-y border-slate-200 bg-white py-24 dark:border-slate-800 dark:bg-slate-900/40" x-data="{ open: 1 }">
    <div class="shell grid gap-12 lg:grid-cols-[.72fr_1.28fr]">
        <div>
            <span class="eyebrow">Pertanyaan umum</span>
            <h2 class="section-title mt-5">Sebelum Anda meminta demo.</h2>
        </div>
        <div class="divide-y divide-slate-200 border-y border-slate-200 dark:divide-slate-800 dark:border-slate-800">
            @foreach ([
                ['Apakah SIMS hanya untuk satu jenis sekolah?', 'Fitur akademik dan administrasi dapat diperlihatkan sesuai kebutuhan sekolah saat sesi demo.'],
                ['Apakah tersedia aplikasi Android?', 'Ya. SIMS mendukung aplikasi Android berbasis WebView serta notifikasi push melalui FCM.'],
                ['Bagaimana keamanan akses pengguna?', 'SIMS memakai akses berbasis peran, rate-limit login, CSRF, file sensitif di disk privat, serta header keamanan.'],
                ['Apakah ada pembayaran online otomatis?', 'Belum. Aktivasi dan perpanjangan langganan dilakukan manual oleh superadmin sesuai cakupan fase saat ini.'],
            ] as $index => $faq)
                <div>
                    <button type="button" @click="open = open === {{ $index + 1 }} ? null : {{ $index + 1 }}" :aria-expanded="open === {{ $index + 1 }}" class="flex w-full items-center justify-between gap-4 py-5 text-left font-bold text-ink dark:text-white">
                        <span>{{ $faq[0] }}</span>
                        <i data-lucide="plus" class="size-5 shrink-0 transition" :class="{ 'rotate-45': open === {{ $index + 1 }} }" aria-hidden="true"></i>
                    </button>
                    <div x-cloak x-show="open === {{ $index + 1 }}" x-transition class="pb-5 text-sm leading-6 text-slate-600 dark:text-slate-400">{{ $faq[1] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section id="minta-demo" class="py-24 sm:py-30">
    <div class="shell">
        <div class="grid overflow-hidden rounded-[2rem] bg-ink lg:grid-cols-[.8fr_1.2fr] dark:bg-slate-900">
            <div class="relative p-8 text-white sm:p-12">
                <div class="absolute -bottom-24 -left-24 size-72 rounded-full bg-tide/20 blur-3xl"></div>
                <div class="relative">
                    <span class="eyebrow border-white/15 bg-white/8 text-teal-300">Minta demo</span>
                    <h2 class="mt-5 text-4xl font-bold tracking-[-0.04em]">Lihat SIMS bekerja untuk kebutuhan sekolah Anda.</h2>
                    <p class="mt-5 leading-7 text-slate-300">Ceritakan kondisi sekolah Anda. Tim kami akan menyiapkan sesi pembahasan yang lebih relevan.</p>
                    <div class="mt-8 space-y-4 text-sm text-slate-300">
                        <p class="flex gap-3"><i data-lucide="check-circle-2" class="mt-0.5 size-5 shrink-0 text-tide" aria-hidden="true"></i> Eksplorasi modul sesuai kebutuhan</p>
                        <p class="flex gap-3"><i data-lucide="check-circle-2" class="mt-0.5 size-5 shrink-0 text-tide" aria-hidden="true"></i> Diskusi paket dan durasi</p>
                        <p class="flex gap-3"><i data-lucide="check-circle-2" class="mt-0.5 size-5 shrink-0 text-tide" aria-hidden="true"></i> Tanpa komitmen pembayaran</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-8 sm:p-12 dark:bg-slate-950">
                @include('partials.lead-form', ['source' => 'landing'])
            </div>
        </div>
    </div>
</section>
@endsection
