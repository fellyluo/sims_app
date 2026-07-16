@extends('layouts.app')
@section('title', 'Jagat Misi — Katalog')

@push('styles')
<link rel="stylesheet" href="{{ asset('jagat-misi/styles.css') }}">
@endpush

@section('content')
<div class="space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-primary mb-1">Permainan Edukasi</p>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Jagat Misi</h1>
            <p class="text-sm text-slate-500 mt-1">Katalog misi edukatif dengan mekanik nalar interaktif.</p>
        </div>
        <a href="{{ route('jagat-misi.progress') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary text-white text-sm font-bold">
            <i data-lucide="trophy" class="w-4 h-4"></i> Progres Saya
        </a>
        @if(in_array(auth()->user()->access, ['guru','admin','superadmin']))
        <a href="{{ route('jagat-misi.builder.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border text-sm font-bold">Builder</a>
        <a href="{{ route('jagat-misi.analytics') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border text-sm font-bold">Analitik</a>
        <a href="{{ route('jagat-misi.debrief.teacher') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border text-sm font-bold">Debrief Guru</a>
        @endif
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($missions as $mission)
        @php
            $playRoute = str_contains($mission->mechanic_type, 'recall') || str_contains($mission->mechanic_type, 'quiz')
                ? route('jagat-misi.player', $mission)
                : route('jagat-misi.play', $mission);
        @endphp
        <a href="{{ $playRoute }}" class="block rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-5 hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-md bg-primary/10 text-primary">{{ $mission->subject }}</span>
                <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-500">{{ $mission->grade_level }}</span>
            </div>
            <h3 class="font-black text-lg text-slate-800 dark:text-slate-100">{{ $mission->title }}</h3>
            <p class="text-sm text-slate-500 mt-2 line-clamp-2">{{ $mission->summary }}</p>
            <p class="text-xs text-slate-400 mt-3 flex items-center gap-3">
                <span>{{ $mission->duration_minutes }} menit</span>
                <span>Skor maks {{ $mission->max_score }}</span>
            </p>
        </a>
        @empty
        <div class="col-span-full rounded-2xl border border-dashed border-slate-300 dark:border-slate-600 p-8 text-center text-slate-500">
            Belum ada misi terbit. Jalankan seeder <code class="text-xs">JagatMisiSeeder</code>.
        </div>
        @endforelse
    </div>
</div>
@endsection
