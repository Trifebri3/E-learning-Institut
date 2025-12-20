<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .title { text-align:center; font-size:18px; font-weight:bold; margin-bottom:10px; }
        table { width:100%; border-collapse:collapse; margin-top:10px; }
        th, td { border:1px solid #444; padding:6px; text-align:center; }
        th { background:#e5e5e5; }
    </style>
</head>
<body>

<div class="title">REKAP PROGRESS PROGRAM<br>{{ $program->name }}</div>

<p><strong>Nama Peserta:</strong> {{ $user->name }}</p>
<p><strong>Email:</strong> {{ $user->email }}</p>
<p><strong>Tanggal:</strong> {{ now()->format('d M Y') }}</p>

<table>
    <thead>
        <tr>
            <th>Kelas</th>
            <th>Tugas</th>
            <th>Quiz</th>
            <th>Progress (%)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($kelasData as $k)
        <tr>
            <td>{{ $k['nama_kelas'] }}</td>
            <td>{{ $k['tugas'] }}</td>
            <td>{{ $k['quiz'] }}</td>
            <td>{{ $k['progress'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
