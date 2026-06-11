<?php

namespace App\Exports;

use App\Models\Jadwal;
use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class JadwalExport implements FromCollection, WithHeadings, WithMapping
{
    protected $kelasId;

    public function __construct($kelasId = null)
    {
        $this->kelasId = $kelasId;
    }

    public function collection()
    {
        $kelas = $this->kelasId ? Kelas::where('uuid', $this->kelasId)->first() : Kelas::orderBy('tingkat')->orderBy('kelas')->first();
        if (!$kelas) return collect([]);

        return Jadwal::with(['pelajaran', 'guru'])
            ->where('id_kelas', $kelas->uuid)
            ->orderBy('hari')
            ->orderBy('jam_ke')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Hari',
            'Jam Ke',
            'Pelajaran',
            'Kode Pelajaran',
            'Guru',
            'NIP/NIK Guru'
        ];
    }

    public function map($jadwal): array
    {
        $hariArr = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat'];
        return [
            $hariArr[$jadwal->hari] ?? $jadwal->hari,
            $jadwal->jam_ke,
            $jadwal->pelajaran?->nama,
            $jadwal->pelajaran?->kode,
            $jadwal->guru?->nama,
            $jadwal->guru?->nip ?? $jadwal->guru?->nik
        ];
    }
}
