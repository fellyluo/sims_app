@extends('layouts.app')
@section('title', ($mission ? 'Edit' : 'Buat') . ' Misi')

@section('content')
<div class="max-w-3xl mx-auto space-y-4">
    <a href="{{ route('jagat-misi.builder.index') }}" class="text-xs text-slate-500">← Daftar Misi</a>
    <h1 class="text-2xl font-black">{{ $mission ? 'Edit Misi' : 'Buat Misi Baru' }}</h1>

    <form method="post" action="{{ $mission ? route('jagat-misi.builder.update', $mission) : route('jagat-misi.builder.store') }}" class="space-y-4 rounded-2xl border p-5 bg-white dark:bg-slate-900">
        @csrf
        <div class="grid sm:grid-cols-2 gap-3">
            <input name="title" value="{{ old('title', $mission?->title) }}" placeholder="Judul misi" class="border rounded-xl px-3 py-2 text-sm" required>
            <input name="subject" value="{{ old('subject', $mission?->subject) }}" placeholder="Mapel" class="border rounded-xl px-3 py-2 text-sm" required>
            <input name="grade_level" value="{{ old('grade_level', $mission?->grade_level) }}" placeholder="Tingkat" class="border rounded-xl px-3 py-2 text-sm" required>
            <input name="mechanic_type" value="{{ old('mechanic_type', $mission?->mechanic_type ?? 'recall_quiz_bundle') }}" placeholder="Mekanik" class="border rounded-xl px-3 py-2 text-sm" required>
            <input name="duration_minutes" type="number" value="{{ old('duration_minutes', $mission?->duration_minutes ?? 30) }}" class="border rounded-xl px-3 py-2 text-sm" required>
        </div>
        <textarea name="summary" rows="3" class="w-full border rounded-xl px-3 py-2 text-sm" placeholder="Ringkasan" required>{{ old('summary', $mission?->summary) }}</textarea>
        <textarea name="reflections[]" rows="2" class="w-full border rounded-xl px-3 py-2 text-sm" placeholder="Prompt refleksi 1">{{ $mission?->reflectionPrompts->first()?->prompt_text }}</textarea>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="requires_reflection" value="1" {{ old('requires_reflection', $mission?->requires_reflection ?? true) ? 'checked' : '' }}> Wajib refleksi</label>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="visible_to_teachers" value="1" {{ old('visible_to_teachers', $mission?->visible_to_teachers) ? 'checked' : '' }}> Bagikan ke guru lain</label>
        <button type="submit" class="px-4 py-2 rounded-xl bg-primary text-white text-sm font-bold">Simpan</button>
        @if($mission)
        <a href="{{ route('jagat-misi.builder.publish', $mission) }}" onclick="event.preventDefault(); document.getElementById('publishForm').submit();" class="ml-2 text-sm text-primary font-bold">Terbitkan</a>
        @endif
    </form>
    @if($mission)
    <form id="publishForm" method="post" action="{{ route('jagat-misi.builder.publish', $mission) }}">@csrf</form>
    @endif
</div>
@endsection
