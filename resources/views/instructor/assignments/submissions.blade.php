@extends('instructor.layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">
        Submissions - {{ $assignment->title }}
    </h1>


<div class="flex justify-end mb-4">
    <a href="{{ route('instructor.assignments.download-submissions', $assignment->id) }}"
       class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded shadow">
        <i class="fas fa-download mr-1"></i> Download Semua PDF
    </a>
</div>

    @if($assignment->submissions->count() == 0)
        <p class="text-gray-500 dark:text-gray-400 text-center py-8">Belum ada submission.</p>
    @else
        <table class="w-full table-auto border border-gray-200 dark:border-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 border-b">#</th>
                    <th class="px-4 py-2 border-b">Nama Peserta</th>
                    <th class="px-4 py-2 border-b">Link Submission</th>
                    <th class="px-4 py-2 border-b">Nilai</th>
                    <th class="px-4 py-2 border-b">Feedback Admin</th>
                    <th class="px-4 py-2 border-b">Tanggal Submit</th>
                    <th class="px-4 py-2 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assignment->submissions as $s)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-2 border-b">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2 border-b">{{ $s->user->name }}</td>
                    <td class="px-4 py-2 border-b">
                        <a href="{{ $s->submission_link }}" target="_blank" class="text-blue-600 hover:underline">Lihat</a>
                    </td>
<td class="px-4 py-2 border-b">
    <form action="{{ route('instructor.assignments.update-score', $s->id) }}" method="POST" class="flex flex-col gap-1">
        @csrf
        @method('PUT')
        <input type="number" name="score" value="{{ $s->score }}" min="0" max="100" class="w-20 px-2 py-1 border rounded">
        <button type="submit" class="px-2 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-sm">Simpan</button>
    </form>
</td>
<td class="px-4 py-2 border-b">
    <form action="{{ route('instructor.assignments.update-score', $s->id) }}" method="POST">
        @csrf
        @method('PUT')
        <textarea name="feedback" rows="2" class="w-full px-2 py-1 border rounded">{{ $s->admin_feedback }}</textarea>
        <button type="submit" class="mt-1 px-2 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-sm">Simpan Feedback</button>
    </form>
</td>
                    <td class="px-4 py-2 border-b">
    {{ $s->submitted_at ? \Carbon\Carbon::parse($s->submitted_at)->format('d M Y H:i') : '-' }}
</td>

                    <td class="px-4 py-2 border-b">
                        <a href="{{ $s->submission_link }}" target="_blank"
                           class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm">Lihat</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
