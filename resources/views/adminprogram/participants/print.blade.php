<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; font-size: 18px; margin-bottom: 5px; }
        .meta { text-align: center; margin-bottom: 20px; font-size: 11px; color: #666; }
        .status-active { color: green; }
        .status-inactive { color: red; }
    </style>
</head>
<body onload="window.print()">

    <h1>{{ $title }}</h1>
    <div class="meta">Dicetak pada: {{ now()->format('d M Y H:i') }} oleh {{ Auth::user()->name }}</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peserta</th>
                <th>Email</th>
                <th>Nomor HP</th>
                <th>Program</th>
                <th>Nomor Induk</th>
                <th>Tanggal Daftar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($participants as $index => $user)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->profile->nomor_hp ?? '-' }}</td>
                <td>
                    @foreach($user->programs as $p)
                        {{ $p->title }}<br>
                    @endforeach
                </td>
                <td>
                    @foreach($user->nomorInduks as $ni)
                        @if($ni->program_id)
                            {{ $ni->nomor_induk }}<br>
                        @endif
                    @endforeach
                </td>
                <td>{{ $user->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
