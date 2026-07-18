<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SIMS — Sistem Informasi Manajemen Sekolah')</title>
    <meta name="description" content="@yield('description', 'SIMS menyatukan pengelolaan akademik, kehadiran, keuangan, sarpras, dan Asisten Guru dalam satu sistem.')">
    <meta name="theme-color" content="#0F1F3D">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="id_ID">
    <meta property="og:site_name" content="SIMS">
    <meta property="og:title" content="@yield('og_title', 'SIMS — Sistem Informasi Manajemen Sekolah')">
    <meta property="og:description" content="@yield('og_description', 'Kelola sekolah lebih terhubung, rapi, dan siap berkembang bersama SIMS.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/og/default.png'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'SIMS — Sistem Informasi Manajemen Sekolah')">
    <meta name="twitter:description" content="@yield('og_description', 'Kelola sekolah lebih terhubung, rapi, dan siap berkembang bersama SIMS.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og/default.png'))">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        (() => {
            const saved = localStorage.getItem('sims-theme');
            const dark = saved === 'dark' || (!saved && matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', dark);
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <a href="#konten-utama" class="sr-only z-[100] rounded bg-white px-4 py-2 text-ink focus:not-sr-only focus:fixed focus:top-4 focus:left-4">
        Lewati ke konten utama
    </a>

    <header x-data="{ mobileOpen: false }" class="sticky top-0 z-50 border-b border-slate-200/70 bg-paper/90 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/88">
        <div class="shell flex h-18 items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="SIMS — Beranda">
                <span class="grid size-10 place-items-center rounded-xl bg-ink text-white shadow-lg shadow-ink/20 dark:bg-tide dark:text-slate-950">
                    <i data-lucide="school" class="size-5" aria-hidden="true"></i>
                </span>
                <span>
                    <span class="block text-lg font-bold leading-none tracking-[-0.03em] text-ink dark:text-white">SIMS</span>
                    <span class="mt-1 block text-[10px] font-semibold tracking-[0.12em] text-slate-500 uppercase">Sekolah terhubung</span>
                </span>
            </a>

            <nav class="hidden items-center gap-1 md:flex" aria-label="Navigasi utama">
                @foreach ([
                    ['route' => 'home', 'label' => 'Beranda'],
                    ['route' => 'features', 'label' => 'Fitur'],
                    ['route' => 'pricing', 'label' => 'Harga'],
                    ['route' => 'contact', 'label' => 'Kontak'],
                ] as $item)
                    <a href="{{ route($item['route']) }}"
                       @class([
                           'rounded-lg px-4 py-2 text-sm font-semibold transition',
                           'bg-ink/7 text-ink dark:bg-white/8 dark:text-white' => request()->routeIs($item['route']),
                           'text-slate-600 hover:bg-slate-100 hover:text-ink dark:text-slate-400 dark:hover:bg-slate-900 dark:hover:text-white' => ! request()->routeIs($item['route']),
                       ])>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-2">
                <button type="button"
                        x-data
                        @click="
                            document.documentElement.classList.toggle('dark');
                            localStorage.setItem('sims-theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
                        "
                        class="grid size-10 place-items-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-tide hover:text-tide-dark dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300"
                        aria-label="Ubah tema terang atau gelap">
                    <i data-lucide="sun" class="size-4 dark:hidden" aria-hidden="true"></i>
                    <i data-lucide="moon" class="hidden size-4 dark:block" aria-hidden="true"></i>
                </button>
                <a href="{{ config('marketing.app_url').'/login' }}" class="hidden text-sm font-bold text-ink hover:text-tide-dark lg:block dark:text-white">Masuk</a>
                <a href="{{ route('contact') }}" class="btn-primary hidden sm:inline-flex">Minta demo</a>
                <button type="button" @click="mobileOpen = ! mobileOpen" :aria-expanded="mobileOpen" aria-controls="menu-mobile" class="grid size-10 place-items-center rounded-xl border border-slate-200 md:hidden dark:border-slate-800" aria-label="Buka menu">
                    <i data-lucide="menu" class="size-5" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <nav id="menu-mobile" x-cloak x-show="mobileOpen" x-transition class="shell border-t border-slate-200 py-4 md:hidden dark:border-slate-800" aria-label="Navigasi mobile">
            <div class="grid gap-1">
                <a href="{{ route('home') }}" class="rounded-lg px-3 py-3 font-semibold">Beranda</a>
                <a href="{{ route('features') }}" class="rounded-lg px-3 py-3 font-semibold">Fitur</a>
                <a href="{{ route('pricing') }}" class="rounded-lg px-3 py-3 font-semibold">Harga</a>
                <a href="{{ route('contact') }}" class="rounded-lg px-3 py-3 font-semibold">Kontak</a>
                <a href="{{ config('marketing.app_url').'/login' }}" class="mt-2 btn-secondary">Masuk ke aplikasi</a>
            </div>
        </nav>
    </header>

    @if (session('success'))
        <div class="shell pt-5">
            <div role="status" class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900 dark:border-emerald-900 dark:bg-emerald-950 dark:text-emerald-200">
                <i data-lucide="circle-check" class="mt-0.5 size-5 shrink-0" aria-hidden="true"></i>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <main id="konten-utama">
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-ink text-slate-300 dark:border-slate-800 dark:bg-slate-950">
        <div class="shell grid gap-10 py-14 md:grid-cols-[1.4fr_1fr_1fr]">
            <div>
                <div class="flex items-center gap-3 text-white">
                    <span class="grid size-10 place-items-center rounded-xl bg-tide text-slate-950">
                        <i data-lucide="school" class="size-5" aria-hidden="true"></i>
                    </span>
                    <span class="text-xl font-bold">SIMS</span>
                </div>
                <p class="mt-4 max-w-sm text-sm leading-6 text-slate-400">Sistem Informasi Manajemen Sekolah yang menyatukan pekerjaan sekolah dalam satu alur yang terhubung.</p>
                <div class="mt-5 flex flex-wrap gap-x-4 gap-y-2 text-xs font-semibold text-slate-400">
                    <span>Satu alur kerja</span><span aria-hidden="true">•</span><span>Akses per peran</span><span aria-hidden="true">•</span><span>Asisten Guru</span>
                </div>
            </div>
            <div>
                <p class="font-bold text-white">Jelajahi</p>
                <div class="mt-4 grid gap-3 text-sm">
                    <a href="{{ route('features') }}" class="hover:text-teal-300">Seluruh fitur</a>
                    <a href="{{ route('pricing') }}" class="hover:text-teal-300">Paket & harga</a>
                    <a href="{{ route('contact') }}" class="hover:text-teal-300">Minta demo</a>
                    <a href="{{ route('privacy') }}" class="hover:text-teal-300">Privasi</a>
                </div>
            </div>
            <div>
                <p class="font-bold text-white">Hubungi</p>
                <div class="mt-4 grid gap-3 text-sm">
                    <a href="mailto:{{ config('marketing.contact.email') }}" class="hover:text-teal-300">{{ config('marketing.contact.email') }}</a>
                    <a href="https://wa.me/{{ \App\Support\Marketing::whatsappDigits() }}" class="hover:text-teal-300">WhatsApp {{ \App\Support\Marketing::whatsappDisplay() }}</a>
                    <span class="text-slate-400">{{ config('marketing.contact.address') }}</span>
                </div>
            </div>
        </div>
        <div class="border-t border-white/10">
            <div class="shell flex flex-col gap-2 py-5 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
                <p>© {{ now()->year }} SIMS. Semua hak dilindungi.</p>
                <p>
                    <a href="{{ route('privacy') }}" class="hover:text-teal-300">Privasi</a>
                    <span class="mx-2" aria-hidden="true">·</span>
                    Sistem sekolah yang tumbuh bersama kebutuhan Anda.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
