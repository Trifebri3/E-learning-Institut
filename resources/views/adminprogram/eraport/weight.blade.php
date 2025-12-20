@extends('adminprogram.layouts.app')

@section('content')
<h1 class="text-xl font-bold mb-4">Setting Bobot - {{ $kelas->title }}</h1>

<form action="{{ route('adminprogram.eraport.updateWeight', ['programId' => $kelas->program_id, 'kelasId' => $kelas->id]) }}" method="POST">
    @csrf
    <table class="table-auto border">
        <tr><th>Komponen</th><th>Bobot (%)</th></tr>
        <tr><td>Presensi</td><td><input type="number" name="weight_presensi" value="{{ $weight->weight_presensi }}" min="0" max="100"></td></tr>
        <tr><td>Tugas</td><td><input type="number" name="weight_tugas" value="{{ $weight->weight_tugas }}" min="0" max="100"></td></tr>
        <tr><td>Quiz</td><td><input type="number" name="weight_quiz" value="{{ $weight->weight_quiz }}" min="0" max="100"></td></tr>
        <tr><td>Essay</td><td><input type="number" name="weight_essay" value="{{ $weight->weight_essay }}" min="0" max="100"></td></tr>
        <tr><td>Progress</td><td><input type="number" name="weight_progress" value="{{ $weight->weight_progress }}" min="0" max="100"></td></tr>
        <tr><td>Custom</td><td><input type="number" name="weight_custom" value="{{ $weight->weight_custom }}" min="0" max="100"></td></tr>
    </table>
    <button type="submit" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
</form>

@endsection
