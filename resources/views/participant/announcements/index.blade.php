@extends('participant.layouts.app')

@section('title', 'Papan Pengumuman')

@section('content')
<div class="container mx-auto p-4 md:p-6 lg:p-8 max-w-5xl">

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2 flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center shadow-sm">
                    <i class="fas fa-bullhorn text-lg"></i>
                </span>
                Papan Pengumuman
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm md:text-base ml-14">
                Pusat informasi terbaru dari Admin dan Program Anda.
            </p>
        </div>
    </div>

    @if($announcements->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-inbox text-3xl text-gray-300 dark:text-gray-500"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Belum ada pengumuman</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Informasi terbaru akan muncul di sini.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($announcements as $announcement)
                @php
                    $isRead = $announcement->isReadByCurrentUser();

                    // Logic Warna Netral & Status
                    if ($isRead) {
                        $containerClass = 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 opacity-75 hover:opacity-100';
                        $statusBadge = 'bg-gray-100 text-gray-500 border-gray-200';
                        $iconColor = 'text-gray-400';
                    } else {
                        if ($announcement->priority == 'critical') {
                            $containerClass = 'bg-white dark:bg-gray-800 border-red-200 dark:border-red-900 shadow-md ring-1 ring-red-100 dark:ring-red-900/50';
                            $statusBadge = 'bg-red-50 text-red-600 border-red-100';
                            $iconColor = 'text-red-500';
                        } else {
                            // Default / Normal Priority (Primary Theme)
                            $containerClass = 'bg-white dark:bg-gray-800 border-primary-200 dark:border-primary-800 shadow-sm';
                            $statusBadge = 'bg-primary-50 text-primary-700 border-primary-100';
                            $iconColor = 'text-primary-500';
                        }
                    }

                    $typeIcon = $announcement->type == 'global' ? 'fas fa-globe' : 'fas fa-chalkboard-teacher';
                    $typeLabel = $announcement->type == 'global' ? 'Info Umum' : $announcement->program->title;
                @endphp

                <div class="group relative rounded-2xl border {{ $containerClass }} p-5 transition-all duration-300 hover:shadow-lg">

                    {{-- Header Kartu: Tipe, Prioritas, Waktu --}}
                    <div class="flex flex-wrap justify-between items-start gap-3 mb-3">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                <i class="{{ $typeIcon }} mr-1.5 {{ $iconColor }}"></i>
                                {{ $typeLabel }}
                            </span>

                            @if($announcement->priority == 'critical')
                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-800">
                                    <i class="fas fa-exclamation-circle mr-1"></i> Penting
                                </span>
                            @endif

                            @if(!$isRead)
                                <span class="flex h-2.5 w-2.5 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-primary-500"></span>
                                </span>
                            @endif
                        </div>

                        <span class="text-xs font-medium text-gray-400 flex items-center gap-1">
                            <i class="far fa-clock"></i>
                            {{ $announcement->created_at->diffForHumans() }}
                        </span>
                    </div>

                    {{-- Konten Utama --}}
                    <div class="pl-1">
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-gray-100 mb-2 group-hover:text-primary-700 dark:group-hover:text-primary-400 transition-colors">
                            {{ $announcement->title }}
                        </h3>

                        <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 mb-4 leading-relaxed">
                            {!! nl2br(e($announcement->content)) !!}
                        </div>

                        @if($announcement->attachment_path)
                            <div class="mb-5">
                                <a href="{{ Storage::url($announcement->attachment_path) }}" target="_blank"
                                   class="inline-flex items-center px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors group/file">
                                    <div class="w-8 h-8 rounded bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-500 flex items-center justify-center mr-2 text-gray-500 group-hover/file:text-primary-600">
                                        <i class="fas fa-paperclip"></i>
                                    </div>
                                    <span>Lihat Lampiran</span>
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Footer: Creator & Action --}}
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">

                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs text-gray-500 font-bold">
                                {{ substr($announcement->creator->name, 0, 1) }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $announcement->creator->name }}</span>
                                <span class="opacity-75">• {{ ucfirst($announcement->creator->role) }}</span>
                            </div>
                        </div>

                        <div>
                            @if($isRead)
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-50 dark:bg-gray-700/50 text-gray-400 text-xs font-semibold cursor-default">
                                    <i class="fas fa-check-double text-[10px]"></i> Dibaca
                                </div>
                            @else
                                <form action="{{ route('participant.announcements.read', $announcement->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 w-full sm:w-auto justify-center">
                                        <i class="fas fa-check"></i> Tandai Dibaca
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $announcements->links() }}
        </div>
    @endif
</div>
@endsection
