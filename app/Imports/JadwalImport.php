<?php

namespace App\Imports;

use App\Models\Jadwal;
use App\Models\Pelajaran;
use App\Models\Guru;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class JadwalImport implements ToCollection, WithStartRow
{
    protected $idKelas;

    public function __construct($idKelas)
    {
        $this->idKelas = $idKelas;
    }

    public function collection(Collection $rows)
    {
        $hariMap = ['senin' => 1, 'selasa' => 2, 'rabu' => 3, 'kamis' => 4, 'jumat' => 5];
        $count = 0;

        foreach ($rows as $row) {
            if (!isset($row[0], $row[1], $row[2], $row[4])) continue;

            $hariStr = strtolower(trim((string)$row[0]));
            $jam = (int) trim((string)$row[1]);
            $pelajaranNama = trim((string)$row[2]);
            $guruNama = trim((string)$row[4]);

            if (empty($hariStr) || empty($jam) || empty($pelajaranNama) || empty($guruNama)) continue;

            $hari = $hariMap[$hariStr] ?? null;
            if (!$hari) continue;

            $pelajaran = Pelajaran::where('nama', $pelajaranNama)->first();
            $guru = Guru::where('nama', $guruNama)->first();

            if ($pelajaran && $guru && $this->idKelas) {
                // Hapus jadwal existing di slot itu
                Jadwal::where('id_kelas', $this->idKelas)->where('hari', $hari)->where('jam_ke', $jam)->delete();
                
                Jadwal::create([
                    'id_kelas' => $this->idKelas,
                    'id_pelajaran' => $pelajaran->uuid,
                    'id_guru' => $guru->uuid,
                    'hari' => $hari,
                    'jam_ke' => $jam
                ]);
                $count++;
            }
        }
        
        session()->flash('import_count', $count);
    }

    public function startRow(): int
    {
        return 2; // Skip header
    }
}
