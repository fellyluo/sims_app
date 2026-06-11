<?php

namespace App\Services;

use App\Models\GuruKetersediaan;
use App\Models\Jadwal;
use App\Models\Ngajar;
use Illuminate\Support\Str;

class TimetableGenerator
{
    private $maxHari = 5;
    private $maxJam = 8;

    public function generate()
    {
        // Clear old schedules
        Jadwal::truncate();

        $ngajars = Ngajar::whereNotNull('id_kelas')->where('jumlah_jam', '>', 0)->get();
        $unavailables = GuruKetersediaan::all()->groupBy('id_guru');
        
        $unavailLookup = [];
        foreach ($unavailables as $id_guru => $records) {
            foreach ($records as $r) {
                $unavailLookup[$id_guru][$r->hari][$r->jam_ke] = true;
            }
        }

        $classSchedule = [];
        $teacherSchedule = [];

        // Inisialisasi Matriks
        $kelasIds = $ngajars->pluck('id_kelas')->unique();
        foreach ($kelasIds as $kId) {
            for ($h = 1; $h <= $this->maxHari; $h++) {
                for ($j = 1; $j <= $this->maxJam; $j++) {
                    $classSchedule[$kId][$h][$j] = null;
                }
            }
        }

        // Siapkan antrean pelajaran (blocks)
        $blocks = [];
        foreach ($ngajars as $ngajar) {
            for ($i = 0; $i < $ngajar->jumlah_jam; $i++) {
                $blocks[] = [
                    'id_kelas' => $ngajar->id_kelas,
                    'id_pelajaran' => $ngajar->id_pelajaran,
                    'id_guru' => $ngajar->id_guru,
                ];
            }
        }

        // Shuffle untuk pendekatan stochastik / greedy randomized
        shuffle($blocks);
        
        $unplaced = [];
        foreach ($blocks as $block) {
            $placed = false;
            
            // Cari slot yang cocok
            $availableSlots = [];
            for ($h = 1; $h <= $this->maxHari; $h++) {
                $subjectCountToday = 0;
                for ($j = 1; $j <= $this->maxJam; $j++) {
                    if (isset($classSchedule[$block['id_kelas']][$h][$j]) && 
                        $classSchedule[$block['id_kelas']][$h][$j]['id_pelajaran'] == $block['id_pelajaran']) {
                        $subjectCountToday++;
                    }
                }
                
                // Max 2 blocks per subject per day
                if ($subjectCountToday >= 2) continue;

                for ($j = 1; $j <= $this->maxJam; $j++) {
                    // Cek bentrok kelas
                    if ($classSchedule[$block['id_kelas']][$h][$j] !== null) continue;
                    // Cek bentrok guru
                    if (isset($teacherSchedule[$block['id_guru']][$h][$j])) continue;
                    // Cek ketersediaan guru
                    if (isset($unavailLookup[$block['id_guru']][$h][$j])) continue;

                    $availableSlots[] = ['hari' => $h, 'jam_ke' => $j];
                }
            }

            if (count($availableSlots) > 0) {
                // Pick a random valid slot
                $slot = $availableSlots[array_rand($availableSlots)];
                $h = $slot['hari'];
                $j = $slot['jam_ke'];

                $classSchedule[$block['id_kelas']][$h][$j] = $block;
                $teacherSchedule[$block['id_guru']][$h][$j] = $block['id_kelas'];
                $placed = true;
            }

            if (!$placed) {
                $unplaced[] = $block;
            }
        }

        // Simpan ke database
        $toInsert = [];
        foreach ($classSchedule as $id_kelas => $days) {
            foreach ($days as $h => $slots) {
                foreach ($slots as $j => $block) {
                    if ($block) {
                        $toInsert[] = [
                            'uuid' => Str::uuid()->toString(),
                            'id_kelas' => $block['id_kelas'],
                            'id_pelajaran' => $block['id_pelajaran'],
                            'id_guru' => $block['id_guru'],
                            'hari' => $h,
                            'jam_ke' => $j,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
        }

        if (count($toInsert) > 0) {
            $chunks = array_chunk($toInsert, 500);
            foreach ($chunks as $chunk) {
                Jadwal::insert($chunk);
            }
        }

        return [
            'success' => true,
            'placed' => count($toInsert),
            'unplaced' => count($unplaced),
        ];
    }
}
