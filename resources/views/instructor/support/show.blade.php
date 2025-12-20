@extends('instructor.layouts.app')

@section('title', 'Detail Tiket #' . $ticket->id)

@section('content')
<div class="container mx-auto p-6 max-w-5xl">

    <div class="mb-6">
        <a href="{{ route('instructor.support.index') }}" class="text-gray-500 hover:text-indigo-600 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- KOLOM KIRI: Detail & Chat -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Isi Tiket -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-indigo-50 dark:bg-indigo-900/20">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-white dark:bg-gray-700 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-600 mb-2 inline-block">
                        {{ $ticket->getCategoryLabelAttribute() }} • {{ $ticket->program->title }}
                    </span>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $ticket->subject }}</h1>
                </div>

                <div class="p-6">
                    <div class="prose dark:prose-invert max-w-none text-gray-800 dark:text-gray-200 mb-6 bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4">
                        {!! nl2br(e($ticket->description)) !!}
                    </div>

                    @if($ticket->attachment_path)
                        <a href="{{ Storage::url($ticket->attachment_path) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/30 border border-indigo-200 dark:border-indigo-700 transition-colors">
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

                <form action="{{ route('instructor.support.update', $ticket->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Status Tiket</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <label class="flex items-center p-3 border-2 rounded-lg cursor-pointer transition-all
                                {{ $ticket->status == 'in_progress' ? 'border-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-indigo-300' }}">
                                <input type="radio" name="status" value="in_progress"
                                       {{ $ticket->status == 'in_progress' ? 'checked' : '' }}
                                       class="text-indigo-500 focus:ring-indigo-500 mr-3">
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">Sedang Diproses</div>
                                    <div class="text-xs text-gray-500">Tim sedang menangani</div>
                                </div>
                            </label>

                            <label class="flex items-center p-3 border-2 rounded-lg cursor-pointer transition-all
                                {{ $ticket->status == 'resolved' ? 'border-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-indigo-300' }}">
                                <input type="radio" name="status" value="resolved"
                                       {{ $ticket->status == 'resolved' ? 'checked' : '' }}
                                       class="text-indigo-500 focus:ring-indigo-500 mr-3">
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">Selesai</div>
                                    <div class="text-xs text-gray-500">Masalah teratasi</div>
                                </div>
                            </label>

                            <label class="flex items-center p-3 border-2 rounded-lg cursor-pointer transition-all
                                {{ $ticket->status == 'closed' ? 'border-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-indigo-300' }}">
                                <input type="radio" name="status" value="closed"
                                       {{ $ticket->status == 'closed' ? 'checked' : '' }}
                                       class="text-indigo-500 focus:ring-indigo-500 mr-3">
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">Tutup</div>
                                    <div class="text-xs text-gray-500">Tiket selesai</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Balasan Anda</label>
                        <textarea name="admin_reply" rows="5" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="Berikan solusi atau tanggapan kepada peserta...">{{ $ticket->admin_reply }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <i class="fas fa-save mr-2"></i>Update Tiket
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- KOLOM KANAN: Profil Pelapor -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 sticky top-6">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center">
                    <i class="fas fa-user-circle mr-2"></i> Identitas Peserta
                </h3>

                <div class="flex items-center gap-3 mb-4 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($ticket->user->name) }}&background=6366f1&color=ffffff" class="w-12 h-12 rounded-full border-2 border-indigo-200 dark:border-indigo-600">
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white">{{ $ticket->user->name }}</h4>
                        <p class="text-xs text-gray-500">{{ $ticket->user->email }}</p>
                    </div>
                </div>

                <div class="space-y-3 text-sm border-t border-gray-100 dark:border-gray-700 pt-4 mb-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 flex items-center">
                            <i class="fas fa-phone mr-2 text-xs"></i>Nomor HP:
                        </span>
                        <span class="font-medium dark:text-gray-300 text-right">
                            {{ $ticket->user->profile->nomor_hp ?? '-' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 flex items-center">
                            <i class="fas fa-calendar mr-2 text-xs"></i>Tgl Lapor:
                        </span>
                        <span class="font-medium dark:text-gray-300">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 flex items-center">
                            <i class="fas fa-clock mr-2 text-xs"></i>Durasi:
                        </span>
                        <span class="font-medium dark:text-gray-300">{{ $ticket->created_at->diffForHumans() }}</span>
                    </div>
                </div>

=
            </div>
        </div>

    </div>
</div>

<!-- Success Message -->
@if(session('success'))
<div class="fixed bottom-4 right-4 bg-indigo-500 text-white px-6 py-3 rounded-lg shadow-lg animate-fade-in-up">
    <div class="flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}
    </div>
</div>
@endif

<style>
.animate-fade-in-up {
    animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endsection
