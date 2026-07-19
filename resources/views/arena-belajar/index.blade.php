@extends('layouts.app')
@section('title', 'Arena Belajar')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&display=swap" rel="stylesheet">
@include('arena-belajar.partials.game-styles')
@endpush

@php
    $quizCount = $quizzes->count();
    $missionCount = $missionAssignments->count();
    $artPairs = [
        ['#00c2b2', '#0b3d6e'],
        ['#ffb020', '#e85d75'],
        ['#3ecf8e', '#0b3d6e'],
        ['#ff6b8a', '#00c2b2'],
        ['#4da3ff', '#0b3d6e'],
        ['#ffb020', '#00c2b2'],
    ];
    $mechanicLabels = [
        'nalar_bundle' => 'Nalar',
        'recall_quiz_bundle' => 'Kuis recall',
        'recall_quiz' => 'Kuis recall',
        'interactive_narrative' => 'Narasi',
        'strategic_decision' => 'Keputusan',
        'puzzle_sequencing' => 'Puzzle',
        'quiz_matching' => 'Mencocokkan',
    ];
    $playerName = auth()->user()->displayName();
    $playerInitial = auth()->user()->initial();
@endphp

@section('content')
@php
    $defaultMode = request('mode');
    if (! in_array($defaultMode, ['kuis', 'misi'], true)) {
        $defaultMode = $missionAssignments->isNotEmpty() && $quizzes->isEmpty() ? 'misi' : 'kuis';
    }
