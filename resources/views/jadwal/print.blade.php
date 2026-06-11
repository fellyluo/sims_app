<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Kelas {{ $kelas->tingkat }}{{ $kelas->kelas }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .text-center { text-align: center; }
        h2 { margin-bottom: 5px; }
        p { margin-top: 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .slot { margin-bottom: 4px; }
        .pelajaran { font-weight: bold; }
        .guru { font-size: 11px; color: #444; }
        @media print {
            button { display: none; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" style="padding: 10px 20px; margin-bottom: 20px; cursor: pointer;">Cetak Sekarang</button>

    <div class="text-center">
        <h2>Jadwal Pelajaran Kelas {{ $kelas->tingkat }}{{ $kelas->kelas }}</h2>
        <p>{{ $kelas->nama_lengkap ?? '' }}</p>
    </div>

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

    <table>
        <thead>
            <tr>
                <th>Jam Ke-</th>
                @foreach($hari_nama as $h_id => $h_nama)
                    <th>{{ $h_nama }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @for($j=1; $j<=$max_jam; $j++)
            <tr>
                <td>
                    <strong>{{ $j }}</strong>
                    @if(!empty($waktuJadwal[$j]['mulai']) && !empty($waktuJadwal[$j]['selesai']))
                        <br><span style="font-size:10px;color:#666;">{{ $waktuJadwal[$j]['mulai'] }} - {{ $waktuJadwal[$j]['selesai'] }}</span>
                    @endif
                </td>
                @for($h=1; $h<=5; $h++)
                    <td>
                        @if(count($grid[$h][$j]) > 0)
                            @foreach($grid[$h][$j] as $jadwal)
                            <div class="slot">
                                <div class="pelajaran">{{ $jadwal->pelajaran?->nama }}</div>
                                <div class="guru">{{ $jadwal->guru?->nama }}</div>
                            </div>
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                @endfor
            </tr>
            @endfor
        </tbody>
    </table>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
