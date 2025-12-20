@php
    $isFinished = $type === 'finished';
    $isInteractive = $item->tipe == 'interaktif';
    $classDate = \Carbon\Carbon::parse($item->tanggal);
    $isToday = $classDate->isToday();
    $isUpcoming = $classDate->isFuture();
    $daysUntil = $classDate->diffInDays(now());
    $isSoon = $daysUntil <= 7 && $daysUntil > 0;
    $isVerySoon = $daysUntil <= 3 && $daysUntil > 0;
    $isTomorrow = $daysUntil === 1;

    // Warna berdasarkan status
$statusColor = $isFinished ? 'gray' :
              ($isToday ? 'green' :
              ($isVerySoon ? 'lime' :
              ($isTomorrow ? 'emerald' :
              ($isSoon ? 'teal' : 'green'))));

// Gradien hijau
$gradientColors = [
    'gray'    => 'from-gray-500 to-gray-600',
    'green'   => 'from-green-500 to-emerald-600',   // Hari ini
    'lime'    => 'from-lime-400 to-lime-600',       // Sangat dekat
    'emerald' => 'from-emerald-400 to-emerald-600', // Besok
    'teal'    => 'from-teal-400 to-teal-600',       // Dekat
];

    $statusGradient = $gradientColors[$statusColor];
    $typeGradient = $isInteractive ? $gradientColors['teal'] : $gradientColors['lime'];
@endphp

