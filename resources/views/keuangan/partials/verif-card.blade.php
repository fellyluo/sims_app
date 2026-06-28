{{--
    Kartu verifikasi satu pembayaran (boleh banyak bulan / satu batch).
    Variabel: $group (Collection<SppPembayaran>), $mode ('verify' | 'validate').
    - mode 'verify'   : tahap 1 (menunggu → terverifikasi)  → tombol "Verifikasi Bukti"
    - mode 'validate' : tahap 2 (terverifikasi → lunas)      → tombol "Validasi (Lunas)"
--}}
@php
    $first  = $group->first();
    $jumlah = $group->count();
    $total  = (int) $group->sum('nominal');
    $isVerify = $mode === 'verify';
    $aksiRoute = $isVerify ? route('keuangan.verify-batch') : route('keuangan.validate-batch');
    $aksiLabel = $isVerify ? 'Verifikasi Bukti' : 'Validasi (Lunas)';
    $aksiWarna = $isVerify ? 'blue' : 'blue';
    $konfirmasi = $isVerify
        ? "Tandai bukti {$jumlah} bulan ({$first->siswa?->nama}) sebagai TERVERIFIKASI? Pastikan nominal & bukti sesuai."
        : "Validasi {$jumlah} bulan ({$first->siswa?->nama}, total Rp ".number_format($total,0,',','.').") via rekening koran & tandai LUNAS?";
