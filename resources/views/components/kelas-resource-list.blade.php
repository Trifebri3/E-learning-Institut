@props(['resources'])

<div class="mt-8">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400">
            <i class="fas fa-folder-open text-lg"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white leading-tight">
                Materi Pendukung
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                Dokumen dan tautan referensi belajar.
            </p>
        </div>
    </div>

    @if($resources->isEmpty())
        <div class="p-8 text-center bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
            <div class="w-16 h-16 mx-auto bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mb-3 shadow-sm">
                <i class="fas fa-box-open text-gray-300 dark:text-gray-600 text-2xl"></i>
            </div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Belum ada materi tambahan yang diunggah.
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($resources as $resource)
                @php
                    $isOpened = $resource->users->isNotEmpty();
                    $fileType = $resource->file_path ? 'pdf' : 'link'; // Bisa dikembangkan logic deteksi tipe file

                    // Icon Mapping
                    $iconClass = match($fileType) {
                        'pdf' => 'fas fa-file-pdf',
                        'link' => 'fas fa-link',
                        default => 'fas fa-file-alt'
                    };

                    // Color Logic (Neutral vs Active)
                    $cardClass = $isOpened
                        ? 'bg-white dark:bg-gray-800 border-primary-200 dark:border-primary-800'
                        : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700';

                    $iconBg = $isOpened
                        ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400'
                        : 'bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500';
                @endphp

                <a href="{{ route('participant.materi.show', $resource->id) }}"
                   target="_blank"
                   class="group relative flex items-start gap-4 p-4 rounded-xl border {{ $cardClass }} shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 overflow-hidden">

                    @if($isOpened)
                        <div class="absolute top-0 left-0 w-1 h-full bg-primary-500"></div>
                    @endif

                    <div class="flex-shrink-0 w-12 h-12 rounded-lg {{ $iconBg }} flex items-center justify-center transition-colors group-hover:bg-primary-100 group-hover:text-primary-600 dark:group-hover:bg-primary-900/40">
                        <i class="{{ $iconClass }} text-xl"></i>
                    </div>

                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white line-clamp-1 mb-1 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                            {{ $resource->title }}
                        </h4>

                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                                {{ $resource->file_path ? 'Dokumen' : 'Tautan Luar' }}
                            </span>

                            @if($isOpened)
                                <span class="flex items-center gap-1 text-[10px] uppercase font-bold text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/30 px-2 py-0.5 rounded">
                                    <i class="fas fa-check"></i> Diakses
                                </span>
                            @else
                                <span class="flex items-center gap-1 text-[10px] uppercase font-bold text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded">
                                    Baru
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity text-gray-300">
                        <i class="fas fa-external-link-alt text-xs"></i>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
