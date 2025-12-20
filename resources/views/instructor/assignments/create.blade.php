@extends('instructor.layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Buat Tugas Baru</h1>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form action="{{ route('instructor.assignments.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-200">Judul Tugas</label>
                <input type="text" name="title" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-200">Deskripsi</label>
                <textarea name="description" class="w-full border rounded px-3 py-2" rows="4"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-200">Pilih Kelas</label>
<select name="kelas_id" class="w-full border rounded px-3 py-2" required>
    @foreach($kelasList as $k)
        <option value="{{ $k->id }}">
            {{ $k->title ?? $k->name }} (Program: {{ $k->program->title }})
        </option>
    @endforeach
</select>

            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-200">Deadline</label>
                <input type="datetime-local" name="due_date" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-200">Durasi (menit)</label>
                <input type="number" name="duration_minutes" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-200">Publikasi</label>
                <select name="is_published" class="w-full border rounded px-3 py-2">
                    <option value="1">Published</option>
                    <option value="0">Draft</option>
                </select>
            </div>

            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded shadow">
                Simpan Tugas
            </button>
        </form>
    </div>
</div>
@endsection
