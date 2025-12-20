@extends('participant.layouts.app')

@section('title', $kelas->title . ' - Institut Hijau Indonesia')

@section('content')
<div class="min-h-screen py-8" x-data="{ activeSection: 'deskripsi' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-6">
            <a href="{{ route('participant.kelas.index') }}"
               class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary-600 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Kelas
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 relative">

            <div class="lg:col-span-3 space-y-8">

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="relative h-64 md:h-80 bg-gray-200">
                        <img src="{{ $kelas->banner_path ? Storage::url($kelas->banner_path) : asset('images/defaultkelas.svg') }}"
                             alt="{{ $kelas->title }}"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

                        <div class="absolute bottom-0 left-0 w-full p-6 md:p-8">
                            <span class="inline-block px-3 py-1 mb-3 rounded-md text-xs font-bold uppercase tracking-wider bg-primary-600 text-white shadow-sm">
                                {{ $kelas->tipe == 'interaktif' ? 'Kelas Interaktif' : 'Pembelajaran Mandiri' }}
                            </span>
                            <h1 class="text-2xl md:text-4xl font-bold text-white mb-2 leading-tight">
                                {{ $kelas->title }}
                            </h1>
                            <div class="flex flex-wrap items-center gap-4 text-white/90 text-sm">
                                <span class="flex items-center gap-1.5"><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($kelas->tanggal)->translatedFormat('l, d F Y') }}</span>
                                <span class="flex items-center gap-1.5"><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') }} WIB</span>
                                @if($kelas->instruktur)
                                    <span class="flex items-center gap-1.5"><i class="far fa-user"></i> {{ $kelas->instruktur }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    use Carbon\Carbon;
                    $now = Carbon::now();
                    $start = Carbon::parse($kelas->tanggal . ' ' . $kelas->jam_mulai);
                    $end = Carbon::parse($kelas->tanggal . ' ' . $kelas->jam_selesai);

                    if ($now->lt($start)) { $status = 'belum'; }
                    elseif ($now->between($start, $end)) { $status = 'berlangsung'; }
                    else { $status = 'selesai'; }
                @endphp

                @if ($kelas->tipe == 'interaktif' && $kelas->link_zoom)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-video text-red-500"></i> Sesi Live Zoom
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            @if ($status == 'belum')
                                Kelas belum dimulai. Harap menunggu hingga jadwal yang ditentukan.
                            @elseif ($status == 'berlangsung')
                                Kelas sedang berlangsung! Silakan bergabung sekarang.
                            @else
                                Sesi live telah berakhir.
                            @endif
                        </p>
                    </div>

                    <div class="w-full md:w-auto flex-shrink-0">
                        @if ($status == 'belum')
                            <button disabled class="w-full md:w-auto px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-400 font-bold rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                                <i class="fas fa-lock"></i> Belum Dimulai
                            </button>
                        @elseif ($status == 'berlangsung')
                            <a href="{{ $kelas->link_zoom }}" target="_blank"
                               class="w-full md:w-auto px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl flex items-center justify-center gap-2 shadow-lg shadow-primary-500/30 transition-all transform hover:-translate-y-0.5 animate-pulse">
                                <i class="fas fa-video"></i> Masuk Zoom
                            </a>
                        @else
                            <button disabled class="w-full md:w-auto px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-400 font-bold rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                                <i class="fas fa-check-circle"></i> Selesai
                            </button>
                        @endif
                    </div>
                </div>
                @endif

                <div id="deskripsi" class="scroll-mt-24">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 md:p-8 border border-gray-200 dark:border-gray-700 shadow-sm">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                                <i class="fas fa-align-left text-lg"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Deskripsi Kelas</h2>
                        </div>
                        <div class="prose prose-lg dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                            {!! nl2br(e($kelas->deskripsi)) !!}
                        </div>
                    </div>
                </div>

                <div id="materi" class="scroll-mt-24 space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 flex items-center justify-center">
                            <i class="fas fa-book-reader text-lg"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Materi Pembelajaran</h2>
                    </div>

                    <x-kelas-learning-path :kelas="$kelas" :learning-path="$kelas->learningPath" />

                    @if($kelas->modules->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                            @foreach($kelas->modules as $mod)
                                @php $isCompleted = Auth::user()->completedModules->contains($mod->id); @endphp
                                <a href="{{ route('participant.module.show', $mod->id) }}"
                                   class="flex items-center p-5 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition border-b border-gray-100 dark:border-gray-700 last:border-0 group">
                                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center mr-4
                                        {{ $isCompleted ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                                        <i class="fas {{ $isCompleted ? 'fa-check' : 'fa-book' }}"></i>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Modul {{ $loop->iteration }}</span>
                                        <h4 class="text-base font-bold text-gray-900 dark:text-white group-hover:text-primary-600 transition">{{ $mod->title }}</h4>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-300 group-hover:text-primary-500"></i>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-kelas-video-list :videos="$kelas->videoEmbeds" />
                        <x-kelas-resource-list :resources="$kelas->resources" />
                    </div>
                </div>

                @if(isset($setupPresensi) && $setupPresensi->is_active)
                <div id="presensi" class="scroll-mt-24">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-1">
                        @include('participant.kelas.partials.presensi-box', [
                            'setup' => $setupPresensi,
                            'hasil' => $hasilPresensi ?? null,
                            'kelas' => $kelas,
                            'awal_open' => $awal_open ?? null,
                            'akhir_open' => $akhir_open ?? null
                        ])
                    </div>
                </div>
                @endif

                @if($quizzes->count() > 0 || $kelas->essayExams->count() > 0)
                <div id="evaluasi" class="scroll-mt-24 space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                            <i class="fas fa-pencil-alt text-lg"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Evaluasi & Ujian</h2>
                    </div>

                    @if($quizzes->count() > 0)
                        <div class="space-y-4">
                            @foreach($quizzes as $quiz)
                                @php
                                    $remainingAttempts = $quiz->remainingAttempts();
                                    $hasAttempts = $quiz->attempts()->where('user_id', auth()->id())->exists();
                                    $isCompleted = $hasAttempts && $remainingAttempts === 0;
                                @endphp
                                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-full bg-purple-100 text-green-900 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-clipboard-check text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 dark:text-white text-lg">{{ $quiz->title }}</h4>
                                            <div class="flex items-center gap-4 text-sm text-gray-500 mt-1">
                                                <span><i class="far fa-clock"></i> {{ $quiz->duration_minutes }}m</span>
                                                <span><i class="fas fa-redo"></i> Sisa: {{ $remainingAttempts }}x</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="w-full md:w-auto">
                                        @if($isCompleted)
                                            <a href="{{ route('participant.quiz.result', $quiz->id) }}" class="inline-flex w-full md:w-auto items-center justify-center px-6 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">
                                                Lihat Hasil
                                            </a>
                                        @elseif($quiz->is_published && $remainingAttempts > 0)
    <a href="{{ route('participant.quiz.show', $quiz->id) }}"
       class="inline-flex w-full md:w-auto items-center justify-center px-6 py-2.5 bg-green-900 text-white font-bold rounded-xl hover:bg-green-500 transition shadow-lg shadow-purple-500/30">
        Mulai Quiz
    </a>
                                        @else
                                            <button disabled class="inline-flex w-full md:w-auto items-center justify-center px-6 py-2.5 bg-gray-100 text-gray-400 font-bold rounded-xl cursor-not-allowed">
                                                Terkunci
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($kelas->essayExams->count() > 0)
                        <div class="space-y-4">
                            @foreach($kelas->essayExams as $exam)
                                <x-essay-card :exam="$exam" />
                            @endforeach
                        </div>
                    @endif
                </div>
                @endif

                @if($assignments->count() > 0)
                <div id="tugas" class="scroll-mt-24 space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 flex items-center justify-center">
                            <i class="fas fa-tasks text-lg"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Penugasan</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($assignments as $assignment)
                            @php $submission = $assignment->submissions->first(); @endphp
                            <x-assignment-status-card :assignment="$assignment" :submission="$submission" />
                        @endforeach
                    </div>
                </div>
                @endif

                @if($kelas->narasumbers->count() > 0)
                <div id="narasumber" class="scroll-mt-24 pt-8 border-t border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Narasumber Kelas</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($kelas->narasumbers as $narasumber)
                            <x-narasumber-card :narasumber="$narasumber" />
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            <div class="hidden lg:block lg:col-span-1">
                <div class="sticky top-24 space-y-6">

                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-2">
                        <nav class="space-y-1">
                            <a href="#deskripsi"
                               class="flex items-center px-4 py-3 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition group"
                               :class="{ 'bg-primary-50 text-primary-700 font-bold': activeSection === 'deskripsi' }"
                               @click="activeSection = 'deskripsi'">
                                <span class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 flex items-center justify-center mr-3 group-hover:bg-white group-hover:text-primary-600 transition-colors">
                                    <i class="fas fa-align-left text-xs"></i>
                                </span>
                                Deskripsi
                            </a>

                            <a href="#materi"
                               class="flex items-center px-4 py-3 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition group"
                               @click="activeSection = 'materi'">
                                <span class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 flex items-center justify-center mr-3 group-hover:bg-white group-hover:text-primary-600 transition-colors">
                                    <i class="fas fa-book-open text-xs"></i>
                                </span>
                                Materi & Modul
                            </a>

                            @if(isset($setupPresensi) && $setupPresensi->is_active)
                            <a href="#presensi"
                               class="flex items-center px-4 py-3 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition group"
                               @click="activeSection = 'presensi'">
                                <span class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 flex items-center justify-center mr-3 group-hover:bg-white group-hover:text-primary-600 transition-colors">
                                    <i class="fas fa-fingerprint text-xs"></i>
                                </span>
                                Presensi
                            </a>
                            @endif

                            @if($quizzes->count() > 0 || $kelas->essayExams->count() > 0)
                            <a href="#evaluasi"
                               class="flex items-center px-4 py-3 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition group"
                               @click="activeSection = 'evaluasi'">
                                <span class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 flex items-center justify-center mr-3 group-hover:bg-white group-hover:text-primary-600 transition-colors">
                                    <i class="fas fa-pencil-alt text-xs"></i>
                                </span>
                                Evaluasi
                            </a>
                            @endif

                            @if($assignments->count() > 0)
                            <a href="#tugas"
                               class="flex items-center px-4 py-3 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition group"
                               @click="activeSection = 'tugas'">
                                <span class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 flex items-center justify-center mr-3 group-hover:bg-white group-hover:text-primary-600 transition-colors">
                                    <i class="fas fa-tasks text-xs"></i>
                                </span>
                                Tugas
                            </a>
                            @endif

                            @if($kelas->narasumbers->count() > 0)
                            <a href="#narasumber"
                               class="flex items-center px-4 py-3 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition group"
                               @click="activeSection = 'narasumber'">
                                <span class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 flex items-center justify-center mr-3 group-hover:bg-white group-hover:text-primary-600 transition-colors">
                                    <i class="fas fa-users text-xs"></i>
                                </span>
                                Narasumber
                            </a>
                            @endif
                        </nav>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Zoom confirmation
        const zoomLink = document.querySelector('a[href*="zoom"]');
        if (zoomLink) {
            zoomLink.addEventListener('click', function(e) {
                if (!confirm('Buka Zoom di tab baru?')) e.preventDefault();
            });
        }

        // Active Scroll Spy (Optional - untuk highlight menu saat scroll)
        const observerOptions = { root: null, rootMargin: '-20% 0px -70% 0px', threshold: 0 };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Logic update alpine js state bisa ditambahkan disini jika perlu sync 2 arah
                }
            });
        }, observerOptions);

        document.querySelectorAll('div[id]').forEach(section => observer.observe(section));
    });
</script>
@endpush
