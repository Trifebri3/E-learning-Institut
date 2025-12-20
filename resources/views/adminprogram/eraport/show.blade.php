@extends('adminprogram.layouts.app')

@section('content')
<h1 class="text-xl font-bold mb-4">Input Nilai - {{ $kelas->title }}</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('adminprogram.eraport.storeScore', [$kelas->program_id, $kelas->id]) }}" method="POST">
    @csrf
    <div class="overflow-x-auto">
        <table class="w-full table-auto border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-2 py-1">Peserta</th>
                    <th class="border px-2 py-1">Presensi</th>
                    <th class="border px-2 py-1">Tugas</th>
                    <th class="border px-2 py-1">Quiz</th>
                    <th class="border px-2 py-1">Essay</th>
                    <th class="border px-2 py-1">Progress</th>
                    @foreach($kelas->customColumns as $column)
                        <th class="border px-2 py-1">{{ $column->name }}</th>
                    @endforeach
                    <th class="border px-2 py-1">Passed</th>
                    <th class="border px-2 py-1">Feedback</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kelas->participants as $participant)
                    @php
                        $report = $reports[$participant->id] ?? null;
                    @endphp
                    <tr>
                        <td class="border px-2 py-1">{{ $participant->name }}</td>
                        <td class="border px-2 py-1">
                            <input type="number" name="presensi_{{ $participant->id }}" value="{{ $report->score_presensi ?? 0 }}" min="0" max="100" class="w-16 px-1 py-0.5 border rounded">
                        </td>
                        <td class="border px-2 py-1">
                            <input type="number" name="tugas_{{ $participant->id }}" value="{{ $report->score_tugas ?? 0 }}" min="0" max="100" class="w-16 px-1 py-0.5 border rounded">
                        </td>
                        <td class="border px-2 py-1">
                            <input type="number" name="quiz_{{ $participant->id }}" value="{{ $report->score_quiz ?? 0 }}" min="0" max="100" class="w-16 px-1 py-0.5 border rounded">
                        </td>
                        <td class="border px-2 py-1">
                            <input type="number" name="essay_{{ $participant->id }}" value="{{ $report->score_essay ?? 0 }}" min="0" max="100" class="w-16 px-1 py-0.5 border rounded">
                        </td>
                        <td class="border px-2 py-1">
                            <input type="number" name="progress_{{ $participant->id }}" value="{{ $report->score_progress ?? 0 }}" min="0" max="100" class="w-16 px-1 py-0.5 border rounded">
                        </td>
                        @foreach($kelas->customColumns as $column)
                            @php
                                $key = $participant->id . '-' . $column->id;
                                $customValue = $customValues[$key][0]->score ?? 0;
                            @endphp
                            <td class="border px-2 py-1">
                                <input type="number" name="custom[{{ $participant->id }}][{{ $column->id }}]" value="{{ $customValue }}" min="0" max="100" class="w-16 px-1 py-0.5 border rounded">
                            </td>
                        @endforeach
                        <td class="border px-2 py-1 text-center">
                            <input type="checkbox" name="passed_{{ $participant->id }}" value="1" {{ ($report->is_passed ?? false) ? 'checked' : '' }}>
                        </td>
                        <td class="border px-2 py-1">
                            <input type="text" name="feedback_{{ $participant->id }}" value="{{ $report->feedback ?? '' }}" class="w-full px-1 py-0.5 border rounded">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Tombol redirect ke halaman input nilai -->
<a href="{{ route('adminprogram.eraport.editScore', [$kelas->program_id, $kelas->id]) }}"
   class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
    Input Nilai Kelas
</a>


    <div class="mt-4 flex justify-end gap-2">
        <a href="{{ route('adminprogram.eraport.editWeight', [$kelas->program_id, $kelas->id]) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit Bobot</a>
        <a href="{{ route('adminprogram.eraport.createCustomColumn', [$kelas->program_id, $kelas->id]) }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Tambah Kolom Custom</a>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan Semua Nilai</button>
    </div>

</form>
@endsection
