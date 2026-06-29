{{-- ===== Ringkasan Sarpras (terintegrasi) ===== --}}
@php
    $spTotalAset    = \App\Sarpras\Models\Aset::count();
    $spKerusakan    = \App\Sarpras\Models\LaporanKerusakan::whereIn('status', ['dilaporkan','diterima'])->count();
    $spPeminjaman   = \App\Sarpras\Models\Peminjaman::whereIn('status', ['disetujui','dipinjam','terlambat'])->count();
    $spPengadaan    = \App\Sarpras\Models\Pengadaan::where('status','diajukan')->count();
    $spCards = [
        ['Total Aset',        $spTotalAset,  'package',         'text-slate-700', route('sarpras.aset.index')],
        ['Kerusakan Terbuka', $spKerusakan,  'wrench',          'text-red-600',   route('sarpras.kerusakan.index')],
        ['Peminjaman Aktif',  $spPeminjaman, 'clipboard-check', 'text-amber-600', route('sarpras.peminjaman.index')],
        ['Pengadaan Pending', $spPengadaan,  'shopping-cart',   'text-blue-600',  route('sarpras.pengadaan.index')],
    ];
@endphp
<div>
    <div class="flex items-center justify-between mb-3 px-1">
        <h2 class="font-bold text-slate-700 dark:text-slate-200">Sarana &amp; Prasarana</h2>
        <a href="{{ route('sarpras.dashboard') }}" class="text-xs font-semibold text-primary hover:underline">Dashboard Sarpras &rarr;</a>
    </div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($spCards as [$label, $val, $icon, $color, $href])
        <a href="{{ $href }}" class="card card-hover p-4 flex items-center justify-between group">
            <div>
                <p class="text-xs text-slate-400 mb-1">{{ $label }}</p>
                <p class="text-2xl font-extrabold {{ $color }} dark:text-slate-100">{{ number_format($val) }}</p>
            </div>
            <span class="grid place-items-center w-9 h-9 rounded-xl bg-primary/10 text-primary group-hover:scale-110 transition">
                <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
            </span>
        </a>
        @endforeach
    </div>
</div>
