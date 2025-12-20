@extends('instructor.layouts.app')

@section('title', 'Buat Pengumuman Program')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Buat Pengumuman Program</h1>
        <a href="{{ route('instructor.announcements.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 shadow transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 p-6">
        <form action="{{ route('instructor.announcements.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-200 font-bold mb-2">Program</label>
                <select name="program_id" class="w-full border rounded px-3 py-2">
                    @foreach($programs as $program)
                        <option value="{{ $program->id }}">{{ $program->title }}</option>
                    @endforeach
                </select>
                @error('program_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-200 font-bold mb-2">Judul</label>
                <input type="text" name="title" class="w-full border rounded px-3 py-2" value="{{ old('title') }}">
                @error('title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-200 font-bold mb-2">Konten</label>
                <textarea name="content" class="w-full border rounded px-3 py-2" rows="5">{{ old('content') }}</textarea>
                @error('content') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-200 font-bold mb-2">Prioritas</label>
                <select name="priority" class="w-full border rounded px-3 py-2">
                    <option value="normal" {{ old('priority')=='normal' ? 'selected' : '' }}>Normal</option>
                    <option value="important" {{ old('priority')=='important' ? 'selected' : '' }}>Info</option>
                    <option value="critical" {{ old('priority')=='critical' ? 'selected' : '' }}>Penting</option>
                </select>
                @error('priority') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-200 font-bold mb-2">Lampiran (Opsional)</label>
                <input type="file" name="attachment">
                @error('attachment') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 shadow transition">
                <i class="fas fa-paper-plane mr-2"></i> Terbitkan
            </button>
        </form>
    </div>
</div>
@endsection
