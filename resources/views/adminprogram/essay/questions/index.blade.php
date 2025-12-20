@extends('adminprogram.layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Kelola Soal Essay: <span class="text-indigo-600 dark:text-indigo-400">{{ $exam->title }}</span></h1>
        <a href="{{ route('adminprogram.essay.index') }}" class="text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400">
            ← Kembali ke Daftar Ujian
        </a>
    </div>

    <!-- Form Tambah Soal Baru -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-100 dark:border-gray-700 mb-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Tambah Soal Baru</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('adminprogram.essay.questions.store', $exam->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Isi Soal <span class="text-red-500">*</span></label>
                    <textarea name="question_text" rows="3" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white"
                              placeholder="Tulis pertanyaan essay di sini..." required></textarea>
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow">
                    + Tambah Soal
                </button>
            </form>
        </div>
    </div>

    <!-- Daftar Soal -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-100 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Daftar Soal ({{ $exam->questions->count() }})</h2>
        </div>

        <div class="p-6">
            @if($exam->questions->count() == 0)
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    <p>Belum ada soal untuk ujian ini.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($exam->questions as $q)
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                        <form action="{{ route('adminprogram.essay.questions.update', $q->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Soal #{{ $loop->iteration }}</label>
                            </div>
                            <textarea name="question_text" rows="2" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">{{ $q->question_text }}</textarea>
                            <div class="flex space-x-2">
                                <button type="submit" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded shadow">
                                    Simpan Perubahan
                                </button>
                                <button type="button"
                                        onclick="if(confirm('Hapus soal ini?')) document.getElementById('delete-{{ $q->id }}').submit()"
                                        class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded shadow">
                                    Hapus
                                </button>
                            </div>
                        </form>

                        <form id="delete-{{ $q->id }}" action="{{ route('adminprogram.essay.questions.delete', $q->id) }}" method="POST" class="hidden">
                            @csrf @method('DELETE')
                        </form>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
