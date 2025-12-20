@extends('instructor.layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Buat Ujian Essay Baru</h1>
        <a href="{{ route('instructor.essay.index') }}" class="text-gray-500 hover:text-indigo-600">Kembali</a>
    </div>

    <form action="{{ route('instructor.essay.store') }}" method="POST">
        @csrf

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100 dark:border-gray-700 space-y-6">

            <!-- Judul Ujian -->
            <div>
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Judul Ujian <span class="text-red-500">*</span></label>
                <input type="text" name="title" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" placeholder="Contoh: Ujian Essay Midterm - Semester 1" required>
            </div>

            <!-- Kelas -->
@php
    $kelas = \App\Models\Kelas::orderBy('title')->get();
@endphp

<div>
    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">
        Kelas <span class="text-red-500">*</span>
    </label>

    <select name="kelas_id" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
        @foreach($kelas as $k)
            <option value="{{ $k->id }}">{{ $k->title }}</option>
        @endforeach
    </select>
</div>


            <!-- Durasi -->
            <div>
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Durasi (menit) <span class="text-red-500">*</span></label>
                <input type="number" name="duration_minutes" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" placeholder="Contoh: 120" min="1" required>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-4 border-t dark:border-gray-700 text-right">
                <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow">
                    Simpan Ujian
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
