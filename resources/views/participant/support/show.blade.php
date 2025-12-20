@extends('participant.layouts.app')

@section('title', $ticket->subject)

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-6">
            <a href="{{ route('participant.support.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors">
                <i class="fas fa-arrow-left mr-2 text-xs"></i> Kembali ke Pusat Bantuan
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 md:p-8 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex flex-wrap items-center gap-2 mb-4">
                            @php
                                $statusClasses = [
                                    'open' => 'bg-blue-50 text-blue-700 border-blue-100 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800',
                                    'in_progress' => 'bg-yellow-50 text-yellow-700 border-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-800',
                                    'resolved' => 'bg-green-50 text-green-700 border-green-100 dark:bg-green-900/30 dark:text-green-300 dark:border-green-800',
                                    'closed' => 'bg-gray-50 text-gray-600 border-gray-100 dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600'
                                ];
                                $statusLabels = [
                                    'open' => 'Baru', 'in_progress' => 'Diproses', 'resolved' => 'Selesai', 'closed' => 'Ditutup'
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold border uppercase tracking-wider {{ $statusClasses[$ticket->status] ?? $statusClasses['open'] }}">
                                {{ $statusLabels[$ticket->status] ?? ucfirst($ticket->status) }}
                            </span>

                            @php
                                $priorityColors = [
                                    'low' => 'text-gray-500 bg-gray-100 dark:bg-gray-700',
                                    'medium' => 'text-orange-600 bg-orange-50 dark:bg-orange-900/20',
                                    'high' => 'text-red-600 bg-red-50 dark:bg-red-900/20'
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $priorityColors[$ticket->priority] ?? 'text-gray-500 bg-gray-100' }}">
                                Prioritas: {{ ucfirst($ticket->priority) }}
                            </span>
                        </div>

                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2 leading-tight">
                            {{ $ticket->subject }}
                        </h1>
                        <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 gap-2">
                            <span>Dibuat pada {{ $ticket->created_at->format('d M Y, H:i') }}</span>
                            <span>•</span>
                            <span>ID: #{{ $ticket->id }}</span>
                        </div>
                    </div>

                    <div class="p-6 md:p-8">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-3">Deskripsi Masalah</h3>
                        <div class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                            {{ $ticket->description }}
                        </div>
                    </div>
                </div>

                @if($ticket->admin_reply)
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 md:p-8 relative">
                        <div class="absolute top-6 right-6">
                            <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-700 flex items-center justify-center text-primary-600 shadow-sm border border-gray-100 dark:border-gray-600">
                                <i class="fas fa-reply"></i>
                            </div>
                        </div>

                        <h3 class="text-sm font-bold text-primary-700 dark:text-primary-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                            Balasan dari Admin
                        </h3>

                        <div class="prose prose-sm dark:prose-invert max-w-none text-gray-800 dark:text-gray-200 leading-relaxed whitespace-pre-line bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                            {{ $ticket->admin_reply }}
                        </div>

                        @if($ticket->updated_at)
                            <div class="mt-4 text-right">
                                <span class="text-xs text-gray-400 italic">
                                    Diupdate: {{ $ticket->updated_at->diffForHumans() }}
                                </span>
                            </div>
                        @endif
                    </div>
                @endif

            </div>

            <div class="space-y-6">

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="far fa-clipboard text-gray-400"></i> Detail Tiket
                    </h3>

                    <ul class="space-y-4 text-sm">
                        <li class="flex flex-col">
                            <span class="text-xs text-gray-500 dark:text-gray-400 mb-1">Kategori</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $ticket->category_label }}</span>
                        </li>

                        @if($ticket->program)
                        <li class="flex flex-col pt-3 border-t border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-500 dark:text-gray-400 mb-1">Program Terkait</span>
                            <span class="font-medium text-primary-600 dark:text-primary-400">{{ $ticket->program->title }}</span>
                        </li>
                        @endif

                        <li class="flex flex-col pt-3 border-t border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-500 dark:text-gray-400 mb-1">Terakhir Update</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $ticket->updated_at->format('d M Y, H:i') }}</span>
                        </li>
                    </ul>
                </div>

                @if($ticket->attachment_path)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-paperclip text-gray-400"></i> Lampiran
                    </h3>

                    <a href="{{ Storage::url($ticket->attachment_path) }}" target="_blank"
                       class="group flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-primary-300 dark:hover:border-primary-500 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-600 flex items-center justify-center text-gray-500 dark:text-gray-300 group-hover:text-primary-600 group-hover:bg-primary-50 transition-colors">
                            <i class="fas fa-file-download text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200 group-hover:text-primary-700 dark:group-hover:text-primary-300 truncate">
                                Download File
                            </p>
                            <p class="text-xs text-gray-400 truncate">Klik untuk mengunduh</p>
                        </div>
                    </a>
                </div>
                @endif

                <div class="bg-blue-50 dark:bg-blue-900/10 rounded-2xl border border-blue-100 dark:border-blue-800 p-5">
                    <div class="flex gap-3">
                        <i class="fas fa-headset text-blue-500 mt-1"></i>
                        <div>
                            <h4 class="text-sm font-bold text-blue-900 dark:text-blue-100 mb-1">Butuh respon cepat?</h4>
                            <p class="text-xs text-blue-700 dark:text-blue-300 leading-relaxed mb-3">
                                Jika masalah bersifat mendesak, silakan hubungi admin via WhatsApp.
                            </p>
                            <a href="https://wa.me/6281234567890" target="_blank" class="text-xs font-bold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 inline-flex items-center">
                                Chat WhatsApp <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
