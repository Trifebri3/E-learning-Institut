{{--
    Partial ini menerima variabel:
    $enrolledPrograms (dikirim dari DashboardController)
--}}

@php
    use Carbon\Carbon;

    // Pisahkan program aktif dan selesai
    $activePrograms = $enrolledPrograms->filter(function($program) {
        return Carbon::parse($program->waktu_selesai)->isFuture();
    })->sortByDesc('waktu_mulai');

    $completedPrograms = $enrolledPrograms->filter(function($program) {
        return Carbon::parse($program->waktu_selesai)->isPast();
    })->sortByDesc('waktu_selesai');

    $user = Auth::user();
@endphp

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 md:p-8">
        @if ($enrolledPrograms->isEmpty())
            {{-- Tampilan jika BELUM terdaftar program --}}
            <div class="text-center border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8">
                <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-full flex items-center justify-center shadow-inner">
                    <i class="fas fa-folder-open text-3xl text-gray-400 dark:text-gray-500"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    Belum Ada Program
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                    Anda belum terdaftar di program manapun. Mari mulai petualangan belajar Anda dengan mengikuti program pertama!
                </p>

                {{-- Tombol Redeem --}}
                <a href="{{ route('participant.redeem.form') }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 border border-transparent rounded-xl font-bold text-white uppercase tracking-wider transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <i class="fas fa-ticket-alt mr-3"></i>
                    Redeem Kode Program
                </a>
            </div>

        @else
            {{-- ==================== PROGRAM AKTIF ==================== --}}
            @if($activePrograms->isNotEmpty())
            <div class="mb-8">
                {{-- Header Program Aktif --}}
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            Program Aktif
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Hallo <span class="font-semibold text-primary-600 dark:text-primary-400">{{ $user->name }}</span>,
                            selamat belajar di program berikut:
                        </p>
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2 bg-primary-50 dark:bg-primary-900/20 rounded-full">
                        <i class="fas fa-play-circle text-primary-600 dark:text-primary-400"></i>
                        <span class="text-sm font-semibold text-primary-600 dark:text-primary-400">
                            {{ $activePrograms->count() }} Program Aktif
                        </span>
                    </div>
                </div>

                {{-- Program Aktif Cards --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($activePrograms as $program)
                    <div class="group relative bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-200 dark:border-gray-700 overflow-hidden">
                        {{-- Banner Program --}}
                        @if($program->banner_path)
                        <div class="h-32 bg-cover bg-center" style="background-image: url('{{ Storage::url($program->banner_path) }}')"></div>
                        @else
                        <div class="h-32 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-white text-4xl opacity-80"></i>
                        </div>
                        @endif

                        {{-- Content --}}
                        <div class="p-6">
                            <div class="flex items-start space-x-4">
                                {{-- Logo Program --}}
                                <div class="flex-shrink-0">
                                    <img src="{{ $program->logo_path ? Storage::url($program->logo_path) : 'https://via.placeholder.com/80.png?text=LMS' }}"
                                         alt="Logo Program"
                                         class="w-16 h-16 rounded-xl object-cover bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-600">
                                </div>

                                {{-- Info Program --}}
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                        {{ $program->title }}
                                    </h3>

                                    @if($program->description)
                                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-2">
                                        {{ $program->description }}
                                    </p>
                                    @endif

                                    {{-- Progress & Info --}}
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center gap-1">
                                                <i class="fas fa-calendar-day"></i>
                                                {{ Carbon::parse($program->waktu_mulai)->translatedFormat('d M Y') }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <i class="fas fa-flag-checkered"></i>
                                                {{ Carbon::parse($program->waktu_selesai)->translatedFormat('d M Y') }}
                                            </span>
                                        </div>

                                        {{-- Progress Indicator --}}
                                        @php
                                            $progress = $program->progress ?? 0;
                                            $daysLeft = Carbon::parse($program->waktu_selesai)->diffInDays(now());
                                        @endphp
                                        <div class="text-right">
                                            <div class="text-xs font-semibold text-primary-600 dark:text-primary-400">
                                                {{ $progress }}% Selesai
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $daysLeft }} hari lagi
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Progress Bar --}}
                                    <div class="mt-3 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-green-400 to-blue-500 h-2 rounded-full transition-all duration-1000 ease-out"
                                             style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="mt-6 flex gap-3">
                                <a href="{{ route('participant.kelas.index', ['program' => $program->id]) }}"
                                   class="flex-1 bg-primary-600 hover:bg-primary-700 text-white text-center py-3 px-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                                    <i class="fas fa-chalkboard-teacher mr-2"></i>
                                    Lanjutkan Belajar
                                </a>
                                <button class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-400 p-3 rounded-xl transition-colors">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Badge Status --}}
                        <div class="absolute top-4 right-4">
                            @if($daysLeft <= 7)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300">
                                <i class="fas fa-clock mr-1"></i>
                                Deadline Mendekati
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300">
                                <i class="fas fa-play-circle mr-1"></i>
                                Sedang Berjalan
                            </span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ==================== RIWAYAT PROGRAM ==================== --}}
            @if($completedPrograms->isNotEmpty())
            <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                {{-- Header Riwayat --}}
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            Riwayat Program
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Program yang sedang dan telah Anda ikuti
                        </p>
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-full">
                        <i class="fas fa-history text-gray-600 dark:text-gray-400"></i>
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">
                            {{ $completedPrograms->count() }} Program yang diikuti
                        </span>
                    </div>
                </div>

                {{-- Grid Riwayat Program --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($completedPrograms as $program)
                    <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="p-5">
                            {{-- Header Riwayat --}}
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="flex-shrink-0">
                                    <img src="{{ $program->logo_path ? Storage::url($program->logo_path) : 'https://via.placeholder.com/48.png?text=LMS' }}"
                                         alt="Logo Program"
                                         class="w-12 h-12 rounded-lg object-cover bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 dark:text-white text-sm line-clamp-1">
                                        {{ $program->title }}
                                    </h4>

                                </div>
                            </div>

                            {{-- Progress Selesai --}}


                            {{-- Tombol Riwayat --}}
                            <div class="flex gap-2">
                                <a href="{{ route('participant.kelas.index', ['program' => $program->id, 'filter' => 'finished']) }}"
                                   class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-center py-2 px-3 rounded-lg text-xs font-medium transition-colors">
                                    <i class="fas fa-eye mr-1"></i>
                                    Lihat Kelas
                                </a>
                                <button class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-500 dark:text-gray-400 p-2 rounded-lg transition-colors text-xs">
                                    <i class="fas fa-certificate"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Badge Selesai --}}
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ==================== TOMBOL UTAMA ==================== --}}
            @if($activePrograms->isNotEmpty())
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('participant.kelas.index') }}"
                   class="w-full text-center justify-center inline-flex items-center px-6 py-4 bg-gradient-to-r from-primary-500 to-blue-600 hover:from-primary-600 hover:to-blue-700 border border-transparent rounded-2xl font-bold text-white uppercase tracking-wider transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
                    <i class="fas fa-chalkboard-teacher mr-3 text-lg"></i>
                    Lihat Semua Kelas Saya
                    <i class="fas fa-arrow-right ml-2 text-sm"></i>
                </a>
            </div>
            @endif

        @endif
    </div>
</div>

@push('styles')
<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .group:hover .group-hover\:text-primary-600 {
        transition: color 0.3s ease;
    }

    .shadow-custom {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .hover\:shadow-2xl:hover {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
</style>
@endpush
