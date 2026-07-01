{{-- ===== Sebaran Siswa per Kelas (rombel) ===== --}}
@php
    $perKelas = \App\Models\Kelas::withCount('siswa')
        ->orderBy('tingkat')->orderBy('kelas')->get();
    $maxKelas = max($perKelas->max('siswa_count') ?? 0, 1);
@endphp
<div class="card p-5">
    <div class="flex items-center justify-between mb-2">
        <div>
            <h2 class="font-bold text-slate-700 dark:text-slate-200">Sebaran Siswa per Kelas</h2>
            <p class="text-[10px] text-slate-400 dark:text-slate-500">Jumlah siswa terdistribusi di setiap rombongan belajar</p>
        </div>
        <a href="{{ route('kelas.index') }}" class="text-xs font-semibold text-primary hover:underline">{{ $perKelas->count() }} rombel</a>
    </div>
    @if($perKelas->isEmpty())
        <p class="text-sm text-slate-400 text-center py-8">Belum ada data kelas</p>
    @else
    <div class="overflow-x-auto scrollbar-thin">
        <div class="relative h-52 min-w-[550px] w-full pt-8 pb-6">
            <!-- Grid Lines -->
            <div class="absolute inset-x-0 top-8 bottom-6 flex flex-col justify-between pointer-events-none opacity-50 dark:opacity-20">
                <div class="border-b border-dashed border-slate-200 dark:border-slate-700 w-full"></div>
                <div class="border-b border-dashed border-slate-200 dark:border-slate-700 w-full"></div>
                <div class="border-b border-dashed border-slate-200 dark:border-slate-700 w-full"></div>
                <div class="border-b border-dashed border-slate-200 dark:border-slate-700 w-full"></div>
            </div>
            <!-- Bars Container -->
            <div class="absolute inset-x-0 top-8 bottom-6 flex items-end justify-between gap-2.5 px-2">
                @foreach($perKelas as $k)
                @php
                    $percent = round($k->siswa_count / $maxKelas * 100);
                @endphp
                <div class="flex-1 flex flex-col items-center h-full justify-end group relative">
                    <!-- Tooltip -->
                    <div class="absolute bottom-full mb-2 hidden group-hover:flex flex-col items-center transition-all duration-200 z-30">
                        <span class="bg-slate-800 text-white text-[10px] font-bold px-2 py-1 rounded shadow-md whitespace-nowrap">
                            {{ $k->siswa_count }} Siswa
                        </span>
                        <!-- Arrow -->
                        <div class="w-1.5 h-1.5 bg-slate-800 rotate-45 -mt-1"></div>
                    </div>
                    <!-- Bar -->
                    <div class="w-full sm:w-8 rounded-t bg-gradient-to-t from-primary/80 to-accent/90 group-hover:from-primary group-hover:to-accent transition-all duration-300 cursor-pointer shadow-sm group-hover:shadow-md"
                         style="height: {{ max($percent, 6) }}%"></div>
                    <!-- Label -->
                    <span class="absolute top-full mt-1.5 text-[10px] font-bold text-slate-500 dark:text-slate-400 group-hover:text-primary transition-colors text-center whitespace-nowrap">
                        {{ $k->tingkat }}{{ $k->kelas }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
