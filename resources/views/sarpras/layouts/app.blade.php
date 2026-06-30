{{--
    Layout modul Sarpras — terintegrasi ke shell SIMS.
    Memakai layout utama SIMS (sidebar, topbar, tema, font, Tailwind CDN).

    Slot yang bisa diisi view:
      @section('title')            → judul tab browser (dipakai layouts.app)
      @section('sarpras_title')    → judul besar header (default "Sarana & Prasarana")
      @section('sarpras_subtitle') → subjudul header
      @section('sarpras_actions')  → tombol aksi kanan header
      @section('sarpras_body')     → konten halaman
--}}
@extends('layouts.app')

@push('styles')
<style>
/* ============================================================
   Dark-mode modul Sarpras (override terscope di .sarpras-scope).
   ============================================================ */
.dark .sarpras-scope { color:#cbd5e1; }
.dark .sarpras-scope .bg-white { background-color:#1e293b !important; }
.dark .sarpras-scope .text-gray-900,
.dark .sarpras-scope .text-gray-800,
.dark .sarpras-scope .text-gray-700,
.dark .sarpras-scope .text-slate-900,
.dark .sarpras-scope .text-slate-800,
.dark .sarpras-scope .text-slate-700 { color:#e2e8f0 !important; }
.dark .sarpras-scope .text-gray-600,
.dark .sarpras-scope .text-gray-500 { color:#94a3b8 !important; }
.dark .sarpras-scope .text-gray-400,
.dark .sarpras-scope .text-gray-300 { color:#64748b !important; }
.dark .sarpras-scope .bg-gray-50,
.dark .sarpras-scope .bg-gray-100 { background-color:#334155 !important; }
.dark .sarpras-scope .border,
.dark .sarpras-scope .border-t,
.dark .sarpras-scope .border-b,
.dark .sarpras-scope .border-l,
.dark .sarpras-scope .border-r,
.dark .sarpras-scope .border-gray-100,
.dark .sarpras-scope .border-gray-200,
.dark .sarpras-scope .border-gray-300,
.dark .sarpras-scope .divide-y > :not([hidden]) ~ :not([hidden]) { border-color:#334155 !important; }
.dark .sarpras-scope input:not([type=color]):not([type=file]),
.dark .sarpras-scope select,
.dark .sarpras-scope textarea { background-color:#0f172a !important; color:#e2e8f0 !important; border-color:#334155 !important; }
.dark .sarpras-scope input::placeholder,
.dark .sarpras-scope textarea::placeholder { color:#64748b !important; }
.dark .sarpras-scope .hover\:bg-gray-50:hover,
.dark .sarpras-scope .hover\:bg-gray-100:hover { background-color:#334155 !important; }

/* Tab nav sarpras */
.sarpras-tabs::-webkit-scrollbar { height:4px; }
.sarpras-tabs::-webkit-scrollbar-thumb { background:rgb(203 213 225 / .6); border-radius:9999px; }
.dark .sarpras-tabs::-webkit-scrollbar-thumb { background:rgb(71 85 105 / .6); }
</style>
@endpush

@section('content')
<div class="sarpras-scope space-y-5">

    {{-- Header --}}
    <div class="flex items-end justify-between gap-3 flex-wrap">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100">@yield('sarpras_title', 'Sarana & Prasarana')</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">@yield('sarpras_subtitle', 'Manajemen aset, gedung interaktif, pengadaan barang, peminjaman, perbaikan, dan mutasi barang.')</p>
        </div>
        @hasSection('sarpras_actions')
            <div class="flex items-center gap-2 flex-wrap">@yield('sarpras_actions')</div>
        @endif
    </div>

    {{-- Tab navigasi modul Sarpras --}}
    @php
        $sarprasTabs = [
            ['Dashboard Sarpras', 'layout-dashboard', 'sarpras.dashboard',     ['sarpras.dashboard']],
            ['Denah Interaktif',  'map',              'sarpras.denah.index',   ['sarpras.denah.*','sarpras.ruangan.*']],
            ['Ruangan & Booking', 'building-2',       'sarpras.booking.index', ['sarpras.booking.*']],
            ['Maintenance Lapor', 'triangle-alert',   'sarpras.kerusakan.index',  ['sarpras.kerusakan.*']],
            ['Inventaris Barang', 'package',          'sarpras.aset.index',       ['sarpras.aset.*','sarpras.kategori.*']],
            ['Pengadaan Aset',    'shopping-cart',    'sarpras.pengadaan.index',  ['sarpras.pengadaan.*']],
            ['Peminjaman Aset',   'hand-helping',     'sarpras.peminjaman.index', ['sarpras.peminjaman.*']],
            ['Perbaikan & Teknisi','wrench',          'sarpras.perbaikan.index',  ['sarpras.perbaikan.*','sarpras.teknisi.*','sarpras.jadwal.*']],
            ['Mutasi & Hapus',    'trash-2',          'sarpras.mutasi.index',     ['sarpras.mutasi.*','sarpras.penghapusan.*']],
            ['Supplier',          'truck',            'sarpras.supplier.index',   ['sarpras.supplier.*']],
        ];
    @endphp
    <div class="border-b border-slate-200 dark:border-slate-700">
        <nav class="sarpras-tabs flex gap-1 overflow-x-auto">
            @foreach($sarprasTabs as [$label, $icon, $route, $patterns])
                @php $active = request()->routeIs(...$patterns); @endphp
                <a href="{{ route($route) }}"
                   class="flex items-center gap-2 px-4 py-3 text-sm font-semibold whitespace-nowrap border-b-2 transition
                          {{ $active
                                ? 'border-amber-500 text-amber-600 dark:text-amber-400'
                                : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">
                    <i data-lucide="{{ $icon }}" class="w-[18px] h-[18px]"></i>
                    {{ $label }}
                </a>
            @endforeach
        </nav>
    </div>

    {{-- Konten halaman modul --}}
    @yield('sarpras_body')
</div>
@endsection
