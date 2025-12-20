@extends('adminprogram.layouts.app')

@section('title', 'Detail Tiket #' . $ticket->id)

@section('content')
<div class="container mx-auto p-6 max-w-5xl">

    <div class="mb-6">
        <a href="{{ route('adminprogram.support.index') }}" class="text-gray-500 hover:text-indigo-600 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- KOLOM KIRI: Detail & Chat -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Isi Tiket -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-indigo-50 dark:bg-indigo-900/20">
                    <span class="px-2 py-1 rounded text-xs font-bold bg-white dark:bg-gray-700 text-indigo-700 dark:text-indigo-300 mb-2 inline-block">
                        {{ $ticket->category_label }} &bull; {{ $ticket->program->title }}
                    </span>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $ticket->subject }}</h1>
                </div>

                <div class="p-6">
                    <div class="prose dark:prose-invert max-w-none text-gray-800 dark:text-gray-200 mb-6">
                        {!! nl2br(e($ticket->description)) !!}
                    </div>

                    @if($ticket->attachment_path)
                        <a href="{{ Storage::url($ticket->attachment_path) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 text-sm font-bold">
                            <i class="fas fa-paperclip mr-2"></i> Lihat Lampiran Bukti
                        </a>
                    @endif
                </div>
            </div>

            <!-- Form Balasan -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-reply text-indigo-500 mr-2"></i> Tindakan & Respon
                </h3>

                <form action="{{ route('adminprogram.support.update', $ticket->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Status Tiket</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="status" value="in_progress" @checked($ticket->status == 'in_progress') class="text-yellow-500 focus:ring-yellow-500">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Sedang Diproses</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="status" value="resolved" @checked($ticket->status == 'resolved') class="text-green-500 focus:ring-green-500">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Selesai (Resolved)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="status" value="closed" @checked($ticket->status == 'closed') class="text-gray-500 focus:ring-gray-500">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Tutup (Closed)</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Balasan Anda</label>
                        <textarea name="admin_reply" rows="5" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500" placeholder="Berikan solusi atau tanggapan kepada peserta...">{{ $ticket->admin_reply }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow transition">
                            Update Tiket
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- KOLOM KANAN: Profil Pelapor -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 sticky top-6">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Identitas Peserta</h3>

                <div class="flex items-center gap-3 mb-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($ticket->user->name) }}" class="w-12 h-12 rounded-full">
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white">{{ $ticket->user->name }}</h4>
                        <p class="text-xs text-gray-500">{{ $ticket->user->email }}</p>
                    </div>
                </div>

                <div class="space-y-2 text-sm border-t border-gray-100 dark:border-gray-700 pt-4 mb-4">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Nomor HP:</span>
                        <span class="font-medium dark:text-gray-300">{{ $ticket->user->profile->nomor_hp ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tgl Lapor:</span>
                        <span class="font-medium dark:text-gray-300">{{ $ticket->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>

                <a href="{{ route('adminprogram.participants.index', ['search_participant' => $ticket->user->email]) }}" class="block w-full text-center py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 font-bold text-sm">
                    Cek Data Peserta Ini
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
