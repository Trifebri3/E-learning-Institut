<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Presensi Program - {{ $program->title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        h1, h2 { text-align: center; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        th { background-color: #f0f0f0; }
        .text-left { text-align: left; }
        .hadir { color: green; font-weight: bold; }
        .parsial { color: orange; }
        .alpha { color: red; }
        .meta { margin-bottom: 20px; font-size: 11px; }
    </style>
</head>
<body onload="window.print()">

    <h1>REKAP KEHADIRAN PESERTA</h1>
    <h2>PROGRAM: {{ strtoupper($program->title) }}</h2>

    <div class="meta">
        <strong>Dicetak Oleh:</strong> {{ Auth::user()->name }}<br>
        <strong>Tanggal Cetak:</strong> {{ now()->translatedFormat('d F Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" width="3%">No</th>
                <th rowspan="2" width="10%">Nomor Induk</th>
                <th rowspan="2" class="text-left">Nama Peserta</th>
                <th colspan="{{ $program->kelas->count() }}">Pertemuan / Kelas</th>
                <th rowspan="2" width="5%">Total Hadir</th>
            </tr>
            <tr>
                @foreach($program->kelas as $index => $k)
                    <th title="{{ $k->title }}">
                        {{ $index + 1 }}<br>
                        <span style="font-size: 8px;">{{ \Carbon\Carbon::parse($k->tanggal)->format('d/m') }}</span>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($participants as $index => $user)
                @php
                    $userPresensi = $presensiData->get($user->id, collect());
                    $totalHadir = 0;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        @foreach($user->nomorInduks as $ni)
                            {{ $ni->nomor_induk }}
                        @endforeach
                    </td>
                    <td class="text-left">{{ $user->name }}</td>

                    @foreach($program->kelas as $kelas)
                        @php
                            $p = $userPresensi->where('kelas_id', $kelas->id)->first();
                            $status = $p ? $p->status_kehadiran : 'alpha';
                            $symbol = '-';

                            if ($status == 'hadir_full') {
                                $symbol = 'H'; // Hadir
                                $class = 'hadir';
                                $totalHadir++;
                            } elseif ($status == 'hadir_awal' || $status == 'hadir_akhir') {
                                $symbol = 'P'; // Parsial
                                $class = 'parsial';
                                $totalHadir += 0.5; // Hitung setengah
                            } else {
                                $symbol = 'A'; // Alpha
                                $class = 'alpha';
                            }
                        @endphp
                        <td class="{{ $class }}">{{ $symbol }}</td>
                    @endforeach

                    <td><strong>{{ $totalHadir }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 10px; font-size: 9px;">
        <strong>Keterangan:</strong>
        <span class="hadir">H = Hadir Penuh</span>,
        <span class="parsial">P = Hadir Sebagian (Awal/Akhir saja)</span>,
        <span class="alpha">A = Tidak Hadir (Alpha)</span>
    </div>

</body>
</html>
