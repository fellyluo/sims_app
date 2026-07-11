<?php

namespace App\Http\Controllers;

use App\Models\Langganan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Manajemen lisensi langganan SIMS (single-tenant, satu lisensi berjalan).
 * Seluruh route dibatasi role:superadmin di routes/web.php.
 */
class LanggananController extends Controller
{
    public function index()
    {
        $langganan = Langganan::current();

        return view('langganan.index', compact('langganan'));
    }

    /** Tetapkan (atau tetapkan ulang) masa langganan dari tanggal mulai. */
    public function store(Request $request)
    {
        $data = $request->validate([
            'durasi_bulan' => ['required', 'integer', 'in:3,6,12'],
            'mulai_pada' => ['required', 'date'],
            'paket' => ['nullable', 'in:dasar,pro,enterprise'],
            'catatan' => ['nullable', 'string', 'max:2000'],
        ], [
            'durasi_bulan.in' => 'Durasi hanya boleh 3, 6, atau 12 bulan.',
        ]);

        $mulai = Carbon::parse($data['mulai_pada'])->startOfDay();
        // Kalender nyata (addMonths), BUKAN 30×bulan — akurat di bulan 31 hari & Februari.
        $berakhir = $mulai->copy()->addMonths((int) $data['durasi_bulan']);

        DB::transaction(function () use ($data, $mulai, $berakhir) {
            $langganan = Langganan::current();

            $atribut = [
                'paket' => $data['paket'] ?? null,
                'durasi_bulan' => (int) $data['durasi_bulan'],
                'mulai_pada' => $mulai->toDateString(),
                'berakhir_pada' => $berakhir->toDateString(),
                'status' => 'aktif',
                'catatan' => $data['catatan'] ?? null,
                'diatur_oleh' => auth()->id(),
            ];

            $langganan ? $langganan->update($atribut) : Langganan::create($atribut);
        });

        return redirect()->route('langganan.index')
            ->with('success', "Langganan ditetapkan: {$data['durasi_bulan']} bulan, berakhir {$berakhir->translatedFormat('d F Y')}.");
    }

    /**
     * Perpanjang: tambah durasi ke berakhir_pada bila masih aktif,
     * atau hitung dari hari ini bila sudah lewat.
     */
    public function perpanjang(Request $request)
    {
        $data = $request->validate([
            'durasi_bulan' => ['required', 'integer', 'in:3,6,12'],
            'catatan' => ['nullable', 'string', 'max:2000'],
        ], [
            'durasi_bulan.in' => 'Durasi hanya boleh 3, 6, atau 12 bulan.',
        ]);

        $langganan = Langganan::current();
        if (! $langganan) {
            return redirect()->route('langganan.index')
                ->with('error', 'Belum ada langganan — tetapkan masa langganan terlebih dahulu.');
        }

        DB::transaction(function () use ($langganan, $data) {
            $basis = $langganan->kadaluarsa()
                ? now()->startOfDay()
                : $langganan->berakhir_pada->copy()->startOfDay();

            $langganan->update([
                'durasi_bulan' => (int) $data['durasi_bulan'],
                'berakhir_pada' => $basis->addMonths((int) $data['durasi_bulan'])->toDateString(),
                'status' => 'aktif',
                'catatan' => $data['catatan'] ?? $langganan->catatan,
                'diatur_oleh' => auth()->id(),
            ]);
        });

        return redirect()->route('langganan.index')
            ->with('success', "Langganan diperpanjang {$data['durasi_bulan']} bulan, berakhir {$langganan->fresh()->berakhir_pada->translatedFormat('d F Y')}.");
    }
}
