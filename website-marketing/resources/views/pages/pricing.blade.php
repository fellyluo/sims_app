@extends('layouts.marketing')

@section('title', 'Harga SIMS — Paket Dasar, Pro, dan Enterprise')
@section('description', 'Bandingkan paket SIMS Dasar, Pro, dan Enterprise untuk durasi 3, 6, atau 12 bulan, dengan rincian PPN yang transparan.')
@section('og_title', 'Paket & Harga SIMS')
@section('og_description', 'Pilih cakupan fitur dan durasi langganan SIMS yang sesuai dengan kebutuhan sekolah.')

@section('content')
@php
    $defaultDuration = 12;
    $ppnRate = (int) config('marketing.ppn_rate');

    $plans = [
        'dasar' => [
            'name' => 'Dasar',
            'description' => 'Fondasi akademik, kehadiran, dan informasi sekolah.',
            'items' => ['Data master sekolah', 'Absensi & presensi', 'Penilaian + rapor PDF', 'Pengumuman, kalender, agenda, jadwal'],
        ],
        'pro' => [
            'name' => 'Pro',
            'description' => 'Operasional sekolah lebih lengkap dan terhubung.',
            'items' => ['Semua fitur Dasar', 'Ruang kelas digital + forum', 'Keuangan / SPP + kedisiplinan', 'Aplikasi Android + push FCM'],
        ],
        'enterprise' => [
            'name' => 'Enterprise',
            'description' => 'Cakupan menyeluruh, AI, dan dukungan prioritas.',
            'items' => ['Semua fitur Pro', 'Sarpras lengkap', 'Asisten Guru (Gemini)', 'WebAuthn, prioritas, dan kustomisasi'],
        ],
    ];

    $matrix = [
        ['Data master (siswa/guru/kelas/mapel)', true, true, true],
        ['Absensi & Presensi (wajah, QR+GPS, kiosk)', true, true, true],
        ['Penilaian Kurikulum Merdeka + Rapor PDF', true, true, true],
        ['Pengumuman, Kalender, Agenda, Jadwal', true, true, true],
        ['Ruang Kelas digital (materi/tugas + anti-cheat)', false, true, true],
        ['Forum Diskusi Kelas', false, true, true],
        ['Keuangan / SPP (bendahara, VA, verifikasi)', false, true, true],
        ['Kedisiplinan (Poin / P3)', false, true, true],
        ['Kartu Pelajar, Chatbot Helpdesk', false, true, true],
        ['Notifikasi Push Android (FCM) + App Android', false, true, true],
        ['Sarpras lengkap', false, false, true],
        ['Asisten Guru (Gemini)', false, false, true],
        ['Login WebAuthn (sidik jari / Face ID)', false, false, true],
        ['Dukungan prioritas & kustomisasi', false, false, true],
    ];
@endphp

<section
    x-data="{
        duration: 12,
        taxRate: {{ config('marketing.ppn_rate') }},
        prices: @js(config('marketing.prices')),
        value(tier) {
            return this.prices[tier]?.[this.duration] ?? null;
        },
        money(value) {
            if (value === null) return 'Rp —';
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value);
        },
        tax(tier) {
            const price = this.value(tier);
            return price === null ? null : Math.round(price * this.taxRate / 100);
        },
        total(tier) {
            const price = this.value(tier);
            return price === null ? null : price + this.tax(tier);
        }
    }"
    class="relative overflow-hidden py-20 sm:py-28"
