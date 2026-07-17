@extends('layouts.app')
@section('title', $mission->title . ' — Arena Belajar')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/jagat-misi/styles.css') }}">
<link rel="stylesheet" href="{{ asset('assets/jagat-misi/nalar.css') }}">
@endpush

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between gap-3">
        <div>
            <a href="{{ isset($classroom) ? route('classroom.jagat.show', [$classroom, $mission]) : route('jagat-misi.index') }}" class="text-xs text-slate-500 hover:text-primary inline-flex items-center gap-1 mb-2">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> {{ isset($classroom) ? $classroom->title : 'Katalog Misi' }}
            </a>
            <h1 class="text-xl sm:text-2xl font-black">{{ $mission->title }}</h1>
            <p class="text-sm text-slate-500">{{ $mission->summary }}</p>
        </div>
        <button type="button" id="submitMission" class="px-4 py-2 rounded-xl bg-primary text-white text-sm font-bold">Kumpulkan Misi</button>
    </div>

    <div id="missionPlayer" class="nalar-shell" data-mission-slug="{{ $mission->slug }}"
         data-api-show="{{ route('jagat-misi.api.show', $mission) }}"
         data-api-submit="{{ route('jagat-misi.api.attempts', $mission) }}"
         data-csrf="{{ csrf_token() }}"
         @isset($assignment) data-assignment-id="{{ $assignment->uuid }}" @endisset>
        <div class="module-switch" role="tablist">
            <button class="module-tab active" type="button" data-module="narrative">Narasi</button>
            <button class="module-tab" type="button" data-module="decision">Keputusan</button>
            <button class="module-tab" type="button" data-module="puzzle">Puzzle</button>
        </div>
        <div class="nalar-layout">
            <section class="nalar-stage">
                <section class="module-panel active" id="panel-narrative">
                    <div class="module-head"><h2 id="narrativeTitle">Memuat…</h2></div>
                    <div class="story-card" id="narrativeCard"></div>
                    <div class="path-row" id="narrativePath"></div>
                </section>
                <section class="module-panel" id="panel-decision">
                    <div class="module-head"><h2>Keputusan Strategis</h2></div>
                    <div id="decisionBoard"></div>
                </section>
                <section class="module-panel" id="panel-puzzle">
                    <div class="module-head"><h2>Puzzle Sequencing</h2></div>
                    <div id="puzzleBoard" class="puzzle-board"></div>
                </section>
            </section>
            <aside class="nalar-side">
                <div class="summary-card" id="missionSummary">
                    <p class="eyebrow">Ringkasan</p>
                    <h3>Skor sementara</h3>
                    <p id="summaryScore">0%</p>
                </div>
            </aside>
        </div>
    </div>
    <div id="submitResult" class="hidden rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 p-4 text-sm"></div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/jagat-misi/nalar.js') }}"></script>
<script>
document.getElementById('submitMission')?.addEventListener('click', async () => {
    const root = document.getElementById('missionPlayer');
    const url = root.dataset.apiSubmit;
    const payload = window.JagatMisiNalar?.buildSubmitPayload?.() ?? {};
    if (root.dataset.assignmentId) {
        payload.assignment_id = root.dataset.assignmentId;
    }
    const res = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': root.dataset.csrf,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(payload),
    });
    const data = await res.json();
    const box = document.getElementById('submitResult');
    box.classList.remove('hidden');
    if (res.ok) {
        box.textContent = `Misi selesai! Skor: ${data.data?.attempt?.score ?? 0}%`;
        box.className = 'rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-700';
    } else {
        box.textContent = data.message || 'Gagal mengumpulkan misi.';
        box.className = 'rounded-xl bg-rose-50 border border-rose-200 p-4 text-sm text-rose-700';
    }
});
</script>
@endpush
