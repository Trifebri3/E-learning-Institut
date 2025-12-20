@props(['narasumber'])

<a href="{{ route('participant.narasumber.show', $narasumber->id) }}"
   class="group block bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-all duration-300 hover:-translate-y-0.5 hover:border-primary-300 dark:hover:border-primary-700 hover:bg-gray-50/50 dark:hover:bg-gray-750">

    <div class="flex items-start gap-4">

        <div class="flex-shrink-0 relative">
            <div class="w-14 h-14 rounded-full overflow-hidden border-2 border-gray-100 dark:border-gray-600 group-hover:border-primary-200 dark:group-hover:border-primary-800 transition-colors duration-200 shadow-sm">
                <img class="w-full h-full object-cover"
                     src="{{ $narasumber->foto_path ? Storage::url($narasumber->foto_path) : 'https://ui-avatars.com/api/?name=' . urlencode($narasumber->nama) . '&color=7F9CF5&background=EBF4FF' }}"
                     alt="{{ $narasumber->nama }}"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($narasumber->nama) }}&color=7F9CF5&background=EBF4FF'">
            </div>

            @if($narasumber->is_verified)
                <div class="absolute -bottom-0.5 -right-0.5 w-5 h-5 bg-blue-500 text-white rounded-full border-2 border-white dark:border-gray-800 flex items-center justify-center shadow-sm z-10" title="Terverifikasi">
                    <i class="fas fa-check text-[10px]"></i>
                </div>
            @else
                <div class="absolute -bottom-0.5 -right-0.5 w-5 h-5 bg-gray-400 text-white rounded-full border-2 border-white dark:border-gray-800 flex items-center justify-center shadow-sm z-10" title="Pemateri">
                    <i class="fas fa-microphone text-[10px]"></i>
                </div>
            @endif
        </div>

        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-1">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                    {{ $narasumber->jabatan ?? 'Pemateri' }}
                </span>

                @if($narasumber->rating)
                    <div class="flex items-center gap-1 text-orange-400 bg-orange-50 dark:bg-orange-900/20 px-1.5 py-0.5 rounded-md">
                        <i class="fas fa-star text-[10px]"></i>
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ number_format($narasumber->rating, 1) }}</span>
                    </div>
                @endif
            </div>

            <h3 class="text-base font-bold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-200 mb-1 leading-tight line-clamp-1">
                {{ $narasumber->nama }}
            </h3>

            <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mb-3 leading-relaxed">
                {{ $narasumber->deskripsi }}
            </p>

            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[10px] text-gray-400 font-medium">
                @if($narasumber->pengalaman)
                    <span class="flex items-center gap-1">
                        <i class="fas fa-briefcase text-gray-300"></i>
                        {{ $narasumber->pengalaman }}
                    </span>
                @endif

                @if($narasumber->total_kelas)
                    <span class="flex items-center gap-1">
                        <i class="fas fa-chalkboard-teacher text-gray-300"></i>
                        {{ $narasumber->total_kelas }} Kelas
                    </span>
                @endif
            </div>
        </div>

        <div class="hidden sm:flex flex-shrink-0 self-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 -ml-2 group-hover:ml-0">
            <i class="fas fa-chevron-right text-gray-400 group-hover:text-primary-500"></i>
        </div>
    </div>
</a>
