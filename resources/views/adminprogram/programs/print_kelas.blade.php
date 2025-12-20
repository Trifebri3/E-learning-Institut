<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Presensi Kelas - {{ $kelas->title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        h1, h2, h3 { text-align: center; margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background-color: #eee; text-align: center; }
        .text-center { text-align: center; }
        .meta { margin-bottom: 20px; border-bottom: 1px solid #000; padding-bottom: 10px; }
    </style>
</head>
<body onload="window.print()">

    <h1>DAFTAR HADIR PESERTA</h1>
    <h2>{{ $kelas->program->title }}</h2>
    <h3>Kelas: {{ $kelas->title }}</h3>

    <div class="meta">
        <table>
            <tr>
                <td style="border:none; width: 15%;"><strong>Tanggal</strong></td>
                <td style="border:none; width: 35%;">: {{ \Carbon\Carbon::parse($kelas->tanggal)->translatedFormat('l, d F Y') }}</td>
                <td style="border:none; width: 15%;"><strong>Waktu</strong></td>
                <td style="border:none;">: {{ $kelas->jam_mulai }} WIB</td>
            </tr>
            <tr>
                <td style="border:none;"><strong>Lokasi</strong></td>
                <td style="border:none;">: {{ $kelas->tempat }}</td>
                <td style="border:none;"><strong>Instruktur</strong></td>
                <td style="border:none;">:
                    @foreach($kelas->narasumbers as $n) {{ $n->nama }}, @endforeach
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Nomor Induk</th>
                <th width="30%">Nama Peserta</th>
                <th width="15%">Jam Masuk (Awal)</th>
                <th width="15%">Jam Keluar (Akhir)</th>
                <th width="20%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($participants as $index => $user)
                @php
                    $p = $presensi->get($user->id);
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">
                        @foreach($user->nomorInduks as $ni) {{ $ni->nomor_induk }} @endforeach
                    </td>
                    <td>{{ $user->name }}</td>

                    <td class="text-center">
                        @if($p && $p->waktu_presensi_awal)
                            {{ \Carbon\Carbon::parse($p->waktu_presensi_awal)->format('H:i') }}
                        @else - @endif
                    </td>

                    <td class="text-center">
                        @if($p && $p->waktu_presensi_akhir)
                            {{ \Carbon\Carbon::parse($p->waktu_presensi_akhir)->format('H:i') }}
                        @else - @endif
                    </td>

                    <td class="text-center">
                        @if(!$p) Alpha
                        @elseif($p->status_kehadiran == 'hadir_full') Hadir
                        @else Parsial @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; float: right; width: 200px; text-align: center;">
        <p>Mengetahui,<br>Admin Program</p>
        <br><br><br>
        <p>_______________________</p>
    </div>

</body>
</html>
