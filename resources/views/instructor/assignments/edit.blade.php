@extends('instructor.layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Edit Tugas</h1>

    <form action="{{ route('instructor.assignments.update', $assignment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700">Judul</label>
            <input type="text" name="title" value="{{ old('title', $assignment->title) }}"
                   class="w-full px-3 py-2 border rounded">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Deskripsi</label>
            <textarea name="description" class="w-full px-3 py-2 border rounded">{{ old('description', $assignment->description) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Kelas</label>
            <select name="kelas_id" class="w-full px-3 py-2 border rounded">
                @foreach($kelasList as $kelas)
                    <option value="{{ $kelas->id }}" {{ $assignment->kelas_id == $kelas->id ? 'selected' : '' }}>
                        {{ $kelas->program->title }} - {{ $kelas->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Deadline</label>
<input type="datetime-local" name="due_date"
       value="{{ old('due_date', \Carbon\Carbon::parse($assignment->due_date)->format('Y-m-d\TH:i')) }}"
       class="w-full px-3 py-2 border rounded">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Publikasi</label>
            <select name="is_published" class="w-full px-3 py-2 border rounded">
                <option value="1" {{ $assignment->is_published ? 'selected' : '' }}>Ya</option>
                <option value="0" {{ !$assignment->is_published ? 'selected' : '' }}>Tidak</option>
            </select>
        </div>



        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Simpan Perubahan</button>
    </form>
</div>
@endsection