@endphp
<div class="card p-4 flex flex-col sm:flex-row gap-4" x-data="{ rejectOpen:false, reviseOpen:false }">
    {{-- Bukti --}}
    <a href="{{ $first->bukti_url }}" target="_blank" class="block w-full sm:w-40 flex-shrink-0 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 hover:opacity-90">
        @if($first->bukti_url)
            <img src="{{ $first->bukti_url }}" alt="Bukti" class="w-full h-40 object-cover bg-slate-50 dark:bg-slate-900">
        @else
            <div class="w-full h-40 grid place-items-center text-slate-300"><i data-lucide="image-off" class="w-8 h-8"></i></div>
        @endif
    </a>

    {{-- Info --}}
    <div class="flex-1 min-w-0">
        <div class="flex items-start justify-between gap-2">
            <div>
                <p class="font-bold text-slate-800 dark:text-slate-100">{{ $first->siswa?->nama }}</p>
                <p class="text-xs text-slate-400">{{ $first->siswa?->kelas?->nama_lengkap }} · NIS {{ $first->siswa?->nis }}</p>
            </div>
            @if($jumlah > 1)
                <span class="badge bg-primary/15 text-primary flex items-center gap-1"><i data-lucide="layers" class="w-3 h-3"></i> {{ $jumlah }} bulan</span>
            @endif
        </div>

        <div class="flex flex-wrap gap-1.5 mt-2.5">
            @foreach($group->sortBy('bulan') as $p)
                <span class="badge {{ $isVerify ? 'bg-amber-100 dark:bg-amber-900 text-amber-700 dark:text-amber-300' : 'bg-sky-100 dark:bg-sky-900 text-sky-700 dark:text-sky-300' }}">{{ $p->label_bulan }}</span>
            @endforeach
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mt-3 text-sm">
            <div><span class="text-slate-400 text-xs block">Total</span><span class="font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($total,0,',','.') }}</span></div>
            <div><span class="text-slate-400 text-xs block">Bank / Metode</span><span class="font-medium text-slate-700 dark:text-slate-200">{{ $first->bank ?? '-' }}</span></div>
            <div><span class="text-slate-400 text-xs block">Tgl Bayar</span><span class="font-medium text-slate-700 dark:text-slate-200">{{ optional($first->tanggal_bayar)->format('d/m/Y') ?? '-' }}</span></div>
            <div><span class="text-slate-400 text-xs block">{{ $isVerify ? 'Diunggah' : 'Diverifikasi' }}</span><span class="font-medium text-slate-700 dark:text-slate-200">{{ optional($isVerify ? $first->updated_at : $first->diverifikasi_pada)?->diffForHumans() ?? '-' }}</span></div>
        </div>

        @unless($isVerify)
        <p class="text-xs text-sky-600 dark:text-sky-400 mt-2 flex items-center gap-1"><i data-lucide="info" class="w-3.5 h-3.5"></i> Bukti sudah diverifikasi. Validasi dana masuk lewat rekening koran bank sebelum menandai lunas.</p>
        @endunless

        {{-- Aksi --}}
        <div class="flex flex-wrap gap-2 mt-4">
            <form method="POST" action="{{ $aksiRoute }}"
                  onsubmit="return confirmAction(this, '{{ addslashes($konfirmasi) }}', '{{ $aksiWarna }}')">
                @csrf
                @foreach($group as $p)<input type="hidden" name="ids[]" value="{{ $p->uuid }}">@endforeach
                <button class="btn-primary flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold">
                    <i data-lucide="{{ $isVerify ? 'badge-check' : 'check-check' }}" class="w-4 h-4"></i> {{ $aksiLabel }} {{ $jumlah > 1 ? '· '.$jumlah.' bln' : '' }}
                </button>
            </form>
            <button @click="reviseOpen=!reviseOpen; rejectOpen=false" type="button" class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold border border-amber-200 dark:border-amber-700 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20">
                <i data-lucide="pencil" class="w-4 h-4"></i> Revisi
            </button>
            <button @click="rejectOpen=!rejectOpen; reviseOpen=false" type="button" class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold border border-rose-200 dark:border-rose-700 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20">
                <i data-lucide="x" class="w-4 h-4"></i> Tolak
            </button>
        </div>

        {{-- Form revisi (perbaiki nominal/tanggal/bank) --}}
        <form x-show="reviseOpen" x-transition x-cloak method="POST" action="{{ route('keuangan.revise-batch') }}" class="mt-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700 space-y-2.5">
            @csrf
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Revisi nominal per bulan</p>
            @foreach($group->sortBy('bulan') as $p)
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-600 dark:text-slate-300 w-28 flex-shrink-0">{{ $p->label_bulan }}</span>
                <div class="relative flex-1">
                    <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-slate-400">Rp</span>
                    <input type="number" name="nominal[{{ $p->uuid }}]" value="{{ (int) $p->nominal }}" min="0" class="form-input text-sm !pl-8">
                </div>
            </div>
            @endforeach
            <div class="grid grid-cols-2 gap-2 pt-1">
                <div>
                    <label class="form-label !text-[11px]">Tgl Bayar</label>
                    <input type="date" name="tanggal_bayar" value="{{ optional($first->tanggal_bayar)->format('Y-m-d') }}" class="form-input text-sm">
                </div>
                <div>
                    <label class="form-label !text-[11px]">Bank / Metode</label>
                    <input type="text" name="bank" value="{{ $first->bank }}" maxlength="60" class="form-input text-sm">
                </div>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" @click="reviseOpen=false" class="px-3 py-1.5 rounded-lg text-sm font-semibold text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700">Batal</button>
                <button class="btn-primary px-4 py-1.5 rounded-lg text-sm font-bold flex items-center gap-1.5"><i data-lucide="save" class="w-3.5 h-3.5"></i> Simpan Revisi</button>
            </div>
        </form>

        {{-- Form tolak --}}
        <form x-show="rejectOpen" x-transition x-cloak method="POST" action="{{ route('keuangan.reject-batch') }}" class="mt-3 flex gap-2">
            @csrf
            @foreach($group as $p)<input type="hidden" name="ids[]" value="{{ $p->uuid }}">@endforeach
            <input type="text" name="catatan" required maxlength="500" placeholder="Alasan penolakan (mis. nominal kurang / dana tidak masuk)" class="form-input text-sm flex-1">
            <button class="px-4 py-2 rounded-xl text-sm font-bold text-white bg-rose-500 hover:bg-rose-600">Tolak {{ $jumlah > 1 ? 'Semua' : '' }}</button>
        </form>
    </div>
</div>