@endphp
<div class="arena-stage arena-lobby"
     x-data="{ mode: '{{ $defaultMode }}', jenjang: 'semua', hanyaTren: false, entered: false }"
     x-init="setTimeout(() => entered = true, 80)">

    {{-- World backdrop --}}
    <div class="arena-lobby-world" aria-hidden="true">
        <div class="arena-lobby-sky"></div>
        <div class="arena-lobby-grid"></div>
        <span class="arena-float-block arena-fb-a"></span>
        <span class="arena-float-block arena-fb-b"></span>
        <span class="arena-float-block arena-fb-c"></span>
        <span class="arena-float-block arena-fb-d"></span>
        <span class="arena-float-coin arena-fc-a"></span>
        <span class="arena-float-coin arena-fc-b"></span>
    </div>

    {{-- Top HUD --}}
    <header class="arena-lobby-hud arena-anim-in">
        <a href="{{ route('jagat-misi.index') }}" class="arena-hud-back">
            <i data-lucide="chevron-left" class="w-4 h-4"></i>
            <span class="truncate">Kembali</span>
        </a>
        <div class="arena-hud-player">
            <span class="arena-hud-avatar" aria-hidden="true">{{ $playerInitial }}</span>
            <div class="min-w-0">
                <p class="arena-hud-name truncate">{{ $playerName }}</p>
                <p class="arena-hud-role">{{ $canManage ? 'Host · Guru' : 'Player · Siswa' }}</p>
            </div>
        </div>
    </header>

    @if(session('success'))
    <div class="relative z-[2] rounded-2xl bg-emerald-50 dark:bg-emerald-900/40 border-2 border-emerald-300 dark:border-emerald-700 text-emerald-800 dark:text-emerald-200 px-4 py-3 text-sm font-bold">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="relative z-[2] rounded-2xl bg-rose-50 dark:bg-rose-900/40 border-2 border-rose-300 dark:border-rose-700 text-rose-800 dark:text-rose-200 px-4 py-3 text-sm font-bold">{{ session('error') }}</div>
    @endif

    {{-- Lobby welcome --}}
    <section class="arena-lobby-welcome" :class="{ 'is-in': entered }">
        <div class="arena-lobby-mascot" aria-hidden="true">
            <div class="arena-mascot-ring"></div>
            <div class="arena-mascot-core">
                <span>AB</span>
            </div>
            <div class="arena-mascot-badge">ONLINE</div>
        </div>
        <p class="arena-lobby-kicker">Game lobby · Ruang kelas</p>
        <h1 class="arena-lobby-brand">Arena Belajar</h1>
        <p class="arena-lobby-tagline">Pilih dunia, rebut podium, skor masuk rapor.</p>

        <div class="arena-lobby-stats">
            <div class="arena-chip3d">
                <strong>{{ $quizCount }}</strong>
                <span>Experience kuis</span>
            </div>
            <div class="arena-chip3d arena-chip3d-amber">
                <strong>{{ $missionCount }}</strong>
                <span>World misi</span>
            </div>
            <div class="arena-chip3d arena-chip3d-sky">
                <strong>LIVE</strong>
                <span>Podium</span>
            </div>
        </div>

        <div class="arena-lobby-actions">
            @if($canManage)
            <a href="{{ route('classroom.arena.create', $classroom) }}" class="arena-play-btn">
                <i data-lucide="plus" class="w-5 h-5"></i> Buat Experience
            </a>
            @can('create', \App\Models\Mission::class)
            <a href="{{ route('jagat-misi.builder.index') }}" class="arena-play-btn arena-play-btn-amber">
                <i data-lucide="hammer" class="w-5 h-5"></i> Builder Misi
            </a>
            @endcan
            @else
            <button type="button" class="arena-play-btn" @click="mode='kuis'; $refs.discover?.scrollIntoView({ behavior: 'smooth', block: 'start' })">
                <i data-lucide="play" class="w-5 h-5"></i> Main Sekarang
            </button>
            <a href="{{ route('jagat-misi.progress') }}" class="arena-play-btn arena-play-btn-ghost">
                <i data-lucide="trophy" class="w-5 h-5"></i> Progres Saya
            </a>
            @endif
        </div>
    </section>

    {{-- World portals (mode switch) --}}
    <div class="arena-world-portals" role="tablist">
        <button type="button" class="arena-portal" :class="{ active: mode === 'kuis' }" @click="mode='kuis'" role="tab">
            <span class="arena-portal-thumb arena-portal-thumb-kuis" aria-hidden="true">
                <i data-lucide="gamepad-2" class="w-10 h-10"></i>
                <span class="arena-portal-shine"></span>
            </span>
            <span class="arena-portal-body">
                <span class="arena-portal-label">World</span>
                <span class="arena-portal-title">Kuis Arena</span>
                <span class="arena-portal-meta">{{ $quizCount }} experience · jawab & rebut podium</span>
            </span>
            <span class="arena-portal-join" x-show="mode === 'kuis'">JOINED</span>
        </button>
        <button type="button" class="arena-portal" :class="{ active: mode === 'misi' }" @click="mode='misi'" role="tab">
            <span class="arena-portal-thumb arena-portal-thumb-misi" aria-hidden="true">
                <i data-lucide="compass" class="w-10 h-10"></i>
                <span class="arena-portal-shine"></span>
            </span>
            <span class="arena-portal-body">
                <span class="arena-portal-label">World</span>
                <span class="arena-portal-title">Misi Petualangan</span>
                <span class="arena-portal-meta">{{ $missionCount }} world · nalar · keputusan · puzzle</span>
            </span>
            <span class="arena-portal-join" x-show="mode === 'misi'">JOINED</span>
        </button>
    </div>

    {{-- ===== DISCOVER: KUIS ===== --}}
    <section x-ref="discover" x-show="mode==='kuis'" x-cloak class="arena-discover space-y-4">
        <div class="arena-discover-head">
            <div>
                <p class="arena-lobby-kicker" style="color:var(--arena-teal)">Discover</p>
                <h2 class="arena-discover-title">Recommended for class</h2>
            </div>
            @if($canManage)
            <a href="{{ route('classroom.arena.create', $classroom) }}" class="arena-mini-cta">
                <i data-lucide="plus" class="w-4 h-4"></i> Baru
            </a>
            @endif
        </div>

        <div class="arena-xp-grid">
            @forelse($quizzes as $q)
            @php [$a, $b] = $artPairs[$loop->index % count($artPairs)]; @endphp
            <a href="{{ route('classroom.arena.show', [$classroom, $q]) }}"
               class="arena-xp-card arena-anim-in"
               style="animation-delay: {{ $loop->index * 50 }}ms; --art-a:{{ $a }};--art-b:{{ $b }}">
                <div class="arena-xp-thumb">
                    <span class="arena-xp-blocks" aria-hidden="true"></span>
                    <i data-lucide="gamepad-2" class="w-11 h-11"></i>
                    <span class="arena-xp-play"><i data-lucide="play" class="w-4 h-4 fill-current"></i></span>
                    <span class="arena-xp-status">{{ $q->statusLabel() }}</span>
                </div>
                <div class="arena-xp-info">
                    <h3 class="arena-xp-title">{{ $q->title }}</h3>
                    <p class="arena-xp-meta">
                        <span>{{ $q->questions_count }} soal</span>
                        <span>·</span>
                        <span>{{ $q->max_score }} XP</span>
                        @if($q->due_at)
                        <span>·</span>
                        <span>{{ $q->due_at->locale('id')->translatedFormat('d M') }}</span>
                        @endif
                    </p>
                    <span class="arena-xp-cta">Mainkan</span>
                </div>
            </a>
            @empty
            <div class="arena-xp-empty">
                <div class="arena-xp-empty-ico"><i data-lucide="gamepad-2" class="w-9 h-9"></i></div>
                <p class="font-black text-lg text-slate-800 dark:text-slate-100">Server kuis masih kosong</p>
                <p class="text-sm text-slate-500 mt-1">Buat experience pertama untuk kelas ini.</p>
                @if($canManage)
                <a href="{{ route('classroom.arena.create', $classroom) }}" class="arena-play-btn mt-4 inline-flex">Mulai buat</a>
                @endif
            </div>
            @endforelse
        </div>
    </section>

    {{-- ===== DISCOVER: MISI ===== --}}
    <section x-show="mode==='misi'" x-cloak class="arena-discover space-y-4">
        <div class="arena-discover-head">
            <div>
                <p class="arena-lobby-kicker" style="color:var(--arena-amber)">Discover</p>
                <h2 class="arena-discover-title">Adventure worlds</h2>
            </div>
            @if($canManage)
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('jagat-misi.index') }}" class="arena-mini-cta">Katalog</a>
                @can('viewAnalytics', \App\Models\Mission::class)
                <a href="{{ route('jagat-misi.analytics') }}" class="arena-mini-cta arena-mini-cta-ghost">Analitik</a>
                @endcan
            </div>
            @endif
        </div>

        <div class="arena-panel arena-jenjang-panel arena-lobby-panel">
            <div class="flex flex-wrap items-end justify-between gap-3 mb-3">
                <div>
                    <p class="arena-lobby-kicker" style="color:var(--arena-teal)">Filter world</p>
                    <h3 class="font-black text-slate-800 dark:text-slate-100 m-0 text-lg">Menurut jenjang</h3>
                </div>
                <div class="arena-jenjang-filters" role="group" aria-label="Filter jenjang">
                    <button type="button" class="arena-jenjang-chip" :class="{ active: jenjang === 'semua' && !hanyaTren }" @click="jenjang='semua'; hanyaTren=false">Semua</button>
                    <button type="button" class="arena-jenjang-chip arena-jenjang-sd" :class="{ active: jenjang === 'sd' }" @click="jenjang='sd'; hanyaTren=false">SD</button>
                    <button type="button" class="arena-jenjang-chip arena-jenjang-smp" :class="{ active: jenjang === 'smp' }" @click="jenjang='smp'; hanyaTren=false">SMP</button>
                    <button type="button" class="arena-jenjang-chip arena-jenjang-sma" :class="{ active: jenjang === 'sma' }" @click="jenjang='sma'; hanyaTren=false">SMA/SMK</button>
                    <button type="button" class="arena-jenjang-chip arena-jenjang-tren" :class="{ active: hanyaTren }" @click="hanyaTren=true; jenjang='semua'">Tren 25–26</button>
                </div>
            </div>
            <div class="arena-jenjang-grid" x-show="!hanyaTren">
                @foreach(($jenjangRekomendasi ?? []) as $key => $items)
                @php $label = \App\Support\ArenaJenjang::label($key); @endphp
                <div class="arena-jenjang-card arena-jenjang-{{ $key }}" x-show="jenjang === 'semua' || jenjang === '{{ $key }}'">
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <span class="arena-pill arena-pill-jenjang arena-pill-{{ $key }}">{{ $label }}</span>
                        <span class="text-[11px] font-bold text-slate-400">{{ count($items) }} world</span>
                    </div>
                    <ul class="arena-jenjang-list">
                        @foreach($items as $rec)
                        <li>
                            <strong>{{ $rec['title'] }}</strong>
                            <span>{{ $rec['mechanic'] }} · {{ $rec['subject'] }}</span>
                            <em>{{ $rec['why'] }}</em>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="arena-pill arena-pill-tren">Tren 2025–2026</span>
                    <p class="text-sm text-slate-500 m-0">AI · media · iklim · wellbeing</p>
                </div>
                <div class="arena-jenjang-grid">
                    @foreach(($trenRekomendasi ?? []) as $key => $items)
                    @php $label = \App\Support\ArenaJenjang::label($key); @endphp
                    <div class="arena-jenjang-card arena-jenjang-{{ $key }} arena-tren-card"
                         x-show="jenjang === 'semua' || jenjang === '{{ $key }}'">
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="arena-pill arena-pill-jenjang arena-pill-{{ $key }}">{{ $label }}</span>
                            <span class="text-[11px] font-bold text-slate-400">{{ count($items) }} tren</span>
                        </div>
                        <ul class="arena-jenjang-list">
                            @foreach($items as $rec)
                            <li>
                                <strong>{{ $rec['title'] }}</strong>
                                <span>{{ $rec['tren_tag'] }} · {{ $rec['mechanic'] }}</span>
                                <em>{{ $rec['why'] }}</em>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        @if($canManage && $availableMissions->isNotEmpty())
        <div class="arena-panel arena-lobby-panel">
            <p class="arena-lobby-kicker" style="color:var(--arena-teal)">Host tools</p>
            <h3 class="font-black text-slate-800 dark:text-slate-100 mb-3 text-lg">Kirim misi ke kelas</h3>
            <form method="POST" action="{{ route('classroom.jagat.assign', $classroom) }}" class="space-y-3">
                @csrf
                <select name="mission_id" required class="w-full rounded-2xl border-2 border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-3 text-sm min-h-[48px] font-semibold">
                    <option value="">— Pilih world misi —</option>
                    @foreach($availableMissions as $m)
                    <option value="{{ $m->uuid }}">[{{ $m->jenjangLabel() }}] {{ $m->title }} ({{ $m->subject }})</option>
                    @endforeach
                </select>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold text-slate-500">Buka mulai</label>
                        <input type="datetime-local" name="opens_at" class="w-full rounded-2xl border-2 border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm mt-1">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500">Batas waktu</label>
                        <input type="datetime-local" name="due_at" class="w-full rounded-2xl border-2 border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm mt-1">
                    </div>
                </div>
                <button type="submit" class="arena-play-btn">Tugaskan misi</button>
            </form>
        </div>
        @endif

        <div class="arena-xp-grid">
            @forelse($missionAssignments as $a)
            @php $mission = $a->mission; @endphp
            @if(!$mission) @continue @endif
            @php
                [$artA, $artB] = $artPairs[$loop->index % count($artPairs)];
                $mechanicLabel = $mechanicLabels[$mission->mechanic_type] ?? str_replace('_', ' ', $mission->mechanic_type);
                $jenjangKey = $mission->jenjangKey();
                $jenjangLabel = $mission->jenjangLabel();
                $isTren = $mission->isTren();
                $trenTag = $mission->trenTag();
            @endphp
            <a href="{{ route('classroom.jagat.show', [$classroom, $mission]) }}"
               class="arena-xp-card arena-anim-in"
               style="animation-delay: {{ $loop->index * 50 }}ms; --art-a:{{ $artA }};--art-b:{{ $artB }}"
               x-show="(!hanyaTren || {{ $isTren ? 'true' : 'false' }}) && (jenjang === 'semua' || jenjang === '{{ $jenjangKey }}' || ('{{ $jenjangKey }}' === 'umum' && jenjang === 'semua' && !hanyaTren))"
               x-cloak>
                <div class="arena-xp-thumb">
                    <span class="arena-xp-blocks" aria-hidden="true"></span>
                    <i data-lucide="compass" class="w-11 h-11"></i>
                    <span class="arena-xp-play"><i data-lucide="play" class="w-4 h-4 fill-current"></i></span>
                    <span class="arena-xp-status {{ $a->isOpen() ? '' : 'is-closed' }}">{{ $a->isOpen() ? 'Aktif' : 'Tutup' }}</span>
                </div>
                <div class="arena-xp-info">
                    <div class="flex flex-wrap gap-1 mb-1">
                        <span class="arena-pill arena-pill-jenjang arena-pill-{{ $jenjangKey }}">{{ $jenjangLabel }}</span>
                        @if($isTren)<span class="arena-pill arena-pill-tren">Tren</span>@endif
                        <span class="arena-pill">{{ $mechanicLabel }}</span>
                    </div>
                    <h3 class="arena-xp-title">{{ $mission->title }}</h3>
                    <p class="arena-xp-meta">
                        <span>{{ $mission->subject }}</span>
                        @if($a->due_at)
                        <span>·</span>
                        <span>{{ $a->due_at->locale('id')->translatedFormat('d M') }}</span>
                        @endif
                    </p>
                    @if(auth()->user()->access === 'siswa')
                        @php $att = $myMissionAttempts[$a->uuid] ?? null; @endphp
                        <p class="text-xs font-black m-0 mt-1">
                            @if($att && $att->status === 'completed')
                                <span class="text-emerald-600">★ Clear {{ $att->score }}%</span>
                            @elseif($att)
                                <span class="text-sky-600">▶ In progress</span>
                            @else
                                <span class="text-amber-600">● Belum main</span>
                            @endif
                        </p>
                    @endif
                    <span class="arena-xp-cta">{{ auth()->user()->access === 'siswa' ? 'Masuk world' : 'Lihat world' }}</span>
                </div>
            </a>
            @empty
            <div class="arena-xp-empty">
                <div class="arena-xp-empty-ico" style="background:#fff4e0;color:#9a6700"><i data-lucide="compass" class="w-9 h-9"></i></div>
                <p class="font-black text-lg text-slate-800 dark:text-slate-100">Belum ada world misi</p>
                @if($canManage)
                <p class="text-sm text-slate-500 mt-1">Buat di <a href="{{ route('jagat-misi.builder.index') }}" class="font-bold" style="color:var(--arena-teal)">Builder</a> lalu tugaskan.</p>
                @else
                <p class="text-sm text-slate-500 mt-1">Guru belum membuka world untuk kelas ini.</p>
                @endif
            </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
