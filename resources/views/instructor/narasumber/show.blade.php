@extends('instructor.layouts.app')

@section('title', 'Detail Narasumber - ' . $narasumber->nama)

@section('content')
<div class="container mx-auto p-6 max-w-5xl">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('instructor.narasumber.index', $program->id) }}" class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600 transition-colors mb-4">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Narasumber
        </a>
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Narasumber</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Program: {{ $program->title }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('instructor.narasumber.edit', [$program->id, $narasumber->id]) }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Informasi Utama -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Card Informasi Narasumber -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-user-circle text-indigo-500 mr-2"></i> Informasi Narasumber
                </h2>

                <div class="space-y-4">
                    <!-- Nama -->
                    <div class="flex items-start">
                        <div class="w-32 flex-shrink-0">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-900 dark:text-white font-semibold text-lg">{{ $narasumber->nama }}</p>
                        </div>
                    </div>

                    <!-- Jabatan -->
                    @if($narasumber->jabatan)
                    <div class="flex items-start">
                        <div class="w-32 flex-shrink-0">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Jabatan</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-900 dark:text-white">{{ $narasumber->jabatan }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Kontak -->
                    @if($narasumber->kontak)
                    <div class="flex items-start">
                        <div class="w-32 flex-shrink-0">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Kontak</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-900 dark:text-white">{{ $narasumber->kontak }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Deskripsi -->
                    @if($narasumber->deskripsi)
                    <div class="flex items-start">
                        <div class="w-32 flex-shrink-0">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</span>
                        </div>
                        <div class="flex-1">
                            <div class="prose dark:prose-invert max-w-none text-gray-900 dark:text-white">
                                {!! nl2br(e($narasumber->deskripsi)) !!}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Tanggal Dibuat & Diupdate -->
                    <div class="flex items-start pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="w-32 flex-shrink-0">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Info</span>
                        </div>
                        <div class="flex-1">
                            <div class="space-y-1 text-sm text-gray-500 dark:text-gray-400">
                                <p>Dibuat: {{ $narasumber->created_at->format('d M Y H:i') }}</p>
                                <p>Diupdate: {{ $narasumber->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Kelas yang Diampu -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-book-open text-indigo-500 mr-2"></i> Kelas yang Diampu
                </h2>

                @if($narasumber->kelas->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($narasumber->kelas as $kelas)
                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $kelas->title }}</h3>
                                    <span class="px-2 py-1 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-300 text-xs font-medium rounded-full">
                                        Kelas
                                    </span>
                                </div>

                                @if($kelas->tanggal)
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-1">
                                        <i class="fas fa-calendar mr-2 text-xs"></i>
                                        {{ \Carbon\Carbon::parse($kelas->tanggal)->format('d M Y') }}
                                    </div>
                                @endif

                                @if($kelas->waktu_mulai)
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <i class="fas fa-clock mr-2 text-xs"></i>
                                        {{ \Carbon\Carbon::parse($kelas->waktu_mulai)->format('H:i') }}
                                        @if($kelas->waktu_selesai)
                                            - {{ \Carbon\Carbon::parse($kelas->waktu_selesai)->format('H:i') }}
                                        @endif
                                    </div>
                                @endif


                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-book-open text-gray-400 text-3xl mb-3"></i>
                        <p class="text-gray-500 dark:text-gray-400">Narasumber belum mengampu kelas apapun</p>
                        <a href="{{ route('instructor.narasumber.edit', [$program->id, $narasumber->id]) }}"
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg mt-3 transition-colors">
                            <i class="fas fa-plus mr-2"></i> Tambahkan Kelas
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Kolom Kanan: Foto & Actions -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Card Foto -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-camera text-indigo-500 mr-2"></i> Foto Profil
                </h3>

                <div class="text-center">
                    @if($narasumber->foto)
                        <div class="relative inline-block">
                            <img src="{{ Storage::url($narasumber->foto) }}"
                                 alt="{{ $narasumber->nama }}"
                                 class="w-48 h-48 rounded-full object-cover border-4 border-indigo-200 dark:border-indigo-600 shadow-lg mx-auto">
                            <div class="absolute -bottom-2 -right-2 bg-indigo-600 text-white rounded-full p-2 shadow-lg">
                                <i class="fas fa-user text-sm"></i>
                            </div>
                        </div>
                    @else
                        <div class="relative inline-block">
                            <div class="w-48 h-48 rounded-full bg-gray-200 dark:bg-gray-700 border-4 border-indigo-200 dark:border-indigo-600 flex items-center justify-center shadow-lg mx-auto">
                                <i class="fas fa-user text-gray-400 text-4xl"></i>
                            </div>
                            <div class="absolute -bottom-2 -right-2 bg-gray-500 text-white rounded-full p-2 shadow-lg">
                                <i class="fas fa-camera text-sm"></i>
                            </div>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-3">Tidak ada foto</p>
                    @endif
                </div>
            </div>

            <!-- Card Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-cog text-indigo-500 mr-2"></i> Aksi
                </h3>

                <div class="space-y-3">
                    <!-- Edit Button -->
                    <a href="{{ route('instructor.narasumber.edit', [$program->id, $narasumber->id]) }}"
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors">
                        <i class="fas fa-edit mr-2"></i> Edit Narasumber
                    </a>

                    <!-- Delete Button -->
                    <button type="button"
                            onclick="confirmDelete()"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors">
                        <i class="fas fa-trash mr-2"></i> Hapus Narasumber
                    </button>

                    <!-- Back to List -->
                    <a href="{{ route('instructor.narasumber.index', $program->id) }}"
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                        <i class="fas fa-list mr-2"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- Card Statistik -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-chart-bar text-indigo-500 mr-2"></i> Statistik
                </h3>

                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Total Kelas</span>
                        <span class="px-2 py-1 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-300 text-sm font-semibold rounded-full">
                            {{ $narasumber->kelas->count() }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Status</span>
                        <span class="px-2 py-1 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 text-sm font-semibold rounded-full">
                            Aktif
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Bergabung</span>
                        <span class="text-sm text-gray-900 dark:text-white font-medium">
                            {{ $narasumber->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-md mx-4">
        <div class="text-center">
            <div class="w-16 h-16 bg-red-100 dark:bg-red-900/50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Hapus Narasumber?</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Apakah Anda yakin ingin menghapus narasumber <strong>{{ $narasumber->nama }}</strong>?
                Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait.
            </p>
            <div class="flex gap-3 justify-center">
                <button type="button"
                        onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                    Batal
                </button>
                <form id="delete-form" action="{{ route('instructor.narasumber.destroy', [$program->id, $narasumber->id]) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    document.getElementById('delete-modal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('delete-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>

<style>
.hidden {
    display: none;
}
</style>
@endsection
