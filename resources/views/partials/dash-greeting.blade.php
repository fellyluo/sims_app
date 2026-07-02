{{-- Kartu sambutan: salam + tombol Tata Letak + kutipan harian, bermotif batik --}}
<div class="card card-batik relative overflow-hidden p-5 sm:p-6 mb-6">
    @include('partials.batik', ['uid' => 'dash'])
    <div class="relative z-10">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h1 class="flex items-start sm:items-center gap-2 text-lg sm:text-xl font-extrabold text-slate-700 dark:text-slate-100">
                    <span class="js-salam-icon inline-flex flex-shrink-0 mt-0.5 sm:mt-0" data-icon-class="w-5 h-5 text-primary flex-shrink-0"><i data-lucide="{{ $salamIcon }}" class="w-5 h-5 text-primary flex-shrink-0"></i></span>
                    <span class="leading-tight sm:leading-normal"><span class="js-salam-text">{{ $salam }}</span>,<br class="sm:hidden"> {{ $nama }} <span class="ml-0.5">👋</span></span>
                </h1>
                <p class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-300 mt-1 flex items-center gap-1.5">
                    <i data-lucide="calendar-days" class="w-3.5 h-3.5 flex-shrink-0"></i>
                    <span class="js-dash-date capitalize font-semibold text-slate-600 dark:text-slate-200">{{ $tanggalHari }}</span>
                    <span class="text-slate-300 dark:text-slate-600">&bull;</span>
                    <i data-lucide="clock" class="w-3.5 h-3.5 flex-shrink-0"></i>
                    <span class="js-dash-clock tabular-nums font-semibold text-slate-600 dark:text-slate-200">--:--:--</span>
                </p>
                @if($access === 'orangtua' && $siswaWidget)
                <p class="text-xs sm:text-sm font-semibold text-primary mt-1.5 flex items-center gap-1.5">
                    <i data-lucide="user-round" class="w-3.5 h-3.5 flex-shrink-0"></i>
                    Memantau {{ $siswaWidget['siswa']->nama }}
                    <span class="text-slate-300 dark:text-slate-600">&bull;</span>
                    Kelas {{ $siswaWidget['siswa']->kelas ? $siswaWidget['siswa']->kelas->tingkat . $siswaWidget['siswa']->kelas->kelas : '-' }}
                </p>
                @endif
            </div>
            <div class="hidden sm:flex items-center gap-2">
                <span x-show="editing" x-cloak class="hidden sm:inline text-xs text-slate-400">Seret kartu untuk menyusun ulang</span>
                <button type="button" x-show="editing" x-cloak @click="reset()"
                        class="btn-accent inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold transition shadow-sm">
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Reset
                </button>
                <button type="button" @click="toggle()"
                        class="btn-accent inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold transition shadow-sm">
                    <i data-lucide="layout-dashboard" class="w-3.5 h-3.5" x-show="!editing"></i>
                    <i data-lucide="check" class="w-3.5 h-3.5" x-show="editing" x-cloak></i>
                    <span x-text="editing ? 'Selesai' : 'Tata Letak'"></span>
                </button>
            </div>
        </div>

        {{-- Kutipan harian — minimalis --}}
        <div class="motiv-card mt-5 pt-4 border-t border-primary/15">
            <p class="motiv-label font-bold uppercase text-primary/70">Quote of the Day</p>
            <p class="mt-2 text-sm sm:text-[15px] font-normal leading-relaxed text-slate-600 dark:text-slate-300">{{ $kataTeks }}</p>
            @if($kataPenulis)
                <p class="mt-1.5 text-xs font-medium text-slate-400 dark:text-slate-500">{{ $kataPenulis }}</p>
            @endif
        </div>
    </div>
</div>
