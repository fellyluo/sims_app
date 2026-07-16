@extends('layouts.app')
@section('title', 'Mission Builder')

@section('content')
<div class="space-y-5">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-black">Mission Builder</h1>
        <a href="{{ route('jagat-misi.builder.create') }}" class="px-4 py-2 rounded-xl bg-primary text-white text-sm font-bold">Buat Misi</a>
    </div>
    @if(session('success'))
    <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif
    <div class="grid gap-3">
        @forelse($missions as $m)
        <a href="{{ route('jagat-misi.builder.edit', $m) }}" class="rounded-2xl border p-4 bg-white dark:bg-slate-900 hover:shadow">
            <p class="font-bold">{{ $m->title }}</p>
            <p class="text-xs text-slate-500">{{ $m->subject }} · {{ $m->status }}</p>
        </a>
        @empty
        <p class="text-sm text-slate-500">Belum ada misi. Buat misi pertama.</p>
        @endforelse
    </div>
</div>
@endsection
