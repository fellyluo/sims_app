@extends('layouts.marketing')

@section('title', 'Kontak & Demo SIMS')
@section('description', 'Hubungi tim SIMS dan jadwalkan demo untuk membahas kebutuhan pengelolaan sekolah Anda.')
@section('og_title', 'Minta Demo SIMS')
@section('og_description', 'Ceritakan kebutuhan sekolah Anda dan jelajahi modul SIMS yang paling relevan.')

@section('content')
<section class="relative overflow-hidden py-20 sm:py-28">
    <div class="grid-fade absolute inset-0 -z-10"></div>
    <div class="shell grid gap-12 lg:grid-cols-[.72fr_1.28fr]">
        <div>
            <span class="eyebrow">Hubungi tim SIMS</span>
            <h1 class="mt-6 text-5xl font-bold tracking-[-0.055em] text-ink sm:text-6xl dark:text-white">Mari bahas kebutuhan sekolah Anda.</h1>
            <p class="mt-6 text-lg leading-8 text-slate-600 dark:text-slate-400">Isi form untuk meminta demo atau mengajukan pertanyaan. Tim kami akan menghubungi Anda melalui email atau WhatsApp.</p>

            <div class="mt-10 grid gap-4">
                <a href="mailto:{{ config('marketing.contact.email') }}" class="surface-card flex items-center gap-4 p-5 transition hover:border-tide/40">
                    <span class="grid size-11 place-items-center rounded-xl bg-tide/10 text-tide-dark dark:text-teal-300"><i data-lucide="mail" class="size-5" aria-hidden="true"></i></span>
                    <div><p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Email</p><p class="mt-1 font-bold text-ink dark:text-white">{{ config('marketing.contact.email') }}</p></div>
                </a>
                <a href="https://wa.me/{{ \App\Support\Marketing::whatsappDigits() }}" class="surface-card flex items-center gap-4 p-5 transition hover:border-tide/40">
                    <span class="grid size-11 place-items-center rounded-xl bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300"><i data-lucide="message-circle" class="size-5" aria-hidden="true"></i></span>
                    <div><p class="text-xs font-semibold uppercase tracking-wider text-slate-400">WhatsApp</p><p class="mt-1 font-bold text-ink dark:text-white">{{ \App\Support\Marketing::whatsappDisplay() }}</p></div>
                </a>
                <div class="surface-card flex items-center gap-4 p-5">
                    <span class="grid size-11 place-items-center rounded-xl bg-amber-50 text-amber-700 dark:bg-amber-950 dark:text-amber-300"><i data-lucide="map-pin" class="size-5" aria-hidden="true"></i></span>
                    <div><p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Alamat</p><p class="mt-1 font-bold text-ink dark:text-white">{{ config('marketing.contact.address') }}</p></div>
                </div>
            </div>
        </div>

        <div class="surface-card p-7 sm:p-10">
            <div class="mb-8">
                <p class="text-sm font-bold uppercase tracking-[.14em] text-tide-dark dark:text-teal-300">Form permintaan demo</p>
                <h2 class="mt-2 text-2xl font-bold text-ink dark:text-white">Ceritakan kondisi sekolah Anda</h2>
            </div>
            @include('partials.lead-form', ['source' => 'kontak'])
        </div>
    </div>
</section>

<section class="border-t border-slate-200 bg-mist py-16 dark:border-slate-800 dark:bg-slate-900/45">
    <div class="shell grid gap-6 sm:grid-cols-3">
        @foreach ([
            ['1', 'Kirim kebutuhan', 'Isi informasi sekolah dan modul yang ingin dibahas.'],
            ['2', 'Tim menghubungi', 'Kami menindaklanjuti melalui email atau WhatsApp.'],
            ['3', 'Jelajahi SIMS', 'Sesi demo diarahkan ke alur yang relevan bagi sekolah Anda.'],
        ] as $step)
            <div class="flex gap-4"><span class="grid size-9 shrink-0 place-items-center rounded-full bg-ink text-sm font-bold text-white dark:bg-tide dark:text-slate-950">{{ $step[0] }}</span><div><h3 class="font-bold text-ink dark:text-white">{{ $step[1] }}</h3><p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">{{ $step[2] }}</p></div></div>
        @endforeach
    </div>
</section>
@endsection
