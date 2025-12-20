@extends('instructor.layouts.app')

@section('title', 'Edit Narasumber - ' . $narasumber->nama)

@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('instructor.narasumber.index', $program->id) }}" class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600 transition-colors mb-4">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Narasumber
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Narasumber</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Program: {{ $program->title }}</p>
    </div>

    <!-- Form Container -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
        <form action="{{ route('instructor.narasumber.update', [$program->id, $narasumber->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Current Photo Preview -->
            @if($narasumber->foto)
            <div class="mb-6 text-center">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Foto Saat Ini</label>
                <div class="inline-block relative">
                    <img src="{{ Storage::url($narasumber->foto) }}" alt="{{ $narasumber->nama }}"
                         class="w-32 h-32 rounded-full object-cover border-4 border-indigo-200 dark:border-indigo-600 shadow-lg">
                    <div class="absolute -bottom-2 -right-2 bg-indigo-600 text-white rounded-full p-1 shadow-lg">
                        <i class="fas fa-user text-xs"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Klik tombol hapus untuk menghapus foto</p>
            </div>
            @endif

            <!-- Nama -->
            <div class="mb-6">
                <label for="nama" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Nama Narasumber <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', $narasumber->nama) }}"
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                       placeholder="Masukkan nama lengkap narasumber" required>
                @error('nama')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jabatan & Kontak Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Jabatan -->
                <div>
                    <label for="jabatan" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Jabatan / Posisi
                    </label>
                    <input type="text" id="jabatan" name="jabatan" value="{{ old('jabatan', $narasumber->jabatan) }}"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                           placeholder="Contoh: Senior Developer, Project Manager">
                </div>

                <!-- Kontak -->
                <div>
                    <label for="kontak" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Kontak
                    </label>
                    <input type="text" id="kontak" name="kontak" value="{{ old('kontak', $narasumber->kontak) }}"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                           placeholder="Email atau nomor telepon">
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="mb-6">
                <label for="deskripsi" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Deskripsi & Keahlian
                </label>
                <textarea id="deskripsi" name="deskripsi" rows="4"
                          class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-vertical"
                          placeholder="Jelaskan latar belakang, pengalaman, dan keahlian narasumber...">{{ old('deskripsi', $narasumber->deskripsi) }}</textarea>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Deskripsi ini akan ditampilkan di halaman detail program.
                </p>
            </div>

            <!-- Foto Update -->
            <div class="mb-6">
                <label for="foto" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    {{ $narasumber->foto ? 'Ganti Foto Profil' : 'Upload Foto Profil' }}
                </label>
                <input type="file" id="foto" name="foto"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300 transition-colors">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Format: JPG, PNG, JPEG | Maksimal: 2MB
                </p>

                <!-- Remove Photo Option -->
                @if($narasumber->foto)
                <div class="mt-3 flex items-center">
                    <input type="checkbox" id="remove_foto" name="remove_foto" value="1"
                           class="text-red-600 focus:ring-red-500 rounded mr-2">
                    <label for="remove_foto" class="text-sm text-red-600 dark:text-red-400 cursor-pointer flex items-center">
                        <i class="fas fa-trash mr-1 text-xs"></i> Hapus foto saat ini
                    </label>
                </div>
                @endif

                @error('foto')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kelas yang Diampu -->
            <div class="mb-8">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                    Kelas yang Diampu
                </label>
                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                    @if($kelas->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto">
                            @foreach ($kelas as $k)
                                <label class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="kelas[]" value="{{ $k->id }}"
                                           {{ in_array($k->id, $narasumber->kelas->pluck('id')->toArray()) ? 'checked' : '' }}
                                           class="text-indigo-600 focus:ring-indigo-500 rounded mr-3">
                                    <div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $k->title }}</span>
                                        @if($k->tanggal)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ \Carbon\Carbon::parse($k->tanggal)->format('d M Y') }}
                                            </p>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-book-open text-gray-400 text-2xl mb-2"></i>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada kelas dalam program ini</p>
                        </div>
                    @endif
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    Pilih kelas yang akan diampu oleh narasumber ini (opsional)
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-between items-center pt-6 border-t border-gray-200 dark:border-gray-700 gap-4">
                <!-- Delete Button -->
                <button type="button"
                        onclick="confirmDelete()"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <i class="fas fa-trash mr-2"></i> Hapus Narasumber
                </button>

                <!-- Update Button -->
                <div class="flex gap-3">
                    <a href="{{ route('instructor.narasumber.index', $program->id) }}"
                       class="inline-flex items-center px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <i class="fas fa-save mr-2"></i> Update Narasumber
                    </button>
                </div>
            </div>
        </form>

        <!-- Delete Form -->
        <form id="delete-form" action="{{ route('instructor.narasumber.destroy', [$program->id, $narasumber->id]) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
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
                Apakah Anda yakin ingin menghapus narasumber <strong>{{ $narasumber->nama }}</strong>? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex gap-3 justify-center">
                <button type="button"
                        onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                    Batal
                </button>
                <button type="button"
                        onclick="deleteNarasumber()"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                    Ya, Hapus
                </button>
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

function deleteNarasumber() {
    document.getElementById('delete-form').submit();
}

// Close modal when clicking outside
document.getElementById('delete-modal').addEventListener('click', function(e) {
    if (e.target === this) {
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
