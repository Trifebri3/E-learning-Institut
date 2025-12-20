@extends('adminprogram.layouts.app')

@section('content')
<h1 class="text-xl font-bold mb-4">Tambah Kolom Custom - {{ $kelas->title }}</h1>

<form action="{{ route('adminprogram.eraport.storeCustomColumn', ['programId' => $kelas->program_id, 'kelasId' => $kelas->id]) }}" method="POST">
    @csrf
    <label>Nama Kolom</label>
    <input type="text" name="name" class="border p-2 w-64" required>
    <button type="submit" class="mt-2 px-4 py-2 bg-green-600 text-white rounded">Tambah</button>
</form>

@endsection
