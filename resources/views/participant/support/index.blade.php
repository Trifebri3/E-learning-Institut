@extends('participant.layouts.app')

@section('title', 'Bantuan & Support')

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center shadow-sm">
                        <i class="fas fa-life-ring text-lg"></i>
                    </span>
                    Pusat Bantuan
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm md:text-base ml-14">
                    Solusi cepat untuk kendala teknis dan pertanyaan akademik Anda.
                </p>
            </div>
            <a href="{{ route('participant.support.create') }}"
               class="inline-flex items-center justify-center px-5 py-2.5 bg-gray-900 hover:bg-gray-800 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100 text-white font-semibold rounded-xl transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                <i class="fas fa-plus mr-2 text-xs"></i>
                Buat Tiket Baru
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 flex items-center gap-3">
                <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition-colors shadow-sm">
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-base">IT Support</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Kendala sistem & login</p>
                    </div>
                </div>
                <div class="space-y-2 pt-4 border-t border-dashed border-gray-100 dark:border-gray-700">
                    <a href="https://wa.me/82129711623" target="_blank" class="flex items-center text-sm text-gray-600 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 transition-colors gap-2">
                        <i class="fab fa-whatsapp"></i> +62 821-2971-1623 (IT Support)
                    </a>
                    <a href="mailto:it@lms.id" class="flex items-center text-sm text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors gap-2">
                        <i class="far fa-envelope"></i> instituthijau.id@gmail.com
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition-colors shadow-sm">
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-base">Admin Program</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Perizinan & Akademik</p>
                    </div>
                </div>
                <div class="space-y-2 pt-4 border-t border-dashed border-gray-100 dark:border-gray-700">
                    <a href="" target="_blank" class="flex items-center text-sm text-gray-600 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 transition-colors gap-2">
                        <i class="fab fa-whatsapp"></i> silahkan hubungi admin program
                    </a>
                    <a href="mailto:instituthijau.id@gmail.com" class="flex items-center text-sm text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors gap-2">
                        <i class="far fa-envelope"></i> -
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition-colors shadow-sm">
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-base">Layanan Aduan</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Etika & Keamanan</p>
                    </div>
                </div>
                <div class="space-y-2 pt-4 border-t border-dashed border-gray-100 dark:border-gray-700">
                    <a href="mailto:instituthijau.id@gmail.com" class="flex items-center text-sm text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition-colors gap-2">
                        <i class="far fa-envelope"></i> instituthijau.id@gmail.com
                    </a>
                    <span class="text-[10px] text-gray-400 block mt-1">
                        <i class="fas fa-lock mr-1"></i> Identitas pelapor dirahasiakan
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50">
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Riwayat Tiket</h3>
            </div>

            @if($tickets->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                    <div class="w-16 h-16 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4 text-gray-300 dark:text-gray-500">
                        <i class="fas fa-inbox text-3xl"></i>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada riwayat tiket bantuan.</p>
                </div>
            @else

                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                <th class="px-6 py-3 font-semibold">Status</th>
                                <th class="px-6 py-3 font-semibold">Judul Tiket</th>
                                <th class="px-6 py-3 font-semibold">Kategori</th>
                                <th class="px-6 py-3 font-semibold">Tanggal</th>
                                <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($tickets as $ticket)
                                @php
                                    $statusClasses = [
                                        'open' => 'bg-blue-50 text-blue-700 border-blue-100 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800',
                                        'in_progress' => 'bg-yellow-50 text-yellow-700 border-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-800',
                                        'resolved' => 'bg-green-50 text-green-700 border-green-100 dark:bg-green-900/30 dark:text-green-300 dark:border-green-800',
                                        'closed' => 'bg-gray-50 text-gray-600 border-gray-100 dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600'
                                    ];
                                    $statusLabels = [
                                        'open' => 'Baru', 'in_progress' => 'Proses', 'resolved' => 'Selesai', 'closed' => 'Ditutup'
                                    ];
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusClasses[$ticket->status] ?? $statusClasses['open'] }}">
                                            {{ $statusLabels[$ticket->status] ?? ucfirst($ticket->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-1">{{ $ticket->subject }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ Str::limit($ticket->description, 50) }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                        {{ $ticket->category_label ?? 'Umum' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $ticket->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <a href="{{ route('participant.support.show', $ticket->id) }}" class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 transition-colors">
                                            Lihat Detail <i class="fas fa-chevron-right text-xs ml-1"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($tickets as $ticket)
                        @php
                            $statusColors = [
                                'open' => 'text-blue-600 bg-blue-50',
                                'in_progress' => 'text-yellow-600 bg-yellow-50',
                                'resolved' => 'text-green-600 bg-green-50',
                                'closed' => 'text-gray-600 bg-gray-50'
                            ];
                            $statusLabels = [
                                'open' => 'Baru', 'in_progress' => 'Proses', 'resolved' => 'Selesai', 'closed' => 'Ditutup'
                            ];
                        @endphp
                        <a href="{{ route('participant.support.show', $ticket->id) }}" class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wide {{ $statusColors[$ticket->status] ?? $statusColors['open'] }}">
                                    {{ $statusLabels[$ticket->status] ?? ucfirst($ticket->status) }}
                                </span>
                                <span class="text-xs text-gray-400">{{ $ticket->created_at->diffForHumans() }}</span>
                            </div>
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1">{{ $ticket->subject }}</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1 mb-2">
                                {{ $ticket->category_label }} • {{ Str::limit($ticket->description, 60) }}
                            </p>
                            <div class="flex items-center text-primary-600 dark:text-primary-400 text-xs font-semibold">
                                Selengkapnya <i class="fas fa-arrow-right ml-1"></i>
                            </div>
                        </a>
                    @endforeach
                </div>

                @if($tickets->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                        {{ $tickets->links() }}
                    </div>
                @endif

            @endif
        </div>

    </div>
</div>
@endsection
