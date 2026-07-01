<?php

namespace App\Sarpras\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Sarpras\Models\BookingRuangan;
use App\Sarpras\Models\DenahRuangan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

/**
 * Ruangan & Booking — daftar ruangan (status, fasilitas, kapasitas),
 * pengajuan pemakaian ruangan, & persetujuan (Wakasek Sarpras).
 *
 * Booking ruangan terintegrasi dengan tabel sarpras_booking_ruangan
 * (status: diajukan → disetujui/ditolak; deteksi bentrok via scopeBentrok).
 */
class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $canApprove = $user->can('sarpras.booking.kelola');

        // Ringkasan jumlah ruangan per status.
        $summary = collect(DenahRuangan::STATUS)->mapWithKeys(fn ($l, $k) => [
            $k => DenahRuangan::where('status', $k)->count(),
        ]);

        // Kartu ruangan (filter status opsional).
        $rooms = DenahRuangan::with('denah:id,nama')
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->orderBy('kode')->get();

        // Booking: approver lihat semua; selain itu lihat miliknya sendiri.
        $base = BookingRuangan::with(['ruangan:id,kode,nama,gedung,lantai', 'pemohon'])
            ->when(! $canApprove, fn ($q) => $q->where('pemohon_id', $user->uuid));

        $pending = $canApprove
            ? (clone $base)->where('status', 'diajukan')->orderBy('mulai')->get()
            : collect();

        $bookings = (clone $base)->latest('mulai')->limit(20)->get();

        return view('sarpras.booking.index', [
            'rooms'      => $rooms,
            'summary'    => $summary,
            'pending'    => $pending,
            'bookings'   => $bookings,
            'allRooms'   => DenahRuangan::orderBy('kode')->get(['id', 'kode', 'nama']),
            'canApprove' => $canApprove,
            'statusFilter' => (string) $request->status,
        ]);
    }

    /** Ajukan pemakaian ruangan. */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ruangan_id'  => ['required', 'uuid', 'exists:sarpras_denah_ruangan,id'],
            'keperluan'   => ['required', 'string', 'max:255'],
            'tanggal'     => ['required', 'date'],
            'jam_mulai'   => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
        ], [
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
        ]);

        $mulai   = Carbon::parse($data['tanggal'] . ' ' . $data['jam_mulai']);
        $selesai = Carbon::parse($data['tanggal'] . ' ' . $data['jam_selesai']);

        // Deteksi jadwal bentrok di ruangan & waktu yang sama.
        if (BookingRuangan::bentrok($data['ruangan_id'], $mulai, $selesai)->exists()) {
            return back()->withInput()->with('gagal', 'Jadwal bentrok dengan booking lain di ruangan & waktu tersebut.');
        }

        BookingRuangan::create([
            'ruangan_id' => $data['ruangan_id'],
            'pemohon_id' => auth()->id(),
            'keperluan'  => $data['keperluan'],
            'mulai'      => $mulai,
            'selesai'    => $selesai,
            'status'     => 'diajukan',
        ]);

        return redirect()->route('sarpras.booking.index')
            ->with('sukses', 'Pengajuan pemakaian ruangan terkirim, menunggu persetujuan.');
    }

    /** Setujui booking (cek ulang bentrok dengan yang sudah disetujui). */
    public function setujui(BookingRuangan $booking): RedirectResponse
    {
        $bentrok = BookingRuangan::bentrok($booking->ruangan_id, $booking->mulai, $booking->selesai, $booking->id)
            ->where('status', 'disetujui')->exists();
        if ($bentrok) {
            return back()->with('gagal', 'Tidak bisa disetujui: bentrok dengan booking lain yang sudah disetujui.');
        }

        $booking->update(['status' => 'disetujui']);

        return back()->with('sukses', 'Booking ruangan disetujui.');
    }

    /** Tolak booking. */
    public function tolak(BookingRuangan $booking): RedirectResponse
    {
        $booking->update(['status' => 'ditolak']);

        return back()->with('sukses', 'Booking ruangan ditolak.');
    }
}
