<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #e3e3e3;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

<h2>Daftar Seluruh Submission – {{ $exam->title }}</h2>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Peserta</th>
            <th>Nomor Induk</th>
            <th>Status</th>
            <th>Nilai Akhir</th>
            <th>Dikirim Pada</th>
        </tr>
    </thead>

    <tbody>
    @foreach($submissions as $s)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $s->user->name }}</td>
            <td>{{ $s->user->nomorInduk->nomor_induk ?? '-' }}</td>
            <td>{{ $s->status }}</td>
            <td>{{ $s->final_score ?? 'Belum dinilai' }}</td>
            <td>
    @if($s->submitted_at)
        {{ \Carbon\Carbon::parse($s->submitted_at)->format('d/m/Y H:i') }}
    @else
        -
    @endif
</td>

        </tr>
    @endforeach
    </tbody>

</table>

</body>
</html>
