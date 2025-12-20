@php
    $latestProgram = $user->programs()->latest()->first();
    $programLogo = $latestProgram->logo_path ?? null;
    $programTitle = $latestProgram->title ?? 'Belum Mengikuti Program';
    $programDescription = $latestProgram->description ?? 'Silakan ikuti program untuk memulai pembelajaran';
    $programBanner = $latestProgram->banner_path ?? null;
@endphp

{{-- Main Profile Container --}}
<div class="relative min-h-96 rounded-[2rem] overflow-hidden bg-white dark:bg-gray-900 border-2 border-primary-200 dark:border-primary-900 transition-all duration-500 hover:border-primary-400 dark:hover:border-primary-700 shadow-soft group/container">

    {{-- 1. Background Pattern (Dot Matrix - Neutral) --}}
    <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.05] pointer-events-none"
         style="background-image: radial-gradient(#55826F 1px, transparent 1px); background-size: 24px 24px;">
    </div>

    {{-- 2. Animated Background Lines (Subtle) --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-primary-100/50 dark:bg-primary-900/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-primary-100/50 dark:bg-primary-900/20 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

    {{-- Main Content --}}
    <div class="relative z-10 p-6 md:p-10 lg:p-12">

        {{-- Header Section --}}
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between mb-10 gap-8">

            {{-- Program Info --}}
            <div class="flex items-center space-x-6">
                {{-- Program Logo (With Outline) --}}
                <div class="relative group flex-shrink-0">
                    <div class="w-24 h-24 md:w-28 md:h-28 rounded-2xl bg-white dark:bg-gray-800 border-2 border-primary-100 dark:border-primary-800 flex items-center justify-center p-4 shadow-sm group-hover:border-primary-500 transition-colors duration-300">
                        @php
                            $logoPath = $programLogo && Storage::disk('public')->exists($programLogo)
                                        ? asset('storage/' . $programLogo)
                                        : asset('images/defaultlogoprogram.svg');
                        @endphp
                        <img src="{{ $logoPath }}" class="w-full h-full object-contain" alt="Logo">
                    </div>
                </div>

                {{-- Greeting and Program --}}
                <div class="text-gray-800 dark:text-gray-100">
                    <h1 class="text-2xl md:text-3xl font-bold mb-1 flex items-center font-sans tracking-tight">
                        <span class="animate-wave inline-block mr-3">👋</span>
                        Halo, <span class="text-primary-600 dark:text-primary-400 ml-1">{{ Str::words($user->name, 1, '') }}</span>!
                    </h1>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mt-2">
                        <h2 class="text-lg md:text-xl font-medium text-gray-500 dark:text-gray-400">
                            {{ $programTitle ?? 'Belum Terdaftar' }}
                        </h2>

                        @php
                            $isEnrolled = $user->programs && $user->programs->count() > 0;
                        @endphp

                        {{-- Status Badge (Outlined) --}}
                        <span class="px-3 py-1 rounded-full text-xs font-bold border flex items-center space-x-2 w-fit
                            {{ $isEnrolled
                                ? 'border-green-500 text-green-600 bg-green-50 dark:bg-green-900/20 dark:text-green-400'
                                : 'border-red-500 text-red-600 bg-red-50 dark:bg-red-900/20 dark:text-red-400' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $isEnrolled ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            <span>{{ $isEnrolled ? 'Aktif' : 'Non-Aktif' }}</span>
                        </span>
                    </div>
                </div>
            </div>

            {{-- Quick Stats (Line Style) --}}
            @php
                $programs = $user->programs ?? collect();
                $kelasQuery = \App\Models\Kelas::whereIn('program_id', $programs->pluck('id'))->get();
                $totalKelas = $kelasQuery->count();
                $totalFinished = $kelasQuery->filter(fn($item) => now()->gt($item->tanggal))->count();
                $progressPercentage = $totalKelas > 0 ? round(($totalFinished / $totalKelas) * 100) : 0;
            @endphp

            <div class="w-full lg:w-auto flex items-center justify-between lg:justify-start bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 shadow-sm lg:space-x-8">
                <div class="text-center px-4">
                    <div class="text-xl font-bold text-primary-600 dark:text-primary-400">{{ $programs->count() }}</div>
                    <div class="text-gray-400 text-[10px] uppercase tracking-wider font-semibold">Program</div>
                </div>
                <div class="h-8 w-px bg-gray-200 dark:bg-gray-700"></div>
                <div class="text-center px-4">
                    <div class="text-xl font-bold text-primary-600 dark:text-primary-400">{{ $totalKelas }}</div>
                    <div class="text-gray-400 text-[10px] uppercase tracking-wider font-semibold">Kelas</div>
                </div>
                <div class="h-8 w-px bg-gray-200 dark:bg-gray-700"></div>
                <div class="text-center px-4">
                    <div class="text-xl font-bold text-primary-600 dark:text-primary-400">{{ $progressPercentage }}%</div>
                    <div class="text-gray-400 text-[10px] uppercase tracking-wider font-semibold">Progress</div>
                </div>
            </div>

        </div>

        {{-- Profile Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- 1. Profile Card (Outlined) --}}
            <div class="lg:col-span-1">
                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-8 border border-gray-200 dark:border-gray-700 h-full flex flex-col justify-center items-center relative overflow-hidden">

                    {{-- Decorative Line --}}
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary-400 to-primary-600"></div>

                    <div class="relative mb-4">
                        <div class="w-32 h-32 rounded-full p-1 border-2 border-dashed border-primary-300 dark:border-primary-700">
                            <div class="w-full h-full rounded-full overflow-hidden bg-white">
                                @php $foto = $user->profile->pas_foto_path ?? null; @endphp
                                <img src="{{ ($foto && Storage::disk('public')->exists($foto)) ? asset('storage/' . $foto) : asset('images/defaultprofil.svg') }}"
                                     class="w-full h-full object-cover" alt="Profile">
                            </div>
                        </div>
                        {{-- Role Icon --}}
                        <div class="absolute bottom-0 right-0 w-8 h-8 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-full flex items-center justify-center text-primary-600 dark:text-primary-400 shadow-sm">
                            <i class="fas fa-user text-xs"></i>
                        </div>
                    </div>

                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 text-center">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-4">{{ $user->email }}</p>

                    <div class="flex items-center gap-2 mb-6">
                         <span class="px-3 py-1 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded text-xs font-semibold text-gray-600 dark:text-gray-300">
                            {{ ucfirst($user->role) }}
                        </span>
                        @if($user->is_online)
                            <span class="px-3 py-1 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded text-xs font-semibold text-green-600 dark:text-green-400 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Online
                            </span>
                        @endif
                    </div>

                    <div class="w-full h-px bg-gray-200 dark:bg-gray-700 mb-4"></div>
                    <p class="text-xs text-gray-400">Bergabung: {{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>

            {{-- 2. Details & Actions (Right Column) --}}
            <div class="lg:col-span-2 flex flex-col gap-6">

                {{-- Info Box --}}
                @if($latestProgram && $latestProgram->description)
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-2">Tentang Program</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                        {{ $latestProgram->description }}
                    </p>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 flex-1">
                    {{-- Progress --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between">
                        <div>
                             <h4 class="font-bold text-gray-800 dark:text-white mb-1 flex items-center gap-2">
                                <i class="fas fa-chart-line text-primary-500"></i> Statistik
                            </h4>
                            <p class="text-xs text-gray-500 mb-4">Ringkasan aktivitas belajar Anda.</p>
                        </div>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Kelas Selesai</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $totalFinished }} <span class="text-gray-400 font-normal">/ {{ $totalKelas }}</span></span>
                            </div>
                            {{-- Progress Bar (Line Style) --}}
                            <div class="w-full bg-gray-100 dark:bg-gray-700 h-2 rounded-full overflow-hidden">
                                <div class="bg-primary-500 h-full rounded-full" style="width: {{ $progressPercentage }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Status --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
                        <h4 class="font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-list-alt text-primary-500"></i> Detail
                        </h4>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-2">
                                <span class="text-gray-500">Program Saat Ini</span>
                                <span class="font-semibold text-gray-800 dark:text-gray-200 text-right truncate max-w-[150px]">{{ $latestProgram->title ?? '-' }}</span>
                            </div>
                             @if($latestProgram)
                                @php $enrollment = $user->programs()->where('programs.id', $latestProgram->id)->first(); @endphp
                                @if($enrollment && $enrollment->pivot->created_at)
                                <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-2">
                                    <span class="text-gray-500">Tanggal Daftar</span>
                                    <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $enrollment->pivot->created_at->format('d/m/Y') }}</span>
                                </div>
                                @endif
                            @endif
                            <div class="flex justify-between pt-1">
                                <span class="text-gray-500">Status Akun</span>
                                <span class="text-green-600 font-bold flex items-center gap-1"><i class="fas fa-check-circle"></i> Verified</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons (Outline & Solid Mix) --}}
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('participant.kelas.index') }}"
                       class="flex-1 min-w-[140px] flex items-center justify-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold transition-all shadow-md hover:shadow-lg">
                        <i class="fas fa-play mr-2 text-xs"></i> Lanjutkan
                    </a>

                    <a href="{{ route('participant.progress.index') }}"
                       class="flex-1 min-w-[140px] flex items-center justify-center px-6 py-3 bg-white dark:bg-gray-800 border-2 border-primary-100 dark:border-primary-800 text-primary-700 dark:text-primary-300 hover:border-primary-500 hover:text-primary-600 rounded-xl font-bold transition-all">
                        <i class="fas fa-chart-bar mr-2 text-xs"></i> Progress
                    </a>

                    <a href="{{ route('participant.profil.index') }}"
                       class="w-12 h-12 md:w-auto md:px-6 flex items-center justify-center bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-500 hover:text-primary-600 hover:border-primary-200 rounded-xl font-bold transition-all" title="Pengaturan">
                        <i class="fas fa-cog md:mr-2"></i> <span class="hidden md:inline">Setting</span>
                    </a>
                </div>

            </div>
        </div>

        {{-- Include program-mini --}}
        @include('components.program-mini', ['enrolledPrograms' => $enrolledPrograms])

    </div>
</div>

<style>
    .animate-wave { animation: wave 2s infinite; transform-origin: 70% 70%; display: inline-block; }
    @keyframes wave {
        0% { transform: rotate(0deg); }
        10% { transform: rotate(14deg); }
        20% { transform: rotate(-8deg); }
        30% { transform: rotate(14deg); }
        40% { transform: rotate(-4deg); }
        50% { transform: rotate(10deg); }
        60% { transform: rotate(0deg); }
        100% { transform: rotate(0deg); }
    }
</style>
