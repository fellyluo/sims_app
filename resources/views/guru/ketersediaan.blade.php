@extends('layouts.app')
@section('title', 'Ketersediaan Guru')

@section('content')
@php $breadcrumbs = [['label'=>'Data Guru','url'=>route('guru.index')], ['label'=>$guru->nama,'url'=>route('guru.show',$guru->uuid)], ['label'=>'Ketersediaan Waktu','url'=>'#']]; @endphp

<div class="max-w-3xl mx-auto space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('guru.show', $guru->uuid) }}" class="grid place-items-center w-10 h-10 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-primary hover:border-primary transition">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="page-title">Ketersediaan Waktu</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $guru->nama }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('guru.ketersediaan.simpan', $guru->uuid) }}" class="card p-5">
        @csrf
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-slate-600 dark:text-slate-300">Centang jam pelajaran di mana guru <strong>TIDAK BISA</strong> mengajar.</p>
            <button type="submit" class="btn-primary px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i> Simpan
            </button>
        </div>

        @php
            $hari_nama = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat'];
            $unavailable = $ketersediaans->map(function($k) { return $k->hari . '_' . $k->jam_ke; })->toArray();
        @endphp

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50 dark:bg-slate-800 dark:text-slate-400">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-xl">Hari / Jam Ke-</th>
                        @for($j = 1; $j <= 8; $j++)
                            <th class="px-2 py-3 text-center">{{ $j }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach($hari_nama as $h_id => $h_nama)
                    <tr class="border-b dark:border-slate-700">
                        <td class="px-4 py-3 font-medium text-slate-900 dark:text-white">{{ $h_nama }}</td>
                        @for($j = 1; $j <= 8; $j++)
                            @php
                                $val = $h_id . '_' . $j;
                                $isChecked = in_array($val, $unavailable);
                            @endphp
                            <td class="px-2 py-3 text-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="unavailable[]" value="{{ $val }}" class="sr-only peer" {{ $isChecked ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-rose-500"></div>
                                </label>
                            </td>
                        @endfor
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 flex items-center gap-4 text-xs text-slate-500">
            <div class="flex items-center gap-1"><div class="w-3 h-3 rounded-full bg-slate-200 dark:bg-slate-700"></div> Bisa mengajar</div>
            <div class="flex items-center gap-1"><div class="w-3 h-3 rounded-full bg-rose-500"></div> Tidak bisa mengajar</div>
        </div>
    </form>
</div>

@endsection
