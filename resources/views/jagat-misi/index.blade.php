@extends('layouts.app')
@section('title', 'Arena Belajar — Katalog Misi')

@push('styles')
@include('arena-belajar.partials.game-styles')
@endpush

@php
    $artPairs = [
        ['#00a99d', '#12345b'],
        ['#f5a524', '#e85d75'],
        ['#5ba85b', '#12345b'],
        ['#e85d75', '#00a99d'],
        ['#12345b', '#00a99d'],
        ['#f5a524', '#12345b'],
    ];
    $mechanicLabels = [
        'nalar_bundle' => 'Nalar',
        'recall_quiz_bundle' => 'Kuis recall',
        'recall_quiz' => 'Kuis recall',
        'interactive_narrative' => 'Narasi',
        'strategic_decision' => 'Keputusan',
        'puzzle_sequencing' => 'Puzzle',
        'quiz_matching' => 'Menjodohkan',
    ];
@endphp

@section('content')
<div class="space-y-5 arena-stage">
    <div class="arena-hero p-5 sm:p-8 relative">
        <div class="arena-hero-stars" aria-hidden="true"></div>
        <div class="relative z-[1] arena-intro">
            <div>
                <p class="arena-eyebrow">Arena Belajar</p>
                <h1 class="arena-title">Katalog Misi</h1>
                <p class="text-sm sm:text-base text-white/75 mt-2.5 max-w-lg leading-relaxed">
                    Pilih misi nalar, puzzle, atau recall — main solo atau lewat penugasan kelas.
                </p>
                <div class="arena-cta-row mt-5">
                    <a href="{{ route('jagat-misi.progress') }}" class="arena-cta arena-cta-amber">
                        <i data-lucide="trophy" class="w-4 h-4"></i> Progres Saya
                    </a>
                    @can('create', \App\Models\Mission::class)
                    <a href="{{ route('jagat-misi.builder.index') }}" class="arena-cta">
                        <i data-lucide="compass" class="w-4 h-4"></i> Builder Misi
                    </a>
                    @endcan
                    @can('viewAnalytics', \App\Models\Mission::class)
                    <a href="{{ route('jagat-misi.analytics') }}" class="arena-cta arena-cta-ghost">Analitik</a>
                    @endcan
                </div>
            </div>
            <div class="arena-planet" aria-hidden="true">
                <div class="arena-orbit arena-orbit-a"><span class="arena-orbit-dot"></span></div>
                <div class="arena-orbit arena-orbit-b"></div>
                <div class="arena-planet-core">AB</div>
            </div>
        </div>
    </div>

    <div class="arena-catalog-grid">
        @forelse($missions as $mission)
        @php
            $playRoute = str_contains($mission->mechanic_type, 'recall') || str_contains($mission->mechanic_type, 'quiz')
                ? route('jagat-misi.player', $mission)
                : route('jagat-misi.play', $mission);
            [$artA, $artB] = $artPairs[$loop->index % count($artPairs)];
            $jenjangKey = $mission->jenjangKey();
            $jenjangLabel = $mission->jenjangLabel();
            $mechanicLabel = $mechanicLabels[$mission->mechanic_type] ?? str_replace('_', ' ', $mission->mechanic_type);
            $displayTitle = trim(preg_replace('/^\[(?:DEMO|Tren)\]\s*/u', '', $mission->title) ?? $mission->title);
            $displaySummary = trim(preg_replace('/^\[(?:Tren\s*2025[–-]2026\s*·\s*)?(?:SD|SMP|SMA\/?SMK)\]\s*/u', '', (string) $mission->summary));
            $isTren = $mission->isTren();
        @endphp
        <a href="{{ $playRoute }}"
           class="arena-mission-card arena-catalog-card arena-anim-in"
           style="animation-delay: {{ $loop->index * 35 }}ms">
            <div class="arena-card-art" style="--art-a:{{ $artA }};--art-b:{{ $artB }}">
                <span class="arena-card-shine" aria-hidden="true"></span>
                <i data-lucide="compass" class="w-9 h-9"></i>
                <span class="arena-card-play" aria-hidden="true"><i data-lucide="play" class="w-4 h-4"></i></span>
            </div>
            <div class="arena-mission-card-body arena-catalog-body">
                <div class="arena-catalog-pills">
                    <span class="arena-pill arena-pill-jenjang arena-pill-{{ $jenjangKey }}">{{ $jenjangLabel }}</span>
                    @if($isTren)
                    <span class="arena-pill arena-pill-tren">Tren 25–26</span>
                    @endif
                    <span class="arena-pill arena-pill-teal">{{ $mission->subject }}</span>
                    <span class="arena-pill">{{ $mechanicLabel }}</span>
                </div>
                <h3 class="arena-catalog-title">{{ $displayTitle }}</h3>
                <p class="arena-catalog-summary">{{ $displaySummary }}</p>
                <div class="arena-catalog-meta">
                    <span>{{ $mission->duration_minutes }} menit</span>
                    <span aria-hidden="true">·</span>
                    <span>Skor maks {{ $mission->max_score }}</span>
                </div>
                <div class="arena-catalog-cta">
                    <span>Mainkan</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </div>
            </div>
        </a>
        @empty
        <div class="arena-panel text-center py-12 arena-catalog-empty">
            <p class="font-black text-lg text-slate-700 dark:text-slate-200">Belum ada misi terbit</p>
            <p class="text-sm text-slate-500 mt-1">Jalankan seeder Arena / Jagat Misi untuk mengisi katalog.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
