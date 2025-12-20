@extends('adminprogram.layouts.app')

@section('content')
<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-4">Soal Quiz: {{ $quiz->title }}</h2>

    <a href="{{ route('adminprogram.quiz.soal.create', $quiz->id) }}"
       class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 mb-4 inline-block">
       Tambah Soal Baru
    </a>

    @if($questions->count())
    <table class="w-full text-left border">
        <thead>
            <tr class="border-b">
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">Pertanyaan</th>
                <th class="px-4 py-2">Jawaban</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($questions as $index => $question)
            <tr class="border-b">
                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                <td class="px-4 py-2">{{ $question->question_text }}</td>
                <td class="px-4 py-2">
                    <ul class="list-disc ml-5">
                        @foreach($question->answers as $ans)
                        <li>
                            {{ $ans->text }}
                            @if($ans->is_correct) <strong>(Benar)</strong> @endif
                        </li>
                        @endforeach
                    </ul>
                </td>
                <td class="px-4 py-2 flex gap-2">
                    <a href="{{ route('adminprogram.quiz.soal.edit', [$quiz->id, $question->id]) }}"
                       class="px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Edit</a>
                    <form action="{{ route('adminprogram.quiz.soal.destroy', [$quiz->id, $question->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus soal?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p class="text-gray-500">Belum ada soal untuk quiz ini.</p>
    @endif
</div>
@endsection
