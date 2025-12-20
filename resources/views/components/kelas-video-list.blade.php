@props(['videos'])

@if($videos->isNotEmpty())
    <div class="mt-8 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">

        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400">
                    <i class="fas fa-clapperboard text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm">Video Materi</h3>
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold">
                        Wajib Tonton
                    </p>
                </div>
            </div>

            @php
                $watchedCount = 0;
                foreach($videos as $video) {
                    $isWatched = Auth::user()
                        ->watchedVideos()
                        ->where('video_embed_user.video_embed_id', $video->id)
                        ->exists();
                    if($isWatched) $watchedCount++;
                }
            @endphp

            <div class="flex items-center gap-2">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                    {{ $watchedCount }} / {{ $videos->count() }}
                </span>
                <div class="w-16 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full bg-primary-500 rounded-full transition-all duration-500"
                         style="width: {{ ($videos->count() > 0) ? ($watchedCount / $videos->count()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>

        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach($videos as $video)
                @php
                    $isWatched = Auth::user()
                        ->watchedVideos()
                        ->where('video_embed_user.video_embed_id', $video->id)
                        ->exists();
                @endphp

                <a href="{{ route('participant.video.show', $video->id) }}"
                   class="group flex items-start gap-4 p-4 transition-all duration-200 hover:bg-gray-50 dark:hover:bg-gray-700/50">

                    <div class="flex-shrink-0 mt-0.5">
                        @if($isWatched)
                            <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center shadow-sm">
                                <i class="fas fa-check text-xs"></i>
                            </div>
                        @else
                            <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 text-gray-400 flex items-center justify-center group-hover:border-primary-400 group-hover:text-primary-500 transition-colors">
                                <i class="fas fa-play text-[10px] ml-0.5"></i>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors leading-tight">
                            {{ $video->title }}
                        </h4>

                        @if($video->description)
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-1 group-hover:line-clamp-2 transition-all">
                                {{ $video->description }}
                            </p>
                        @endif

                        <div class="md:hidden mt-2">
                            @if($isWatched)
                                <span class="text-[10px] text-green-600 font-medium bg-green-50 px-2 py-0.5 rounded">Selesai</span>
                            @else
                                <span class="text-[10px] text-gray-400 font-medium">Belum ditonton</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex-shrink-0 self-center">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-gray-300 group-hover:text-primary-500 group-hover:translate-x-1 transition-all">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif
