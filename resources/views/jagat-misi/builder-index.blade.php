@extends('layouts.app')
@section('title', 'Builder Misi — Arena Belajar')

@push('styles')
@include('arena-belajar.partials.game-styles')
@endpush

@section('content')
<div class="space-y-5 arena-stage">
    <a href="{{ route('jagat-misi.index') }}" class="text-xs text-slate-500 hover:text-primary inline-flex items-center gap-1">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Arena Belajar
    </a>
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-3">
        <div>
            <p class="arena-eyebrow" style="color:var(--arena-teal)">Arena Belajar</p>
            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100">Builder Misi</h1>
            <p class="text-sm text-slate-500 mt-1">Susun misi per jenjang: SD · SMP · SMA/SMK.</p>
        </div>
        <a href="{{ route('jagat-misi.builder.create') }}" class="arena-cta inline-flex">
            <i data-lucide="plus" class="w-4 h-4"></i> Buat Misi
        </a>
    </div>

    @if(session('success'))
    <div class="rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-700 p-3 text-sm text-emerald-700 dark:text-emerald-300 font-semibold">{{ session('success') }}</div>
    @endif

    <div class="flex flex-wrap gap-2 text-xs font-bold">
        <span class="arena-pill arena-pill-sd">SD</span>
        <span class="arena-pill arena-pill-smp">SMP</span>
        <span class="arena-pill arena-pill-sma">SMA/SMK</span>
        <span class="arena-pill arena-pill-umum">Umum</span>
        <span class="text-slate-400 self-center font-medium">← badge jenjang pada setiap misi</span>
    </div>

    <div class="grid gap-3">
        @forelse($missions as $m)
        @php
            $jenjangKey = $m->jenjangKey();
            $jenjangLabel = $m->jenjangLabel();
        @endphp
        <a href="{{ route('jagat-misi.builder.edit', $m) }}"
           class="rounded-2xl border border-slate-200 dark:border-slate-700 p-4 bg-white dark:bg-slate-900 hover:shadow-md transition flex flex-col sm:flex-row sm:items-center gap-3">
            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-1.5 mb-1.5">
                    <span class="arena-pill arena-pill-jenjang arena-pill-{{ $jenjangKey }}">{{ $jenjangLabel }}</span>
                    <span class="arena-pill">{{ $m->status }}</span>
                    @if($m->isTren())
                    <span class="arena-pill arena-pill-tren">Tren</span>
                    @endif
                </div>
                <p class="font-bold text-slate-800 dark:text-slate-100">{{ $m->title }}</p>
                <p class="text-xs text-slate-500 mt-0.5">
                    {{ $m->subject }}
                    @if($m->grade_level)
                    · {{ $m->grade_level }}
                    @endif
                    · {{ $m->duration_minutes }} menit
                </p>
            </div>
            <span class="text-xs font-bold shrink-0" style="color:var(--arena-teal)">Edit →</span>
        </a>
        @empty
        <div class="rounded-2xl border border-dashed border-slate-200 dark:border-slate-700 p-10 text-center">
            <p class="font-bold text-slate-700 dark:text-slate-200">Belum ada misi</p>
            <p class="text-sm text-slate-500 mt-1">Buat misi pertama dan tentukan jenjang pendidikannya.</p>
            <a href="{{ route('jagat-misi.builder.create') }}" class="arena-cta mt-4 inline-flex">Buat Misi</a>
        </div>
        @endforelse
    </div>
</div>
@endsection
