@extends('participant.layouts.app')

@section('title', $module->title)

@php
    $user = Auth::user();
@endphp

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8 border-b border-gray-200 dark:border-gray-700 pb-4">
            <a href="{{ route('participant.kelas.show', $module->kelas->id) }}"
               class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors mb-2">
                <i class="fas fa-arrow-left mr-2 text-xs"></i> Kembali ke Kelas
            </a>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white leading-tight">
                        {{ $module->title }}
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ $module->kelas->program->title }} &bull; {{ $module->kelas->title }}
                    </p>
                </div>

                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide border
                    {{ $module->is_mandatory
                        ? 'bg-red-50 text-red-700 border-red-100 dark:bg-red-900/20 dark:text-red-300 dark:border-red-800'
                        : 'bg-blue-50 text-blue-700 border-blue-100 dark:bg-blue-900/20 dark:text-blue-300 dark:border-blue-800' }}">
                    {{ $module->is_mandatory ? 'Materi Wajib' : 'Pengayaan' }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 lg:gap-12">

            <div class="lg:col-span-3">

                @if (session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800 flex items-center gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 dark:bg-green-800 text-green-600 dark:text-green-300 flex items-center justify-center">
                            <i class="fas fa-check"></i>
                        </div>
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                    </div>
                @endif

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 md:p-10">
                        <article class="prose prose-slate dark:prose-invert max-w-none prose-img:rounded-xl prose-headings:font-bold prose-a:text-primary-600">
                            {!! nl2br(e($module->content)) !!}
                        </article>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-6 md:px-10 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            @if($isCompleted)
                                <span class="flex items-center gap-2 text-green-600 dark:text-green-400 font-medium">
                                    <i class="fas fa-check-circle"></i> Materi ini telah diselesaikan
                                </span>
                            @else
                                <span>Silakan baca materi hingga tuntas.</span>
                            @endif
                        </div>

                        @php
                            $completeRoute = request()->is('participant/*') ? 'participant.module.complete' : 'module.complete';
                        @endphp

                        @if ($isCompleted)
                            <button disabled class="w-full sm:w-auto px-6 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 font-bold rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                                <i class="fas fa-check-double"></i> Selesai
                            </button>
                        @else
                            <form method="POST" action="{{ route($completeRoute, $module->id) }}" class="w-full sm:w-auto">
                                @csrf
                                <button type="submit"
                                        class="w-full sm:w-auto px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-sm hover:shadow transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                    <i class="fas fa-check"></i> Tandai Selesai
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                </div>

            <div class="lg:col-span-1">
                <div class="sticky top-24">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

                        <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50">
                            <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <i class="fas fa-list-ul text-primary-500"></i> Daftar Modul
                            </h3>
                        </div>

                        <div class="max-h-[70vh] overflow-y-auto custom-scrollbar">
                            @php
                                $showRoute = request()->is('participant/*') ? 'participant.module.show' : 'module.show';
                            @endphp

                            <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($module->kelas->modules as $mod)
                                    @php
                                        $isCurrent = $mod->id == $module->id;
                                        $isModCompleted = $mod->users()->where('user_id', $user->id)->exists();
                                    @endphp

                                    <li>
                                        <a href="{{ route($showRoute, $mod->id) }}"
                                           class="group flex items-start gap-3 p-4 transition-colors
                                           {{ $isCurrent
                                                ? 'bg-primary-50 dark:bg-primary-900/20 border-l-4 border-primary-500'
                                                : 'hover:bg-gray-50 dark:hover:bg-gray-700 border-l-4 border-transparent' }}">

                                            <div class="flex-shrink-0 mt-0.5">
                                                @if($isModCompleted)
                                                    <div class="w-5 h-5 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 flex items-center justify-center">
                                                        <i class="fas fa-check text-[10px]"></i>
                                                    </div>
                                                @elseif($isCurrent)
                                                    <div class="w-5 h-5 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 flex items-center justify-center animate-pulse">
                                                        <div class="w-2 h-2 rounded-full bg-primary-600"></div>
                                                    </div>
                                                @else
                                                    <div class="w-5 h-5 rounded-full border border-gray-300 dark:border-gray-500 text-gray-300 flex items-center justify-center">
                                                        <span class="text-[10px]">{{ $loop->iteration }}</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium leading-snug {{ $isCurrent ? 'text-primary-700 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 group-hover:text-gray-900' }}">
                                                    {{ $mod->title }}
                                                </p>
                                                @if($isCurrent)
                                                    <p class="text-xs text-primary-500 mt-1 font-semibold">Sedang dibuka</p>
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
