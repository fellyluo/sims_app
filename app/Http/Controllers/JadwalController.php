<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Pelajaran;
use App\Services\TimetableGenerator;
use Illuminate\Http\Request;
use App\Imports\JadwalImport;
use App\Exports\JadwalExport;
use Maatwebsite\Excel\Facades\Excel;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::orderBy('tingkat')->orderBy('kelas')->get();
        $pelajarans = Pelajaran::orderBy('urutan')->orderBy('nama')->get();
        $gurus = Guru::orderBy('nama')->get();

        $selectedKelasId = $request->get('kelas');
        if (empty($selectedKelasId)) {
            $selectedKelasId = $kelas->first()?->uuid;
        }
        
        $jadwals = collect();
        if ($selectedKelasId) {
            $jadwals = Jadwal::with(['pelajaran', 'guru'])
                ->where('id_kelas', $selectedKelasId)
                ->orderBy('hari')
                ->orderBy('jam_ke')
                ->get()
                ->groupBy('hari');
        }

        return view('jadwal.index', compact('kelas', 'pelajarans', 'gurus', 'selectedKelasId', 'jadwals'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_kelas' => 'required|exists:kelas,uuid',
            'id_pelajaran' => 'required|exists:pelajarans,uuid',
            'id_guru' => 'required|exists:gurus,uuid',
            'hari' => 'required|integer|min:1|max:5',
            'jam_ke' => 'required|integer|min:1|max:10',
        ]);

        // Cek Bentrok Kelas
        $bentrokKelas = Jadwal::where('id_kelas', $data['id_kelas'])
            ->where('hari', $data['hari'])
            ->where('jam_ke', $data['jam_ke'])
            ->with(['pelajaran', 'guru'])
            ->first();

        if ($bentrokKelas) {
            return response()->json([
                'success' => false,
                'message' => "Kelas sudah memiliki mata pelajaran {$bentrokKelas->pelajaran->nama} pada waktu tersebut."
            ], 422);
        }

        // Cek Bentrok Guru
        $bentrokGuru = Jadwal::where('id_guru', $data['id_guru'])
            ->where('hari', $data['hari'])
            ->where('jam_ke', $data['jam_ke'])
            ->with('kelas')
            ->first();

        if ($bentrokGuru) {
            return response()->json([
                'success' => false,
                'message' => "Guru tersebut sudah memiliki jadwal mengajar di {$bentrokGuru->kelas->nama_lengkap} pada waktu tersebut."
            ], 422);
        }

        // Cek Ketersediaan Guru
        $unavailable = \App\Models\GuruKetersediaan::where('id_guru', $data['id_guru'])
            ->where('hari', $data['hari'])
            ->where('jam_ke', $data['jam_ke'])
            ->exists();
            
        if ($unavailable) {
            return response()->json([
                'success' => false,
                'message' => "Guru tersebut disetel TIDAK BISA MENGAJAR pada waktu tersebut."
            ], 422);
        }

        Jadwal::create($data);

        return response()->json(['success' => true, 'message' => 'Jadwal berhasil ditambahkan.']);
    }

    public function destroy(string $uuid)
    {
        Jadwal::findOrFail($uuid)->delete();
        return response()->json(['success' => true, 'message' => 'Jadwal dihapus.']);
    }

    public function generate(TimetableGenerator $generator)
    {
        $result = $generator->generate();
        
        $msg = "Jadwal berhasil di-generate. " . $result['placed'] . " slot berhasil disusun.";
        if ($result['unplaced'] > 0) {
            $msg .= " Namun, ada " . $result['unplaced'] . " slot yang gagal disusun karena bentrok parah/kendala waktu.";
        }

        return back()->with('success', $msg);
    }

    public function print($kelasId = null)
    {
        $kelas = $kelasId ? Kelas::where('uuid', $kelasId)->first() : Kelas::orderBy('tingkat')->orderBy('kelas')->first();
        if (!$kelas) return back()->with('error', 'Data kelas tidak ditemukan');

        $jadwals = Jadwal::with(['pelajaran', 'guru'])
            ->where('id_kelas', $kelas->uuid)
            ->orderBy('hari')
            ->orderBy('jam_ke')
            ->get()
            ->groupBy('hari');

        return view('jadwal.print', compact('kelas', 'jadwals'));
    }

    public function export($kelasId = null)
    {
        $kelas = $kelasId ? Kelas::where('uuid', $kelasId)->first() : Kelas::orderBy('tingkat')->orderBy('kelas')->first();
        if (!$kelas) return back()->with('error', 'Data kelas tidak ditemukan');

        $filename = "Jadwal_Kelas_" . $kelas->tingkat . $kelas->kelas . "_" . date('Ymd') . ".xlsx";

        return Excel::download(new JadwalExport($kelasId), $filename);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
            'id_kelas' => 'required|exists:kelas,uuid'
        ]);

        $idKelas = $request->input('id_kelas');
        $file = $request->file('file');

        try {
            Excel::import(new JadwalImport($idKelas), $file);
            $count = session()->pull('import_count', 0);
            return back()->with('success', "Import berhasil. $count baris jadwal ditambahkan.");
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }
}
