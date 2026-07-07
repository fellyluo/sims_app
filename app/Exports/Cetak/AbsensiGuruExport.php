<?php

namespace App\Exports\Cetak;

use App\Models\PresensiGuru;
use App\Support\CetakExcelStyle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class AbsensiGuruExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithTitle, WithEvents
{
    public const HEADINGS = ['No', 'Nama Guru', 'Tanggal', 'Status', 'Jam Masuk', 'Jam Pulang', 'Keterangan'];

    public function __construct(private string $dari, private string $sampai)
    {
    }

    public function collection()
    {
        return PresensiGuru::with('guru')
            ->whereBetween('tanggal', [$this->dari, $this->sampai])
            ->orderBy('tanggal')
            ->get()
            ->sortBy(fn ($p) => $p->guru?->nama)
            ->values();
    }

    public function headings(): array
    {
        return self::HEADINGS;
    }

    public function title(): string
    {
        return 'Absensi Guru';
    }

    public function map($p): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $p->guru?->nama ?? '-',
            $p->tanggal?->format('d-m-Y'),
            PresensiGuru::STATUS[$p->status] ?? $p->status,
            $p->jam_masuk,
            $p->jam_pulang,
            $p->keterangan,
        ];
    }

    public function columnWidths(): array
    {
        return ['A' => 5, 'B' => 26, 'C' => 14, 'D' => 12, 'E' => 12, 'F' => 12, 'G' => 30];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sub = 'Periode ' . \Carbon\Carbon::parse($this->dari)->format('d-m-Y') . ' s/d ' . \Carbon\Carbon::parse($this->sampai)->format('d-m-Y');
                CetakExcelStyle::kopDanTabel($event->sheet->getDelegate(), 'ABSENSI GURU', count(self::HEADINGS), $this->collection()->count(), $sub);
            },
        ];
    }
}
