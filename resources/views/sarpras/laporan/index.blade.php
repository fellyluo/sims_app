@extends('sarpras.layouts.app')
@section('title', 'Laporan Sarpras')

@section('sarpras_body')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
    <h2 class="flex items-center gap-2 text-lg font-bold text-slate-800 dark:text-slate-100">
        <span class="grid place-items-center w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/40 text-indigo-500"><i data-lucide="file-bar-chart" class="w-4 h-4"></i></span>
        Laporan Data Sarpras
    </h2>
    <div class="flex gap-2 flex-wrap">
        @can('sarpras.laporan.export')
            <a href="{{ route('sarpras.laporan.aset.excel') }}" 
               class="inline-flex items-center gap-2 bg-[#eafaf1] text-[#065f46] border border-[#a7f3d0] dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/30 px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all duration-200 shadow-sm hover:bg-[#d1fae5]">
                <i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Ekspor Excel
            </a>
            <a href="{{ route('sarpras.laporan.aset.pdf') }}" target="_blank" 
               class="inline-flex items-center gap-2 bg-[#fff1f2] text-[#9f1239] border border-[#fecdd3] dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/30 px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all duration-200 shadow-sm hover:bg-[#ffe4e6]">
                <i data-lucide="file-text" class="w-4 h-4"></i> Ekspor PDF
            </a>
        @endcan
        <a href="{{ route('sarpras.laporan.aktivitas') }}" 
           class="inline-flex items-center gap-2 bg-[#f8fafc] text-[#1e293b] border border-[#cbd5e1] dark:bg-slate-800/40 dark:text-slate-200 dark:border-slate-700 px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all duration-200 shadow-sm hover:bg-[#f1f5f9]">
            <i data-lucide="history" class="w-4 h-4"></i> Log Aktivitas
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="card p-5 lg:col-span-2">
        <h3 class="font-bold text-slate-800 dark:text-slate-100 mb-4">Rekap Aset per Kondisi</h3>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="py-2">Kondisi</th>
                        <th class="py-2 text-center">Jumlah</th>
                        <th class="py-2 text-right">Nilai Perolehan</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($rekapKondisi as $r)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="py-3 capitalize text-sm font-semibold">
                            <span class="inline-block w-2.5 h-2.5 rounded-full mr-2" 
                                  style="background: {{ ['baik' => '#10b981', 'rusak_ringan' => '#f59e0b', 'rusak_berat' => '#ef4444', 'hilang' => '#94a3b8'][$r->kondisi] ?? '#94a3b8' }}"></span>
                            {{ str_replace('_',' ',$r->kondisi) }}
                        </td>
                        <td class="py-3 text-sm text-center font-medium text-slate-600 dark:text-slate-300">{{ $r->jml }} unit</td>
                        <td class="py-3 text-sm font-bold text-slate-700 dark:text-slate-200 text-right">{{ \App\Sarpras\Support\Rupiah::format($r->nilai) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card p-5 flex flex-col justify-center min-h-[160px]">
        <div class="flex items-center gap-3">
            <span class="grid place-items-center w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 text-emerald-500 flex-shrink-0"><i data-lucide="banknote" class="w-6 h-6"></i></span>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Nilai Total Aset</p>
                <p class="text-2xl sm:text-3xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">{{ $totalNilaiRp }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
