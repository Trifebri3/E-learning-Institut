{{--
    Partial ini menerima variabel:
    $badges (dikirim dari controller profil)
--}}

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 md:p-8">
        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
            Lencana Saya
        </h3>

        @if ($badges->isEmpty())
            <div class="mt-4 text-center border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6">
                <i class="fas fa-shield-alt text-4xl text-gray-400 dark:text-gray-500 mb-3"></i>
                <p class="text-gray-600 dark:text-gray-400">
                    Anda belum mendapatkan lencana. Selesaikan program untuk mendapatkannya!
                </p>
            </div>
        @else
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Lencana yang berhasil Anda kumpulkan:
            </p>
            <div class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($badges as $badge)
                    <div class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg text-center shadow-sm">
                        <img src="{{ Storage::url($badge->image_path) }}"
                             alt="{{ $badge->title }}"
                             class="w-24 h-24 mb-2">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $badge->title }}</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Didapat: {{ \Carbon\Carbon::parse($badge->pivot->earned_at)->translatedFormat('d F Y') }}
                        </p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
