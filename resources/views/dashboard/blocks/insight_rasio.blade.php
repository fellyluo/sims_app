@php
    $totalSiswa = $totalSiswa ?? \App\Models\Siswa::count();
    $totalGuru = $totalGuru ?? \App\Models\Guru::count();
    $rasioGuru = $totalGuru > 0 ? round($totalSiswa / $totalGuru) : 0;
@endphp
<div class="card p-5 flex items-start justify-between relative overflow-hidden group hover:border-primary/20 transition-all duration-300 h-full shadow-sm">
    <div class="min-w-0">
        <p class="text-xs text-slate-400 font-semibold mb-1 truncate">Rasio Guru : Siswa</p>
        <p class="text-2xl font-extrabold text-slate-700 dark:text-slate-100 tracking-tight truncate">1 : {{ $rasioGuru }}</p>
        <p class="text-[11px] text-slate-400 mt-0.5 truncate">Beban per guru</p>
    </div>
    <span class="grid place-items-center w-9 h-9 rounded-xl bg-primary/10 text-primary group-hover:scale-110 transition duration-300 flex-shrink-0">
        <i data-lucide="users-round" class="w-4 h-4"></i>
    </span>
</div>
