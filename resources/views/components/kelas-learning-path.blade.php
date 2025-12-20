<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">

    <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400 shadow-sm">
                <i class="fas fa-map-signs text-lg"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white leading-tight">
                    Alur Pembelajaran
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    Selesaikan materi secara berurutan.
                </p>
            </div>
        </div>
    </div>

    <div class="p-0">

        @if (empty($learningPath))
            <div class="text-center py-10 px-6">
                <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                    <i class="fas fa-book-open text-gray-400 text-xl"></i>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">
                    Materi pembelajaran sedang dipersiapkan.
                </p>
            </div>
            @php return; @endphp
        @endif

        {{-- VALIDASI SECTIONS KOSONG --}}
        @if ($learningPath->sections->isEmpty())
            <div class="text-center py-10 px-6">
                <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                    <i class="fas fa-folder-open text-gray-400 text-xl"></i>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">
                    Belum ada materi yang tersedia.
                </p>
            </div>
            @php return; @endphp
        @endif

        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach ($learningPath->sections as $section)
                @php
                    $isCompleted = auth()->user()->completedPathSections->contains($section->id);
                @endphp

                <a href="{{ route('participant.learningpath.section.show', $section->id) }}"
                   class="group relative flex items-start gap-4 p-5 transition-all duration-200 hover:bg-gray-50 dark:hover:bg-gray-700/50">

                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>

                    <div class="flex-shrink-0 mt-1">
                        @if($isCompleted)
                            <div class="w-6 h-6 rounded-full bg-primary-600 text-white flex items-center justify-center shadow-sm">
                                <i class="fas fa-check text-[10px]"></i>
                            </div>
                        @else
                            <div class="w-6 h-6 rounded-full border-2 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 flex items-center justify-center text-xs font-bold group-hover:border-primary-400 group-hover:text-primary-500 transition-colors">
                                {{ $section->order }}
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors mb-1 pr-4 leading-snug">
                                {{ $section->title }}
                            </h3>

                            <i class="fas fa-chevron-right text-xs text-gray-300 group-hover:text-primary-400 transition-colors mt-1"></i>
                        </div>

                        @if($section->description)
                            <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 leading-relaxed">
                                {{ $section->description }}
                            </p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <div class="px-5 py-3 bg-gray-50 dark:bg-gray-900 border-t border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
            <span>
                Total: <span class="font-bold text-gray-700 dark:text-gray-200">{{ $learningPath->sections->count() }}</span> Materi
            </span>

            <div class="flex items-center gap-1.5">
                <div class="w-2 h-2 rounded-full bg-primary-500"></div>
                <span>
                    {{ auth()->user()->completedPathSections->whereIn('id', $learningPath->sections->pluck('id'))->count() }} Selesai
                </span>
            </div>
        </div>
    </div>

</div>
