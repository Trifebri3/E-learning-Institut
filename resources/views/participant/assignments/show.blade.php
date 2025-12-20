@extends('participant.layouts.app')

@section('title', $assignment->title)

@section('content')
<div class="container mx-auto p-4 md:p-6 lg:p-8 max-w-7xl">

    <div class="mb-6">
        <a href="{{ route('participant.assignments.index') }}"
           class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary-600 transition-colors">
            <i class="fas fa-arrow-left mr-2 text-xs"></i> Kembali ke Daftar Tugas
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-8">

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2 leading-tight">
                                {{ $assignment->title }}
                            </h1>
                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 gap-2">
                                <i class="fas fa-door-open"></i>
                                <span>{{ $assignment->kelas->title }}</span>
                                <span class="mx-1">•</span>
                                <span>{{ $assignment->kelas->program->title }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col items-end flex-shrink-0">
                            <span class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-1">Poin</span>
                            <span class="text-xl font-bold text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/30 px-3 py-1 rounded-lg border border-primary-100 dark:border-primary-800">
                                {{ $assignment->max_points }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6 border-b border-gray-100 dark:border-gray-700">
                    @php
                        $isOverdue = \Carbon\Carbon::now()->greaterThan($assignment->due_date);
                    @endphp
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500">
                            <i class="far fa-calendar-alt text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold">Batas Waktu</p>
                            <p class="font-semibold {{ ($isOverdue && !$submission) ? 'text-red-600' : 'text-gray-800 dark:text-gray-200' }}">
                                {{ \Carbon\Carbon::parse($assignment->due_date)->translatedFormat('l, d F Y - H:i') }} WIB
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500">
                            <i class="fas fa-info-circle text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold">Status Anda</p>
                            @if($submission)
                                <span class="text-green-600 font-bold text-sm">Sudah Dikumpulkan</span>
                            @elseif($isOverdue)
                                <span class="text-red-600 font-bold text-sm">Terlewat / Ditutup</span>
                            @else
                                <span class="text-gray-600 dark:text-gray-300 font-bold text-sm">Belum Dikumpulkan</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-align-left text-primary-500"></i> Instruksi
                    </h2>
                    <div class="prose prose-sm md:prose-base dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 leading-relaxed">
                        {!! nl2br(e($assignment->description)) !!}
                    </div>
                </div>
            </div>

            @if($submission)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-clipboard-check text-primary-500"></i> Detail Pengumpulan
                        </h2>

                        <span class="px-3 py-1 rounded-full text-xs font-bold border
                            {{ $submission->is_late
                                ? 'bg-red-50 text-red-600 border-red-100'
                                : 'bg-green-50 text-green-600 border-green-100' }}">
                            {{ $submission->is_late ? 'Terlambat' : 'Tepat Waktu' }}
                        </span>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-5 border border-gray-100 dark:border-gray-700 mb-6 space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Waktu Kirim</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($submission->submitted_at)->translatedFormat('d F Y, H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-start gap-4">
                            <span class="text-gray-500 flex-shrink-0">Link Tugas</span>
                            <a href="{{ $submission->submission_link }}" target="_blank" class="font-medium text-primary-600 hover:underline break-all text-right">
                                {{ $submission->submission_link }} <i class="fas fa-external-link-alt text-xs ml-1"></i>
                            </a>
                        </div>
                        @if($submission->notes)
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-2 mt-2">
                            <span class="block text-gray-500 text-xs mb-1">Catatan Anda:</span>
                            <p class="text-gray-700 dark:text-gray-300 italic">"{!! nl2br(e($submission->notes)) !!}"</p>
                        </div>
                        @endif
                    </div>

                    <div class="rounded-xl border-2 {{ $submission->is_graded ? 'border-primary-100 bg-primary-50/30 dark:border-primary-900 dark:bg-primary-900/20' : 'border-dashed border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800' }} p-5 text-center">
                        @if($submission->is_graded)
                            <p class="text-sm text-gray-500 uppercase tracking-widest font-bold mb-2">Nilai Akhir</p>
                            <div class="text-4xl font-extrabold text-primary-700 dark:text-primary-400 mb-3">
                                {{ $submission->score }} <span class="text-xl text-gray-400 font-medium">/ {{ $assignment->max_points }}</span>
                            </div>

                            @if($submission->feedback)
                                <div class="mt-4 pt-4 border-t border-primary-200 dark:border-primary-800 text-left">
                                    <p class="text-xs font-bold text-primary-800 dark:text-primary-300 mb-1">Feedback Instruktur:</p>
                                    <p class="text-gray-700 dark:text-gray-300 text-sm">{!! nl2br(e($submission->feedback)) !!}</p>
                                </div>
                            @else
                                <p class="text-xs text-gray-400 mt-2">Belum ada catatan feedback.</p>
                            @endif
                        @else
                            <div class="py-4">
                                <i class="fas fa-hourglass-half text-3xl text-gray-300 mb-3 block"></i>
                                <span class="font-bold text-gray-600 dark:text-gray-300">Menunggu Penilaian</span>
                                <p class="text-xs text-gray-400 mt-1">Tugas Anda sedang diperiksa oleh instruktur.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="lg:col-span-1">
            <div class="sticky top-24">

                @if($submission)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-green-200 dark:border-green-900 p-6 text-center">
                        <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4 text-green-600">
                            <i class="fas fa-check text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Tugas Selesai</h3>
                        <p class="text-sm text-gray-500 mb-4">Anda telah berhasil mengumpulkan tugas ini.</p>
                        <button disabled class="w-full py-2.5 bg-gray-100 text-gray-400 font-bold rounded-xl cursor-not-allowed">
                            Dikumpulkan
                        </button>
                    </div>

                @elseif($isOverdue)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-red-200 dark:border-red-900 p-6 text-center">
                        <div class="w-16 h-16 bg-red-50 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-4 text-red-500">
                            <i class="fas fa-lock text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-red-600 dark:text-red-400 mb-1">Pengumpulan Ditutup</h3>
                        <p class="text-sm text-gray-500 mb-4">Maaf, batas waktu pengumpulan tugas ini telah berakhir.</p>

                        <div class="bg-red-50 dark:bg-red-900/10 p-3 rounded-lg border border-red-100 dark:border-red-800/50">
                            <p class="text-xs text-red-700 dark:text-red-300 font-medium">
                                Deadline: {{ \Carbon\Carbon::parse($assignment->due_date)->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>

                @else
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-primary-100 dark:border-gray-700 p-6 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1 bg-primary-500"></div>

                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-paper-plane text-primary-500"></i> Form Pengumpulan
                        </h3>

                        @if (session('error'))
                            <div class="p-3 bg-red-50 text-red-600 rounded-lg text-xs font-bold mb-4 border border-red-100">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('participant.assignments.submit', $assignment->id) }}" class="space-y-4">
                            @csrf

                            <div>
                                <label for="submission_link" class="block text-xs font-bold text-gray-500 uppercase mb-1">Link Tugas <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                        <i class="fas fa-link text-sm"></i>
                                    </span>
                                    <input id="submission_link" name="submission_link" type="url" required placeholder="https://..."
                                           class="pl-9 block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 transition-all text-sm py-2.5">
                                </div>
                                @error('submission_link') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="notes" class="block text-xs font-bold text-gray-500 uppercase mb-1">Catatan Tambahan</label>
                                <textarea id="notes" name="notes" rows="3" placeholder="Pesan untuk instruktur..."
                                          class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 transition-all text-sm"></textarea>
                                @error('notes') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <button type="submit" class="w-full px-4 py-3 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 flex justify-center items-center gap-2">
                                <span>Kirim Tugas</span>
                                <i class="fas fa-arrow-right"></i>
                            </button>

                            <p class="text-[10px] text-center text-gray-400 mt-2">
                                Pastikan link dapat diakses oleh publik/instruktur.
                            </p>
                        </form>
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>
@endsection
