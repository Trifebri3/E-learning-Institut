@extends('participant.layouts.app')

@section('title', $quiz->title . ' - Detail Quiz')

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8 border-b border-gray-200 dark:border-gray-700 pb-6">
            <a href="{{ url()->previous() }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors mb-4">
                <i class="fas fa-arrow-left mr-2 text-xs"></i> Kembali
            </a>

            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2 leading-tight">
                {{ $quiz->title }}
            </h1>

            @if($quiz->description)
                <div class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed max-w-3xl">
                    {{ $quiz->description }}
                </div>
            @endif
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Durasi</div>
                <div class="flex items-center gap-2">
                    <i class="far fa-clock text-primary-500"></i>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $quiz->duration_minutes }} Menit</span>
                </div>
            </div>

            <div class="p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Kesempatan</div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-redo text-primary-500"></i>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ $quiz->max_attempts == 0 ? 'Unlimited' : $quiz->max_attempts . 'x' }}
                    </span>
                </div>
            </div>

            <div class="p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">KKM / Passing Grade</div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-bullseye text-primary-500"></i>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $quiz->passing_grade ?? 60 }}%</span>
                </div>
            </div>

            <div class="p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Jumlah Soal</div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-list-ol text-primary-500"></i>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $quiz->questions_count ?? 0 }} Soal</span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 md:p-8 border border-gray-200 dark:border-gray-700 shadow-sm mb-8 text-center md:text-left flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex-1">
                @if($ongoing)
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1 flex items-center justify-center md:justify-start gap-2">
                        <span class="relative flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                        </span>
                        Sesi Sedang Berlangsung
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Anda memiliki sesi pengerjaan yang belum diselesaikan.
                    </p>
                @else
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1">Siap Mengerjakan?</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Pastikan koneksi internet stabil. Waktu akan berjalan otomatis saat tombol ditekan.
                    </p>
                @endif
            </div>

            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                @if($ongoing)
                    <a href="{{ route('participant.quiz.take', $ongoing->id) }}"
                       class="inline-flex justify-center items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-md transition-all hover:-translate-y-0.5">
                        <i class="fas fa-play mr-2"></i> Lanjutkan
                    </a>

                    <form action="{{ route('participant.quiz.start', $quiz->id) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Yakin ingin memulai ulang? Progress sesi aktif akan hilang.')"
                                class="w-full inline-flex justify-center items-center px-6 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <i class="fas fa-redo mr-2"></i> Ulangi Baru
                        </button>
                    </form>
                @else
                    <form action="{{ route('participant.quiz.start', $quiz->id) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full inline-flex justify-center items-center px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-md transition-all hover:-translate-y-0.5">
                            <i class="fas fa-play mr-2"></i> Mulai Quiz
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 flex justify-between items-center">
                <h3 class="font-bold text-gray-900 dark:text-white">Riwayat Pengerjaan</h3>
                <span class="text-xs font-bold bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-1 rounded">
                    {{ $attempts->count() }} Percobaan
                </span>
            </div>

            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($attempts as $att)
                    <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $att->id === $ongoing?->id ? 'bg-blue-50/50 dark:bg-blue-900/10' : '' }}">

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 flex-shrink-0">
                                <i class="far fa-calendar-alt"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $att->created_at->translatedFormat('d F Y, H:i') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    @if($att->finished_at)
                                        Selesai dalam {{ $att->created_at->diffInMinutes($att->finished_at) }} menit
                                    @else
                                        Sedang dikerjakan...
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 sm:gap-8 justify-between sm:justify-end w-full sm:w-auto">
                            @if($att->finished_at)
                                <div class="text-right">
                                    <span class="block text-xs text-gray-400 uppercase font-bold">Nilai</span>
                                    <span class="text-lg font-bold {{ $att->score >= ($quiz->passing_grade ?? 60) ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $att->score }}
                                    </span>
                                </div>

                                <a href="{{ route('participant.quiz.result', $att->id) }}"
                                   class="px-4 py-2 text-sm font-bold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                    Detail
                                </a>
                            @else
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                        Proses
                                    </span>
                                </div>
                                <a href="{{ route('participant.quiz.take', $att->id) }}"
                                   class="px-4 py-2 text-sm font-bold text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors">
                                    Lanjut
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada riwayat pengerjaan.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700">
            <div class="flex gap-3">
                <i class="fas fa-info-circle text-gray-400 mt-0.5"></i>
                <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                    <p><strong>Catatan:</strong></p>
                    <ul class="list-disc list-inside pl-1 space-y-1">
                        <li>Waktu pengerjaan tidak dapat dihentikan (pause).</li>
                        <li>Pastikan koneksi internet Anda stabil sebelum memulai.</li>
                        <li>Jawaban tersimpan otomatis setiap Anda berpindah soal.</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
