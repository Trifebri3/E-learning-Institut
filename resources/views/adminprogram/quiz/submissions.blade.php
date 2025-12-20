@extends('adminprogram.layouts.app')

@section('title', 'Submissions Quiz')

@section('content')
<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-4">Submissions: {{ $quiz->title }}</h2>

<a href="{{ route('adminprogram.quiz.download', $quiz->id) }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 mb-4 inline-block">
    Download Semua PDF
</a>

    <table class="w-full text-left border">
        <thead>
            <tr class="border-b">
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">Peserta</th>
                <th class="px-4 py-2">Mulai</th>
                <th class="px-4 py-2">Selesai</th>
                <th class="px-4 py-2">Skor</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quiz->quizAttempts as $attempt)
            <tr class="border-b">
                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                <td class="px-4 py-2">{{ $attempt->user->name }}</td>
                <td class="px-4 py-2">{{ $attempt->started_at }}</td>
                <td class="px-4 py-2">{{ $attempt->finished_at ?? '-' }}</td>
                <td class="px-4 py-2">{{ $attempt->score ?? '-' }}</td>
                <td class="px-4 py-2">
                    <form action="{{ route('adminprogram.quiz.updateScore', $attempt->id) }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="number" name="score" value="{{ $attempt->score }}" class="px-2 py-1 border rounded w-20">
                        <input type="text" name="feedback" value="{{ $attempt->admin_feedback }}" class="px-2 py-1 border rounded w-48" placeholder="Feedback">
                        <button type="submit" class="px-2 py-1 bg-indigo-600 text-white rounded">Update</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
