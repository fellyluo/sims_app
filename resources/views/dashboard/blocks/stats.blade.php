{{-- ===== Top stat strip ===== --}}
<div class="card p-1.5">
    <div class="grid grid-cols-2 lg:grid-cols-4 divide-x divide-y lg:divide-y-0 divide-[#f4efe8] dark:divide-slate-700">
        @foreach([
            ['Total Siswa', $totalSiswa, 'Terdaftar'],
            ['Total Guru', $totalGuru, 'Aktif mengajar'],
            ['Total Kelas', $totalKelas, 'Rombongan belajar'],
        ] as [$label, $val, $sub])
        <div class="px-5 py-4">
            <p class="text-xs text-slate-400 mb-1">{{ $label }}</p>
            <p class="text-2xl font-extrabold text-slate-700 dark:text-slate-100">{{ number_format($val) }}</p>
            <p class="text-[11px] text-slate-400 mt-0.5">{{ $sub }}</p>
        </div>
        @endforeach
        {{-- last cell with mini bar chart --}}
        <div class="px-5 py-4 flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-400 mb-1">Mata Pelajaran</p>
                <p class="text-2xl font-extrabold text-slate-700 dark:text-slate-100">{{ number_format($totalMapel) }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Kurikulum</p>
            </div>
            <svg width="74" height="44" viewBox="0 0 74 44" class="flex-shrink-0">
                @foreach([16,24,18,30,40,26,20] as $i => $h)
                <rect x="{{ $i*10 }}" y="{{ 44-$h }}" width="6" height="{{ $h }}" rx="3"
                      fill="{{ $i==4 ? 'var(--cp)' : 'color-mix(in srgb, var(--cp) 22%, white)' }}"/>
                @endforeach
            </svg>
        </div>
    </div>
</div>