>
    <div class="grid-fade absolute inset-x-0 top-0 -z-10 h-[650px]"></div>
    <div class="shell">
        <div class="mx-auto max-w-3xl text-center">
            <span class="eyebrow">Paket yang mengikuti kebutuhan</span>
            <h1 class="mt-6 text-5xl font-bold tracking-[-0.055em] text-ink sm:text-6xl dark:text-white">Pilih fitur. Pilih durasi. <span class="text-tide-dark dark:text-teal-300">Tetap transparan.</span></h1>
            <p class="mt-6 text-lg leading-8 text-slate-600 dark:text-slate-400">Paket SIMS tersedia untuk 3, 6, atau 12 bulan. Seluruh harga ditampilkan dalam Rupiah dengan rincian PPN.</p>
        </div>

        <div class="mx-auto mt-10 flex w-fit rounded-2xl border border-slate-200 bg-white p-1.5 shadow-sm dark:border-slate-700 dark:bg-slate-900" role="group" aria-label="Pilih durasi langganan">
            @foreach ([3, 6, 12] as $month)
                <button
                    type="button"
                    @click="duration = {{ $month }}"
                    :aria-pressed="duration === {{ $month }}"
                    :class="duration === {{ $month }} ? 'bg-ink text-white shadow-sm dark:bg-tide dark:text-slate-950' : 'text-slate-600 hover:text-ink dark:text-slate-400 dark:hover:text-white'"
                    class="relative rounded-xl px-4 py-2.5 text-sm font-bold transition sm:px-6"
                >
                    {{ $month }} bulan
                    @if ($month === 12)
                        <span class="absolute -top-3 -right-2 rounded-full bg-sun px-2 py-0.5 text-[9px] font-bold text-amber-950 uppercase">Paling hemat</span>
                    @endif
                </button>
            @endforeach
        </div>

        <div class="mt-6 flex items-center justify-center gap-2 text-xs text-slate-500 dark:text-slate-400">
            <i data-lucide="info" class="size-4" aria-hidden="true"></i>
            Harga di bawah belum termasuk PPN {{ config('marketing.ppn_rate') }}%. Paket Enterprise dibahas sesuai skala sekolah.
        </div>

        <div class="mt-12 grid gap-6 lg:grid-cols-3">
            @foreach ($plans as $key => $plan)
                <article @class([
                    'relative flex flex-col rounded-3xl p-7 sm:p-8',
                    'bg-ink text-white shadow-2xl shadow-ink/20 lg:-translate-y-3' => $key === 'pro',
                    'surface-card' => $key !== 'pro',
                ])>
                    @if ($key === 'pro')
                        <span class="absolute top-0 right-7 -translate-y-1/2 rounded-full bg-tide px-3 py-1 text-xs font-bold text-slate-950">Pilihan seimbang</span>
                    @endif

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[.14em] {{ $key === 'pro' ? 'text-teal-300' : 'text-tide-dark dark:text-teal-300' }}">Paket</p>
                        <h2 class="mt-2 text-3xl font-bold {{ $key === 'pro' ? 'text-white' : 'text-ink dark:text-white' }}">{{ $plan['name'] }}</h2>
                        <p class="mt-3 min-h-12 text-sm leading-6 {{ $key === 'pro' ? 'text-slate-300' : 'text-slate-600 dark:text-slate-400' }}">{{ $plan['description'] }}</p>
                    </div>

                    @php
                        $basePrice = config('marketing.prices.'.$key.'.'.$defaultDuration);
                        $basePrice = is_int($basePrice) ? $basePrice : null;
                        $taxPrice = \App\Support\Marketing::taxAmount($basePrice, $ppnRate);
                        $totalPrice = \App\Support\Marketing::totalWithTax($basePrice, $ppnRate);
                    @endphp
                    <div class="my-7 rounded-2xl {{ $key === 'pro' ? 'bg-white/7' : 'bg-slate-50 dark:bg-slate-950' }} p-5">
                        <div class="flex items-end justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider {{ $key === 'pro' ? 'text-slate-400' : 'text-slate-500' }}">
                                    {{ $key === 'enterprise' ? 'Mulai dari · ' : 'Harga dasar · ' }}<span x-text="duration">{{ $defaultDuration }}</span> bulan
                                </p>
                                <p class="mt-2 text-3xl font-bold {{ $key === 'pro' ? 'text-white' : 'text-ink dark:text-white' }}" x-text="money(value('{{ $key }}'))">{{ \App\Support\Marketing::money($basePrice) }}</p>
                            </div>
                            @if ($key === 'enterprise')
                                <span class="rounded-full border px-2 py-1 text-[10px] font-bold border-slate-200 text-slate-500 dark:border-slate-700">Kustom</span>
                            @endif
                        </div>
                        <dl class="mt-5 grid gap-2 border-t pt-4 text-sm {{ $key === 'pro' ? 'border-white/10' : 'border-slate-200 dark:border-slate-800' }}">
                            <div class="flex justify-between gap-4"><dt class="{{ $key === 'pro' ? 'text-slate-400' : 'text-slate-500' }}">PPN <span x-text="taxRate">{{ $ppnRate }}</span>%</dt><dd class="font-semibold" x-text="money(tax('{{ $key }}'))">{{ \App\Support\Marketing::money($taxPrice) }}</dd></div>
                            <div class="flex justify-between gap-4"><dt class="font-bold">Total</dt><dd class="font-bold" x-text="money(total('{{ $key }}'))">{{ \App\Support\Marketing::money($totalPrice) }}</dd></div>
                        </dl>
                        @if ($key === 'enterprise')
                            <p class="mt-3 text-xs {{ $key === 'pro' ? 'text-slate-400' : 'text-slate-500' }}">Termasuk Asisten Guru & sarpras. Final menyesuaikan jumlah siswa dan onboarding.</p>
                        @endif
                    </div>

                    <ul class="grid flex-1 gap-3 text-sm">
                        @foreach ($plan['items'] as $item)
                            <li class="flex gap-3"><span class="grid size-5 shrink-0 place-items-center rounded-full bg-tide text-slate-950"><i data-lucide="check" class="size-3" aria-hidden="true"></i></span><span class="{{ $key === 'pro' ? 'text-slate-200' : 'text-slate-700 dark:text-slate-300' }}">{{ $item }}</span></li>
                        @endforeach
                    </ul>

                    <a href="{{ route('contact', ['tier' => $key]) }}" class="mt-8 {{ $key === 'pro' ? 'inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-tide px-5 py-3 text-sm font-bold text-slate-950 transition hover:bg-teal-300' : 'btn-secondary' }}">
                        {{ $key === 'enterprise' ? 'Hubungi kami' : 'Minta demo paket '.$plan['name'] }}
                        <i data-lucide="arrow-right" class="size-4" aria-hidden="true"></i>
                    </a>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="border-y border-slate-200 bg-mist py-20 sm:py-24 dark:border-slate-800 dark:bg-slate-900/45">
    <div class="shell">
        <div>
            <span class="eyebrow">Perbandingan lengkap</span>
            <h2 class="section-title mt-5">Fitur di setiap paket.</h2>
            <p class="section-copy">Matriks awal ini mengikuti keputusan PRD dan dapat disesuaikan sebelum penawaran final.</p>
        </div>
        <div class="mt-10 overflow-x-auto rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <table class="w-full min-w-[760px] border-collapse text-left">
                <caption class="sr-only">Perbandingan fitur paket SIMS Dasar, Pro, dan Enterprise</caption>
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800">
                        <th scope="col" class="p-5 text-sm font-bold text-ink dark:text-white">Modul / Fitur</th>
                        @foreach (['Dasar', 'Pro', 'Enterprise'] as $name)
                            <th scope="col" class="w-36 p-5 text-center text-sm font-bold text-ink dark:text-white">{{ $name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach ($matrix as $row)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/70">
                            <th scope="row" class="p-4 text-sm font-medium text-slate-700 dark:text-slate-300">{{ $row[0] }}</th>
                            @foreach (array_slice($row, 1) as $available)
                                <td class="p-4 text-center">
                                    @if ($available)
                                        <span class="inline-grid size-7 place-items-center rounded-full bg-teal-50 text-teal-700 dark:bg-teal-950 dark:text-teal-300"><i data-lucide="check" class="size-4" aria-label="Tersedia"></i></span>
                                    @else
                                        <span class="text-slate-300 dark:text-slate-700" aria-label="Tidak tersedia">—</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="py-20 sm:py-24" x-data="{ open: 0 }">
    <div class="shell grid gap-12 lg:grid-cols-[.72fr_1.28fr]">
        <div><span class="eyebrow">FAQ harga</span><h2 class="section-title mt-5">Hal yang perlu diketahui.</h2></div>
        <div class="divide-y divide-slate-200 border-y border-slate-200 dark:divide-slate-800 dark:border-slate-800">
            @foreach ([
                ['Apakah harga sudah termasuk PPN?', 'Belum. Kartu paket menampilkan harga dasar, lalu PPN '.config('marketing.ppn_rate').'% dan total secara terpisah.'],
                ['Apa keuntungan memilih 12 bulan?', 'Durasi lebih panjang menurunkan biaya setara per bulan. Contoh paket Pro: 3 bulan ≈ Rp650rb/bulan, 12 bulan ≈ Rp500rb/bulan (belum PPN).'],
                ['Apakah harga di halaman ini final?', 'Ini acuan paket yang dipublikasikan. Penawaran final dapat menyesuaikan skala sekolah, onboarding, dan kebutuhan kustom — terutama Enterprise.'],
                ['Apakah langganan diperpanjang otomatis?', 'Tidak. Aktivasi dan perpanjangan dilakukan manual oleh superadmin; pembayaran online dan auto-renew berada di luar cakupan saat ini.'],
                ['Bagaimana harga paket Enterprise ditentukan?', 'Acuan “mulai dari” sudah ditampilkan. Final dibahas bersama berdasarkan sarpras, Asisten Guru, WebAuthn, dukungan prioritas, dan kustomisasi.'],
            ] as $index => $faq)
                <div>
                    <button type="button" @click="open = open === {{ $index }} ? null : {{ $index }}" :aria-expanded="open === {{ $index }}" class="flex w-full items-center justify-between gap-4 py-5 text-left font-bold text-ink dark:text-white">
                        <span>{{ $faq[0] }}</span><i data-lucide="plus" class="size-5 shrink-0 transition" :class="{ 'rotate-45': open === {{ $index }} }" aria-hidden="true"></i>
                    </button>
                    <div x-cloak x-show="open === {{ $index }}" x-transition class="pb-5 text-sm leading-6 text-slate-600 dark:text-slate-400">{{ $faq[1] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="pb-24">
    <div class="shell">
        <div class="rounded-[2rem] bg-ink p-8 text-white sm:flex sm:items-center sm:justify-between sm:p-12 dark:bg-slate-900">
            <div><p class="text-sm font-bold uppercase tracking-[.14em] text-teal-300">Masih membandingkan?</p><h2 class="mt-3 text-3xl font-bold tracking-[-0.03em]">Mari petakan paket dari kebutuhan sekolah Anda.</h2></div>
            <a href="{{ route('contact') }}" class="mt-7 inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-tide px-5 py-3 text-sm font-bold text-slate-950 transition hover:bg-teal-300 sm:mt-0 sm:shrink-0">Diskusikan kebutuhan <i data-lucide="arrow-up-right" class="size-4" aria-hidden="true"></i></a>
        </div>
    </div>
</section>
@endsection
