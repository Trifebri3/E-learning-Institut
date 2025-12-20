@extends('superadmin.layouts.app')

@section('title', 'Detail Tiket #' . $ticket->id)

@section('content')
<div class="container mx-auto p-6 max-w-5xl">

    <div class="mb-6">
        <a href="{{ route('superadmin.support.index') }}" class="text-gray-500 hover:text-indigo-600 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- KOLOM KIRI: Detail Laporan -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Header Laporan -->
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="px-2 py-1 rounded text-xs font-bold bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 mb-2 inline-block">
                                #{{ $ticket->id }} &bull; {{ $ticket->category_label }}
                            </span>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $ticket->subject }}</h1>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-500">{{ $ticket->created_at->format('d M Y, H:i') }}</div>
                            @if($ticket->priority == 'high')
                                <span class="text-red-600 font-bold text-sm"><i class="fas fa-fire"></i> High Priority</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Isi Laporan -->
                <div class="p-6">
                    <h4 class="text-sm font-bold text-gray-500 uppercase mb-2">Deskripsi Masalah</h4>
                    <div class="prose dark:prose-invert max-w-none text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg border dark:border-gray-600">
                        {!! nl2br(e($ticket->description)) !!}
                    </div>

                    @if($ticket->attachment_path)
                        <div class="mt-6">
                            <h4 class="text-sm font-bold text-gray-500 uppercase mb-2">Lampiran</h4>
                            <a href="{{ Storage::url($ticket->attachment_path) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-lg hover:bg-indigo-100 border border-indigo-200 dark:border-indigo-700">
                                <i class="fas fa-paperclip mr-2"></i> Lihat Lampiran Bukti
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Balasan Admin -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-reply text-indigo-500 mr-2"></i> Tindakan & Balasan
                </h3>

                <form action="{{ route('superadmin.support.update', $ticket->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Update Status</label>
                            <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500">
                                <option value="open" @selected($ticket->status == 'open')>Baru (Open)</option>
                                <option value="in_progress" @selected($ticket->status == 'in_progress')>Sedang Diproses</option>
                                <option value="resolved" @selected($ticket->status == 'resolved')>Selesai (Resolved)</option>
                                <option value="closed" @selected($ticket->status == 'closed')>Tutup (Closed)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Pesan Balasan / Catatan Solusi</label>
                        <textarea name="admin_reply" rows="5" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500" placeholder="Tulis solusi atau tanggapan Anda di sini...">{{ $ticket->admin_reply }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Balasan ini akan terlihat oleh peserta di halaman detail tiket mereka.</p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow transition">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- KOLOM KANAN: Profil Pelapor -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 sticky top-6">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Profil Pelapor</h3>

                <div class="flex items-center gap-3 mb-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($ticket->user->name) }}" class="w-12 h-12 rounded-full">
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white">{{ $ticket->user->name }}</h4>
                        <p class="text-xs text-gray-500">{{ $ticket->user->email }}</p>
                    </div>
                </div>

                <div class="space-y-3 text-sm border-t border-gray-100 dark:border-gray-700 pt-4">
                    <div>
                        <span class="text-gray-500 block text-xs">Nomor HP:</span>
                        <span class="font-medium dark:text-gray-300">{{ $ticket->user->profile->nomor_hp ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs">Program Terkait:</span>
                        @if($ticket->program)
                            <a href="{{ route('superadmin.programs.edit', $ticket->program->id) }}" class="font-medium text-indigo-600 hover:underline">
                                {{ $ticket->program->title }}
                            </a>
                        @else
                            <span class="font-medium text-gray-400">- (Laporan Umum)</span>
                        @endif
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('superadmin.users.show', $ticket->user->id) }}" class="block w-full text-center py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm font-bold">
                        Lihat Profil Lengkap
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
