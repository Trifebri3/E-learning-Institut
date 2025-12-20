{{-- File: resources/views/participant/materi/partials/item.blade.php --}}
@php
    $isOpened = $resource->users->isNotEmpty();
    // Icon tetap menggunakan pdf/link untuk visual, tapi link utama akan ke halaman detail
    $icon = $resource->file_path ? 'fas fa-file-pdf' : 'fas fa-link';
    $iconColor = $resource->file_path ? 'text-red-500' : 'text-blue-500';
@endphp

<a href="{{ route('participant.materi.show', $resource->id) }}"
   class="group/item flex items-start gap-3 p-3 rounded-xl border border-transparent hover:border-gray-200 dark:hover:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">

    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-white dark:bg-gray-700 border border-gray-100 dark:border-gray-600 flex items-center justify-center text-gray-400 group-hover/item:border-primary-200 group-hover/item:text-primary-500 transition-colors">
        <i class="{{ $icon }} text-xs {{ $iconColor }}"></i>
    </div>

    <div class="flex-1 min-w-0">
        <div class="flex items-center justify-between gap-2">
            <p class="text-sm font-medium text-gray-700 dark:text-gray-200 group-hover/item:text-primary-600 dark:group-hover/item:text-primary-400 truncate transition-colors">
                {{ $resource->title }}
            </p>
            @if($isOpened)
                <i class="fas fa-check-circle text-[10px] text-green-500" title="Sudah Dibuka"></i>
            @endif
        </div>
        <p class="text-[10px] text-gray-400 dark:text-gray-500 truncate mt-0.5">
            {{ $resource->kelas->title }}
        </p>
    </div>
</a>
