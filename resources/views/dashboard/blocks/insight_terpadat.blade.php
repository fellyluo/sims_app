@php
    $perTingkat = \App\Models\Kelas::withCount('siswa')->get()
        ->groupBy('tingkat')
        ->map(fn ($g) => $g->sum('siswa_count'))
        ->sortDesc();
    $jmlTingkat = $perTingkat->count();
    $padatTingkat = $jmlTingkat > 0 ? $perTingkat->keys()->first() : '—';
    $padatJml     = $jmlTingkat > 0 ? $perTingkat->first() : 0;
@endphp
<div class="card p-5 flex items-start justify-between relative overflow-hidden group hover:border-primary/20 transition-all duration-300 h-full shadow-sm">
    <div class="min-w-0">
        <p class="text-xs text-slate-400 font-semibold mb-1 truncate">Tingkat Terpadat</p>
        <p class="text-2xl font-extrabold text-slate-700 dark:text-slate-100 tracking-tight truncate">Tingkat {{ $padatTingkat }}</p>
        <p class="text-[11px] text-slate-400 mt-0.5 truncate">{{ number_format($padatJml) }} siswa</p>
    </div>
    <span class="grid place-items-center w-9 h-9 rounded-xl bg-primary/10 text-primary group-hover:scale-110 transition duration-300 flex-shrink-0">
        <i data-lucide="trending-up" class="w-4 h-4"></i>
    </span>
</div>
