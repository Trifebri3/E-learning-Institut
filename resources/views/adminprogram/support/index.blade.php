@extends('adminprogram.layouts.app')

@section('title', 'Helpdesk Program')

@section('content')
<div class="container mx-auto p-6">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Bantuan Peserta</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Kelola pengajuan izin dan kendala akademik program Anda.</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex space-x-4 mb-6 overflow-x-auto">
        <a href="{{ route('adminprogram.support.index') }}"
           class="px-4 py-2 rounded-lg text-sm font-bold transition {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300' }}">
            Semua
        </a>
        <a href="{{ route('adminprogram.support.index', ['status' => 'open']) }}"
           class="px-4 py-2 rounded-lg text-sm font-bold transition {{ request('status') == 'open' ? 'bg-red-500 text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300' }}">
            Baru <span class="ml-2 bg-white/20 px-2 py-0.5 rounded-full text-xs">{{ $counts['open'] }}</span>
        </a>
        <a href="{{ route('adminprogram.support.index', ['status' => 'in_progress']) }}"
           class="px-4 py-2 rounded-lg text-sm font-bold transition {{ request('status') == 'in_progress' ? 'bg-yellow-500 text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300' }}">
            Diproses <span class="ml-2 bg-white/20 px-2 py-0.5 rounded-full text-xs">{{ $counts['process'] }}</span>
        </a>
        <a href="{{ route('adminprogram.support.index', ['status' => 'resolved']) }}"
           class="px-4 py-2 rounded-lg text-sm font-bold transition {{ request('status') == 'resolved' ? 'bg-green-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300' }}">
            Selesai <span class="ml-2 bg-white/20 px-2 py-0.5 rounded-full text-xs">{{ $counts['closed'] }}</span>
        </a>
    </div>

    <!-- Tabel -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 text-xs uppercase font-bold">
                <tr>
                    <th class="px-6 py-3">Prioritas</th>
                    <th class="px-6 py-3">Peserta</th>
                    <th class="px-6 py-3">Program</th>
                    <th class="px-6 py-3">Subjek</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Waktu</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($tickets as $ticket)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <td class="px-6 py-4">
                        @if($ticket->priority == 'high')
                            <span class="text-red-600 font-bold text-xs"><i class="fas fa-arrow-up"></i> Tinggi</span>
                        @elseif($ticket->priority == 'medium')
                            <span class="text-yellow-600 font-bold text-xs"><i class="fas fa-minus"></i> Sedang</span>
                        @else
                            <span class="text-blue-600 font-bold text-xs"><i class="fas fa-arrow-down"></i> Rendah</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                        {{ $ticket->user->name }}
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-500">
                        {{ $ticket->program->title }}
                    </td>
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300 truncate max-w-xs">
                        {{ $ticket->subject }}
                        <br>
                        <span class="text-[10px] text-gray-400 uppercase">{{ $ticket->category_label }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $colors = ['open' => 'bg-red-100 text-red-800', 'in_progress' => 'bg-yellow-100 text-yellow-800', 'resolved' => 'bg-green-100 text-green-800', 'closed' => 'bg-gray-100 text-gray-800'];
                            $labels = ['open' => 'Baru', 'in_progress' => 'Diproses', 'resolved' => 'Selesai', 'closed' => 'Tutup'];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $colors[$ticket->status] }}">
                            {{ $labels[$ticket->status] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-500">
                        {{ $ticket->created_at->diffForHumans() }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('adminprogram.support.show', $ticket->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold text-sm">
                            Respon
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-check-circle text-4xl mb-3 text-green-500"></i>
                        <p>Tidak ada tiket bantuan yang perlu ditangani.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $tickets->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
