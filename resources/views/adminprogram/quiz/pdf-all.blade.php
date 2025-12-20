<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Submissions Quiz - {{ $quiz->title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Submissions Quiz - {{ $quiz->title }}</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nomor Induk</th>
                <th>Nama Peserta</th>
                <th>Score</th>
                <th>Waktu Submit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quiz->quizAttempts as $i => $attempt)
            <tr>
                <td>{{ $i + 1 }}</td>
                            <td>{{ $attempt->user->nomorInduk->nomor_induk ?? '-' }}</td>
                <td>{{ $attempt->user->name ?? 'N/A' }}</td>
                <td>{{ $attempt->score ?? '-' }}</td>
                <td>{{ $attempt->finished_at ? $attempt->finished_at->format('d M Y H:i') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
