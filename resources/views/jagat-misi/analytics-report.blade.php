@extends('layouts.app')
@section('title', $report['title'])

@section('content')
<div class="max-w-2xl mx-auto space-y-4 print:space-y-2">
    <button onclick="window.print()" class="print:hidden px-4 py-2 rounded-xl border text-sm font-bold">Cetak PDF</button>
    <div class="rounded-2xl border p-6 bg-white">
        <h1 class="text-xl font-black">{{ $report['title'] }}</h1>
        <p class="text-sm text-slate-500">{{ $report['student']['name'] }} · {{ $report['student']['class_name'] }}</p>
        <p class="mt-4 text-sm">{{ $report['summary'] }}</p>
        <p class="mt-2 font-bold">Rata-rata skor: {{ $report['average_score'] }}</p>
        <ul class="mt-4 space-y-2 text-sm">
            @foreach($report['masteries'] as $m)
            <li><strong>{{ $m['concept_label'] }}</strong> — {{ $m['score'] }} ({{ $m['level'] }})</li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
