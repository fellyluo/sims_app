@extends('layouts.app')
@section('title', 'Jadwal Pelajaran')

@section('content')
@php $breadcrumbs = [['label'=>'Akademik','url'=>'#'], ['label'=>'Jadwal Pelajaran','url'=>route('jadwal.index')]]; @endphp

<div class="max-w-6xl mx-auto space-y-5" x-data="jadwalApp()">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="page-title">Jadwal Pelajaran</h1>
            <p class="text-sm text-slate-500">Kelola jadwal otomatis untuk semua kelas</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button @click="openImportModal()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded-xl text-sm font-bold shadow-lg shadow-emerald-200 dark:shadow-none flex items-center gap-2 transition">
                <i data-lucide="file-up" class="w-4 h-4"></i> Import Excel
            </button>
            <a href="{{ route('jadwal.export', $selectedKelasId) }}" class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-2 rounded-xl text-sm font-bold shadow-lg shadow-amber-200 dark:shadow-none flex items-center gap-2 transition">
                <i data-lucide="file-down" class="w-4 h-4"></i> Export Excel
            </a>
            <a href="{{ route('jadwal.print', $selectedKelasId) }}" target="_blank" class="bg-slate-600 hover:bg-slate-700 text-white px-3 py-2 rounded-xl text-sm font-bold shadow-lg shadow-slate-200 dark:shadow-none flex items-center gap-2 transition">
                <i data-lucide="printer" class="w-4 h-4"></i> Cetak PDF
            </a>
            <form method="POST" action="{{ route('jadwal.generate') }}" onsubmit="return confirmAction(this, 'Jadwal yang sudah ada akan dihapus dan di-generate ulang. Lanjutkan?')">
                @csrf
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-xl text-sm font-bold shadow-lg shadow-indigo-200 dark:shadow-none flex items-center gap-2 transition">
                    <i data-lucide="sparkles" class="w-4 h-4"></i> Generate Otomatis
                </button>
            </form>
        </div>
    </div>

    {{-- Filter Kelas --}}
    <div class="card p-4 flex flex-col sm:flex-row sm:items-center gap-4">
        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 whitespace-nowrap">Pilih Kelas:</label>
        <form method="GET" action="{{ route('jadwal.index') }}" class="w-full sm:w-64" id="formFilter">
            <select name="kelas" class="form-select" data-tom onchange="document.getElementById('formFilter').submit()">
                @foreach($kelas as $k)
                <option value="{{ $k->uuid }}" {{ $selectedKelasId == $k->uuid ? 'selected' : '' }}>
                    Kelas {{ $k->tingkat }}{{ $k->kelas }} ({{ $k->nama_lengkap }})
                </option>
                @endforeach
            </select>
        </form>
        <div class="ml-auto">
            <button @click="openModal()" class="btn-primary px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Manual
            </button>
        </div>
    </div>

    {{-- Grid Jadwal --}}
    @php
        $hari_nama = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat'];
        $max_jam = 10;
        $waktuJadwal = json_decode(\App\Models\Setting::get('jadwal_waktu', '{}'), true);
        
        $grid = [];
        for($h=1; $h<=5; $h++) {
            for($j=1; $j<=$max_jam; $j++) $grid[$h][$j] = [];
            if(isset($jadwals[$h])) {
                foreach($jadwals[$h] as $jadwal) {
                    $grid[$h][$jadwal->jam_ke][] = $jadwal;
                }
            }
        }
    @endphp

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50 dark:bg-slate-800 dark:text-slate-400">
                    <tr>
                        <th class="px-4 py-3 border-b dark:border-slate-700 w-24">Jam Ke-</th>
                        @foreach($hari_nama as $h_id => $h_nama)
                            <th class="px-4 py-3 border-b border-l dark:border-slate-700 text-center w-1/5">{{ $h_nama }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @for($j=1; $j<=$max_jam; $j++)
                    <tr class="border-b dark:border-slate-700 last:border-0 hover:bg-slate-50/50 dark:hover:bg-slate-800/50">
                        <td class="px-4 py-4 font-bold text-slate-500 bg-slate-50/30 dark:bg-slate-800/30 text-center">
                            {{ $j }}
                            @if(!empty($waktuJadwal[$j]['mulai']) && !empty($waktuJadwal[$j]['selesai']))
                                <br><span class="text-[10px] font-normal text-slate-400">{{ $waktuJadwal[$j]['mulai'] }} - {{ $waktuJadwal[$j]['selesai'] }}</span>
                            @endif
                        </td>
                        @for($h=1; $h<=5; $h++)
                            <td class="border-l dark:border-slate-700 p-2 align-top h-24 relative group">
                                @if(count($grid[$h][$j]) > 0)
                                    @foreach($grid[$h][$j] as $jadwal)
                                    <div class="p-3 rounded-xl border {{ $jadwal->pelajaran?->warna ? 'bg-'.$jadwal->pelajaran->warna.'-50 border-'.$jadwal->pelajaran->warna.'-100 dark:bg-'.$jadwal->pelajaran->warna.'-900/20 dark:border-'.$jadwal->pelajaran->warna.'-800/30' : 'bg-slate-50 border-slate-200 dark:bg-slate-800 dark:border-slate-700' }} h-full flex flex-col justify-between relative transition-transform hover:-translate-y-0.5 mb-2">
                                        <div>
                                            <div class="font-bold text-slate-800 dark:text-slate-100 leading-tight mb-1">{{ $jadwal->pelajaran?->nama }}</div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $jadwal->guru?->nama }}</div>
                                        </div>
                                        <button @click="deleteJadwal('{{ $jadwal->uuid }}')" class="absolute top-2 right-2 p-1.5 rounded-lg hover:bg-rose-100 text-rose-500 opacity-0 group-hover:opacity-100 transition-opacity" title="Hapus">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="w-full h-full rounded-xl border border-dashed border-slate-200 dark:border-slate-700 bg-slate-50/30 dark:bg-slate-800/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click="openModal({{ $h }}, {{ $j }})" class="text-xs font-semibold text-slate-400 hover:text-primary flex items-center gap-1">
                                            <i data-lucide="plus" class="w-3 h-3"></i> Isi Slot
                                        </button>
                                    </div>
                                @endif
                            </td>
                        @endfor
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div x-show="showModal" style="display: none" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" aria-hidden="true" @click="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showModal" x-transition.scale class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-slate-800 rounded-2xl shadow-xl">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4 flex items-center gap-2" id="modal-title">
                    <i data-lucide="calendar-plus" class="w-5 h-5 text-primary"></i> Tambah Jadwal Manual
                </h3>
                
                <form @submit.prevent="submitForm">
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Hari</label>
                            <select x-model="form.hari" class="form-input" required>
                                <option value="1">Senin</option>
                                <option value="2">Selasa</option>
                                <option value="3">Rabu</option>
                                <option value="4">Kamis</option>
                                <option value="5">Jumat</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Jam Ke-</label>
                            <select x-model="form.jam_ke" class="form-input" required>
                                @for($j=1; $j<=10; $j++)
                                @php
                                    $waktuLabel = (!empty($waktuJadwal[$j]['mulai']) && !empty($waktuJadwal[$j]['selesai'])) ? " ({$waktuJadwal[$j]['mulai']} - {$waktuJadwal[$j]['selesai']})" : "";
                                @endphp
                                <option value="{{ $j }}">Jam {{ $j }}{{ $waktuLabel }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Mata Pelajaran</label>
                            <select x-model="form.id_pelajaran" class="form-input" required>
                                <option value="">Pilih Pelajaran</option>
                                @foreach($pelajarans as $p)
                                <option value="{{ $p->uuid }}">{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Guru Pengajar</label>
                            <select x-model="form.id_guru" class="form-input" required>
                                <option value="">Pilih Guru</option>
                                @foreach($gurus as $g)
                                <option value="{{ $g->uuid }}">{{ $g->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="closeModal()" class="px-4 py-2 text-sm font-semibold text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition">Batal</button>
                        <button type="submit" class="btn-primary px-5 py-2 rounded-xl text-sm font-bold flex items-center gap-2" :disabled="loading">
                            <span x-show="!loading">Simpan Jadwal</span>
                            <span x-show="loading">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Modal Import CSV --}}
    <div x-show="showImportModal" style="display: none" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showImportModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" aria-hidden="true" @click="closeImportModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showImportModal" x-transition.scale class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-slate-800 rounded-2xl shadow-xl">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4 flex items-center gap-2" id="modal-title">
                    <i data-lucide="file-up" class="w-5 h-5 text-primary"></i> Import Jadwal dari Excel
                </h3>
                
                <form method="POST" action="{{ route('jadwal.import') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_kelas" value="{{ $selectedKelasId }}">
                    <div class="space-y-4">
                        <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800">
                            <p class="text-sm text-amber-700 dark:text-amber-400">
                                <strong>Catatan Format:</strong> Excel/CSV harus berisi kolom: <br>
                                Hari (Senin-Jumat), Jam Ke (1-10), Nama Pelajaran, Kode Pelajaran, Nama Guru.
                            </p>
                        </div>
                        <div>
                            <label class="form-label">Pilih File Excel/CSV</label>
                            <input type="file" name="file" accept=".xlsx, .xls, .csv" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20" required>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="closeImportModal()" class="px-4 py-2 text-sm font-semibold text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition">Batal</button>
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-xl text-sm font-bold flex items-center gap-2">
                            Mulai Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('[data-tom]').forEach(el => new TomSelect(el, { create:false }));

    function jadwalApp() {
        return {
            showModal: false,
            showImportModal: false,
            loading: false,
            form: {
                id_kelas: '{{ $selectedKelasId }}',
                id_pelajaran: '',
                id_guru: '',
                hari: '1',
                jam_ke: '1'
            },
            openModal(h = 1, j = 1) {
                this.form.hari = h;
                this.form.jam_ke = j;
                this.form.id_pelajaran = '';
                this.form.id_guru = '';
                this.showModal = true;
            },
            closeModal() {
                this.showModal = false;
            },
            openImportModal() {
                this.showImportModal = true;
            },
            closeImportModal() {
                this.showImportModal = false;
            },
            async submitForm() {
                this.loading = true;
                try {
                    const res = await fetch('{{ route('jadwal.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(this.form)
                    });
                    const data = await res.json();
                    if(data.success) {
                        showToast(data.message, 'success');
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        let errMsg = data.message || 'Terjadi kesalahan';
                        if (data.errors) {
                            errMsg = Object.values(data.errors).flat().join('<br>');
                        }
                        showToast(errMsg, 'error');
                    }
                } catch(e) {
                    showToast('Terjadi kesalahan koneksi/server', 'error');
                    console.error(e);
                }
                this.loading = false;
            },
            async deleteJadwal(uuid) {
                if(!confirm('Hapus jadwal ini?')) return;
                try {
                    const res = await fetch(`/jadwal/${uuid}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const data = await res.json();
                    if(data.success) {
                        showToast(data.message, 'success');
                        setTimeout(() => window.location.reload(), 1000);
                    }
                } catch(e) {
                    showToast('Gagal menghapus', 'error');
                }
            }
        }
    }
</script>
@endpush
@endsection
