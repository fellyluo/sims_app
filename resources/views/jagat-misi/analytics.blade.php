@extends('layouts.app')
@section('title', 'Analitik Jagat Misi')

@section('content')
<div class="space-y-5">
    <h1 class="text-2xl font-black">Matriks Penguasaan</h1>
    <div class="overflow-x-auto rounded-2xl border">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800">
                <tr>
                    <th class="p-3 text-left">Siswa</th>
                    <th class="p-3 text-left">Kelas</th>
                    @foreach($matrix['concepts'] as $concept)
                    <th class="p-3 text-left">{{ $concept['label'] }}</th>
                    @endforeach
                    <th class="p-3 text-left">Level</th>
                </tr>
            </thead>
            <tbody>
                @forelse($matrix['students'] as $row)
                <tr class="border-t">
                    <td class="p-3 font-semibold">{{ $row['name'] }}</td>
                    <td class="p-3">{{ $row['class_name'] }}</td>
                    @foreach($matrix['concepts'] as $concept)
                    <td class="p-3">{{ $row['scores'][$concept['key']] ?? '—' }}</td>
                    @endforeach
                    <td class="p-3 capitalize">{{ $row['level'] }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-6 text-center text-slate-500">Belum ada data penguasaan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
