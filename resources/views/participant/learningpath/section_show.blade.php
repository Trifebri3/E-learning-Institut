@extends('participant.layouts.app')

@section('title', $section->title)

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8 border-b border-gray-200 dark:border-gray-700 pb-4">
            <a href="{{ route('participant.kelas.show', $kelas->id) }}"
               class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors mb-2">
                <i class="fas fa-arrow-left mr-2 text-xs"></i> Kembali ke Kelas
            </a>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white leading-tight">
                        {{ $section->title }}
                    </h1>
                    <div class="flex items-center gap-3 mt-2 text-sm text-gray-500 dark:text-gray-400">
                        <span class="flex items-center gap-1.5 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded text-xs font-semibold">
                            <i class="fas fa-layer-group"></i> Bagian {{ $section->order }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <i class="far fa-clock"></i> {{ $section->estimated_minutes ?? 10 }} Menit Baca
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 lg:gap-12">

            <div class="lg:col-span-3">

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

                    @if ($section->image_path)
                    <div class="w-full h-64 md:h-80 bg-gray-100 dark:bg-gray-700 relative group">
                        <img src="{{ asset('storage/' . $section->image_path) }}"
                             alt="{{ $section->title }}"
                             class="w-full h-full object-cover">
                        @if($section->image_caption)
                        <div class="absolute bottom-0 left-0 right-0 bg-black/50 backdrop-blur-sm p-2 text-center">
                            <p class="text-xs text-white/90 italic">{{ $section->image_caption }}</p>
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="p-6 md:p-10">
                        <article class="prose prose-slate dark:prose-invert max-w-none prose-img:rounded-xl prose-headings:font-bold prose-a:text-primary-600 leading-relaxed">
                            {!! nl2br(e($section->content)) !!}
                        </article>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-6 md:px-10 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="text-sm text-gray-500 dark:text-gray-400 text-center sm:text-left">
                                <p>Selesaikan membaca untuk melanjutkan.</p>
                                <p class="text-xs mt-0.5">Progress Anda tersimpan otomatis.</p>
                            </div>

                            @php
                                $isCompleted = auth()->user()->completedPathSections->contains($section->id);
                            @endphp

                            @if ($isCompleted)
                                <button disabled class="w-full sm:w-auto px-6 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 font-bold rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                                    <i class="fas fa-check-double"></i> Selesai
                                </button>
                            @else
                                <form action="{{ route('participant.learningpath.section.complete', $section->id) }}" method="POST" class="w-full sm:w-auto">
                                    @csrf
                                    <button type="submit"
                                            class="w-full sm:w-auto px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-sm hover:shadow transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                        <i class="fas fa-check-circle"></i> Tandai Selesai
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-8">
                    @if($previousSection)
                    <a href="{{ route('participant.learningpath.section.show', $previousSection->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-300 hover:border-primary-500 hover:text-primary-600 transition-all shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i> Sebelumnya
                    </a>
                    @else
                    <div></div>
                    @endif

                    @if($nextSection)
                    <a href="{{ route('participant.learningpath.section.show', $nextSection->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-300 hover:border-primary-500 hover:text-primary-600 transition-all shadow-sm">
                        Selanjutnya <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                    @endif
                </div>

            </div>

            <div class="lg:col-span-1">
                <div class="sticky top-24">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

                        <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50">
                            <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2 text-sm uppercase tracking-wider">
                                <i class="fas fa-list-ul text-primary-500"></i> Daftar Materi
                            </h3>
                        </div>

                        <div class="max-h-[70vh] overflow-y-auto custom-scrollbar">
                            <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($kelas->learningPath->sections as $sec)
                                    @php
                                        $isCurrent = $sec->id == $section->id;
                                        $isSecCompleted = auth()->user()->completedPathSections->contains($sec->id);
                                    @endphp

                                    <li>
                                        <a href="{{ route('participant.learningpath.section.show', $sec->id) }}"
                                           class="group flex items-start gap-3 p-4 transition-colors relative
                                           {{ $isCurrent
                                                ? 'bg-primary-50 dark:bg-primary-900/20'
                                                : 'hover:bg-gray-50 dark:hover:bg-gray-700' }}">

                                            @if($isCurrent)
                                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary-500"></div>
                                            @endif

                                            <div class="flex-shrink-0 mt-0.5">
                                                @if($isSecCompleted)
                                                    <div class="w-5 h-5 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 flex items-center justify-center">
                                                        <i class="fas fa-check text-[10px]"></i>
                                                    </div>
                                                @elseif($isCurrent)
                                                    <div class="w-5 h-5 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 flex items-center justify-center animate-pulse">
                                                        <div class="w-2 h-2 rounded-full bg-primary-600"></div>
                                                    </div>
                                                @else
                                                    <div class="w-5 h-5 rounded-full border border-gray-300 dark:border-gray-500 text-gray-400 flex items-center justify-center text-[10px] font-medium">
                                                        {{ $sec->order }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium leading-snug {{ $isCurrent ? 'text-primary-700 dark:text-primary-400' : 'text-gray-600 dark:text-gray-300 group-hover:text-gray-900' }}">
                                                    {{ $sec->title }}
                                                </p>
                                                @if($isCurrent)
                                                    <p class="text-[10px] text-primary-500 mt-1 font-semibold uppercase tracking-wide">Sedang Dibaca</p>
                                                @endif
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