@if($isFinished || $isUpcoming || $isToday)
<div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-500 hover:-translate-y-2 relative
            {{ $isFinished ? 'opacity-80 grayscale hover:grayscale-0 hover:opacity-95' : '' }}
            {{ $isToday ? 'ring-4 ring-green-500/30 shadow-green-200 dark:shadow-green-900' : '' }}
            {{ $isVerySoon ? 'ring-4 ring-red-500/30 shadow-red-200 dark:shadow-red-900 animate-pulse-slow' : '' }}
            {{ $isSoon ? 'ring-4 ring-yellow-500/30 shadow-yellow-200 dark:shadow-yellow-900' : '' }}
            {{ $isTomorrow ? 'ring-4 ring-orange-500/30 shadow-orange-200 dark:shadow-orange-900' : '' }}">

    <!-- Glow Effect -->
    <div class="absolute inset-0 bg-gradient-to-br from-{{$statusColor}}-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

    <!-- Banner Image dengan Overlay -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent z-10"></div>

        <img
            src="{{ $item->banner_path
                ? Storage::url($item->banner_path)
                : asset('images/defaultkelas.svg') }}"
            alt="{{ $item->title }}"
            class="w-full h-52 object-cover rounded-b-2xl transform group-hover:scale-105 transition-transform duration-700"
        />

        <!-- Gradient Status Bar -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r {{$statusGradient}} z-20"></div>

        <!-- Status Badge dengan Animasi -->
        <div class="absolute top-4 left-4 z-20">
            <div class="relative">
                <span class="px-4 py-2 bg-gradient-to-r {{$statusGradient}} text-white text-sm font-semibold rounded-full flex items-center gap-2 shadow-lg transform group-hover:scale-105 transition-transform duration-300">
                    @if($isFinished)
                    <i class="fas fa-check-circle text-white animate-bounce-slow"></i>
                    <span>Selesai</span>
                    @elseif($isToday)
                    <i class="fas fa-bolt text-white animate-pulse"></i>
                    <span>Hari Ini!</span>
                    @elseif($isVerySoon)
                    <i class="fas fa-exclamation-triangle text-white animate-pulse"></i>
                    <span>H-{{ $daysUntil }}!</span>
                    @elseif($isTomorrow)
                    <i class="fas fa-hourglass-half text-white animate-spin-slow"></i>
                    <span>Besok!</span>
                    @elseif($isSoon)
                    <i class="fas fa-clock text-white animate-pulse-slow"></i>
                    <span>H-{{ $daysUntil }}</span>
                    @elseif($isUpcoming)
                    <i class="fas fa-calendar-plus text-white"></i>
                    <span class="countdown-timer" data-end="{{ $classDate->format('Y-m-d\TH:i:s') }}"></span>
                    @endif
                </span>

                <!-- Ping Animation untuk status penting -->
                @if($isToday || $isVerySoon)
                <div class="absolute -top-1 -left-1 -right-1 -bottom-1 bg-{{$statusColor}}-500 rounded-full animate-ping opacity-60 z-0"></div>
                @endif
            </div>
        </div>

        <!-- Type Badge -->
        <div class="absolute top-4 right-4 z-20">
            <span class="px-4 py-2 bg-gradient-to-r {{$typeGradient}} text-white text-sm font-semibold rounded-full flex items-center gap-2 shadow-lg transform group-hover:scale-105 transition-transform duration-300">
                <i class="fas fa-{{ $isInteractive ? 'video text-yellow-300' : 'book-open text-orange-300' }}"></i>
                {{ $isInteractive ? 'Live Interaktif' : 'Materi Belajar' }}
            </span>
        </div>

        <!-- Time Indicator -->
        <div class="absolute bottom-4 left-4 z-20">
            <div class="bg-black/60 backdrop-blur-sm text-white px-3 py-2 rounded-lg">
                <div class="flex items-center gap-2 text-sm">
                    <i class="fas fa-clock text-{{$statusColor}}-400"></i>
                    <span>{{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }} WIB</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="p-6 relative z-10">
        <!-- Title dengan efek gradien -->
        <h3 class="font-bold text-xl text-gray-900 dark:text-white mb-3 line-clamp-2 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r {{$statusGradient}} transition-all duration-500">
            {{ $item->title }}
        </h3>

        <!-- Info Grid -->
        <div class="grid grid-cols-1 gap-3 mb-5">
            <!-- Tanggal -->
            <div class="flex items-center text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 p-3 rounded-xl transition-all duration-300 group-hover:bg-{{$statusColor}}-50 dark:group-hover:bg-{{$statusColor}}-900/20">
                <div class="w-10 h-10 bg-gradient-to-br {{$statusGradient}} rounded-lg flex items-center justify-center mr-3 text-white">
                    <i class="fas fa-calendar text-sm"></i>
                </div>
                <div>
                    <div class="font-semibold">{{ $classDate->translatedFormat('l, d F Y') }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $classDate->diffForHumans() }}</div>
                </div>
            </div>

            <!-- Instruktur -->
            @if($item->instruktur)
            <div class="flex items-center text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 p-3 rounded-xl transition-all duration-300 group-hover:bg-{{$statusColor}}-50 dark:group-hover:bg-{{$statusColor}}-900/20">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3 text-white">
                    <i class="fas fa-user text-sm"></i>
                </div>
                <div>
                    <div class="font-semibold">{{ $item->instruktur }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Instruktur</div>
                </div>
            </div>
            @endif

            <!-- Durasi -->
            <div class="flex items-center text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 p-3 rounded-xl transition-all duration-300 group-hover:bg-{{$statusColor}}-50 dark:group-hover:bg-{{$statusColor}}-900/20">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-lg flex items-center justify-center mr-3 text-white">
                    <i class="fas fa-hourglass text-sm"></i>
                </div>
                <div>
                    @php
                        $start = \Carbon\Carbon::parse($item->jam_mulai);
                        $end = \Carbon\Carbon::parse($item->jam_selesai);
                        $duration = $start->diff($end);
                    @endphp
                    <div class="font-semibold">{{ $duration->h }} jam {{ $duration->i }} menit</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Durasi Kelas</div>
                </div>
            </div>
        </div>

        <!-- Progress Bar untuk kelas yang akan datang -->
        @if(!$isFinished)
        <div class="mb-5">
            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                <span>Waktu Menuju Kelas</span>
                <span class="font-semibold text-{{$statusColor}}-600 dark:text-{{$statusColor}}-400">
                    @if($isToday) Sedang Berlangsung
                    @elseif($isTomorrow) 1 Hari Lagi
                    @elseif($isVerySoon) {{ $daysUntil }} Hari Lagi
                    @elseif($isSoon) {{ $daysUntil }} Hari Lagi
                    @else {{ $classDate->diffForHumans() }}
                    @endif
                </span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                @php
                    $progress = $isToday ? 100 :
                               ($isTomorrow ? 90 :
                               ($isVerySoon ? 80 :
                               ($isSoon ? 60 : 30)));
                @endphp
                <div class="bg-gradient-to-r {{$statusGradient}} h-2 rounded-full transition-all duration-1000 ease-out"
                     style="width: {{$progress}}%"></div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex gap-3">
            <a href="{{ route('participant.kelas.show', $item->id) }}"
               class="flex-1 bg-gradient-to-r {{$statusGradient}} hover:shadow-lg text-white text-center py-3 px-4 rounded-xl font-semibold transition-all duration-300 transform group-hover:scale-105 flex items-center justify-center gap-3 shadow-md hover:shadow-xl relative overflow-hidden">
               <div class="absolute inset-0 bg-white/20 group-hover:bg-white/0 transition-all duration-300"></div>
                <i class="fas fa-{{ $isFinished ? 'eye' : 'door-open' }} text-lg"></i>
                <span>{{ $isFinished ? 'Lihat Rekaman' : 'Masuk Kelas' }}</span>
                <i class="fas fa-arrow-right text-sm transform group-hover:translate-x-1 transition-transform duration-300"></i>
            </a>

            @if(!$isFinished && $isInteractive)
            <button class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white p-3 rounded-xl transition-all duration-300 transform group-hover:scale-105 hover:shadow-lg shadow-md group/btn relative overflow-hidden"
                    title="Siapkan Meeting">
                <div class="absolute inset-0 bg-white/20 group-hover/btn:bg-white/0 transition-all duration-300"></div>
                <i class="fas fa-video text-lg"></i>
            </button>
            @endif

            @if(!$isFinished)
            <button class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white p-3 rounded-xl transition-all duration-300 transform group-hover:scale-105 hover:shadow-lg shadow-md group/btn relative overflow-hidden"
                    title="Tambahkan ke Kalender">
                <div class="absolute inset-0 bg-white/20 group-hover/btn:bg-white/0 transition-all duration-300"></div>
                <i class="fas fa-bell text-lg"></i>
            </button>
            @endif
        </div>
    </div>

    <!-- Corner Decoration -->
    <div class="absolute top-0 right-0 w-8 h-8 bg-gradient-to-bl {{$statusGradient}} rounded-bl-2xl opacity-80"></div>
</div>

<!-- Custom Animations CSS -->
<style>
.animate-pulse-slow {
    animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.animate-bounce-slow {
    animation: bounce 2s infinite;
}

.animate-spin-slow {
    animation: spin 3s linear infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Smooth transitions for all interactive elements */
* {
    transition-property: color, background-color, border-color, transform, box-shadow;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}

/* Enhanced hover effects */
.hover-lift:hover {
    transform: translateY(-8px);
}
</style>
@endif
