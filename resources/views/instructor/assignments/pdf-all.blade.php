<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Semua Submissions - {{ $assignment->title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h2>Submissions Assignment: {{ $assignment->title }}</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peserta</th>
                <th>Link Submission</th>
                <th>Score</th>
                <th>Feedback</th>
            </tr>
        </thead>
        <tbody>
            @foreach($submissions as $i => $s)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $s->user->name }}</td>
                <td>{{ $s->submission_link }}</td>
                <td>{{ $s->score ?? '-' }}</td>
                <td>{{ $s->admin_feedback ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
