@extends('instructor.layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-6xl" x-data="{ activeTab: 'info' }">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Manajemen Kelas: {{ $kelas->title }}</h1>
            <p class="text-gray-500 text-sm">{{ $kelas->program->title }} &bull; {{ ucfirst($kelas->tipe) }}</p>
        </div>
        <a href="{{ route('instructor.kelas.index') }}" class="text-gray-500 hover:text-indigo-600 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- TAB NAVIGATION -->
    <div class="flex space-x-1 bg-white dark:bg-gray-800 p-1 rounded-xl shadow mb-6 overflow-x-auto">
        @php
            $tabs = [
                'info' => ['label' => 'Info & Instruktur', 'icon' => 'fas fa-info-circle'],
                'presensi' => ['label' => 'Presensi', 'icon' => 'fas fa-user-check'],
                'materi' => ['label' => 'Materi & Modul', 'icon' => 'fas fa-book'],
                'tugas' => ['label' => 'Tugas & Ujian', 'icon' => 'fas fa-tasks']
            ];
        @endphp

        @foreach($tabs as $key => $tab)
            <button
                @click="activeTab = '{{ $key }}'"
                :class="activeTab === '{{ $key }}' ? 'bg-indigo-600 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'"
                class="px-4 py-2 rounded-lg font-bold text-sm transition flex-shrink-0">
                <i class="{{ $tab['icon'] }} mr-2"></i> {{ $tab['label'] }}
            </button>
        @endforeach
    </div>

    <!-- CONTENT -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">

        <!-- TAB 1: INFO -->
        <div x-show="activeTab === 'info'" x-transition>
            <form action="{{ route('instructor.kelas.update', $kelas->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="tipe" value="{{ $kelas->tipe }}">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                    <!-- Info Dasar -->
                    <div class="space-y-4">
                        <h3 class="font-bold text-gray-700 dark:text-gray-200 border-b pb-2 mb-4">Informasi Dasar</h3>

                        <div>
                            <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Judul Kelas</label>
                            <input type="text" name="title" value="{{ old('title', $kelas->title) }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2 border" required>
                        </div>

                        @if($kelas->tipe == 'interaktif')
                        <div>
                            <label class="block text-sm font-bold mb-2 text-blue-600 dark:text-blue-400">Link Zoom / Meet</label>
                            <input type="url" name="link_zoom" value="{{ old('link_zoom', $kelas->link_zoom) }}" class="w-full rounded border-blue-300 dark:bg-gray-700 dark:text-white p-2 border">
                        </div>
                        @endif

                        <!-- PERBAIKAN: Menambahkan jam_selesai -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Tanggal</label>
                                <input type="date" name="tanggal" value="{{ old('tanggal', $kelas->tanggal) }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2 border" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Jam Mulai</label>
                                <input type="time" name="jam_mulai" value="{{ old('jam_mulai', \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i')) }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2 border" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Jam Selesai</label>
                                <input type="time" name="jam_selesai" value="{{ old('jam_selesai', $kelas->jam_selesai ? \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i') : '') }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2 border">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Tempat</label>
                            <input type="text" name="tempat" value="{{ old('tempat', $kelas->tempat) }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2 border" required>
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Deskripsi</label>
                            <textarea name="deskripsi" rows="4" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2 border">{{ old('deskripsi', $kelas->deskripsi) }}</textarea>
                        </div>
                    </div>

                    <!-- Instruktur & Banner -->
                    <div class="space-y-4">
                        <h3 class="font-bold text-gray-700 dark:text-gray-200 border-b pb-2 mb-4">Instruktur & Visual</h3>

                        <!-- Narasumber -->
                        <div>
                            <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Pilih Narasumber</label>
                            <div class="space-y-2 max-h-60 overflow-y-auto border border-gray-300 rounded p-3">
                                @forelse($availableNarasumbers as $narasumber)
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" name="narasumber_ids[]" value="{{ $narasumber->id }}"
                                            {{ in_array($narasumber->id, old('narasumber_ids', $kelas->narasumbers->pluck('id')->toArray())) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-gray-700 dark:text-gray-300">{{ $narasumber->nama }}</span>
                                    </label>
                                @empty
                                    <p class="text-gray-500 text-sm">Belum ada instruktur tersedia untuk program ini.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Banner -->
                        <div>
                            <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Ganti Banner</label>
                            @if($kelas->banner_path)
                                <img src="{{ Storage::url($kelas->banner_path) }}" class="h-20 w-auto rounded mb-2 border">
                            @endif
                            <input type="file" name="banner" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <button type="submit" class="px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 shadow">Simpan Perubahan Info</button>
                </div>
            </form>
        </div>

        <!-- TAB 2: PRESENSI -->
        <div x-show="activeTab === 'presensi'" x-transition style="display: none;">
            <form action="{{ route('instructor.kelas.update', $kelas->id) }}" method="POST">
                @csrf @method('PUT')
                {{-- Bypass fields lain --}}
                <input type="hidden" name="title" value="{{ $kelas->title }}">
                <input type="hidden" name="tipe" value="{{ $kelas->tipe }}">
                <input type="hidden" name="tanggal" value="{{ $kelas->tanggal }}">
                <input type="hidden" name="jam_mulai" value="{{ $kelas->jam_mulai }}">
                <input type="hidden" name="jam_selesai" value="{{ $kelas->jam_selesai }}">
                <input type="hidden" name="tempat" value="{{ $kelas->tempat }}">
                <input type="hidden" name="deskripsi" value="{{ $kelas->deskripsi }}">
                @if($kelas->tipe == 'interaktif')
                    <input type="hidden" name="link_zoom" value="{{ $kelas->link_zoom }}">
                @endif

                <div class="bg-indigo-50 dark:bg-indigo-900/20 p-6 rounded-xl border border-indigo-100 dark:border-indigo-800">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-indigo-800 dark:text-indigo-300">Konfigurasi Presensi</h3>
                        <label class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" name="presensi[is_active]" value="1" class="sr-only toggle-presensi"
                                    {{ old('presensi.is_active', $kelas->presensiSetup?->is_active) ? 'checked' : '' }}>
                                <div class="toggle-line w-10 h-4 bg-gray-400 rounded-full shadow-inner"></div>
                                <div class="toggle-dot absolute w-6 h-6 bg-white rounded-full shadow -left-1 -top-1 transition"></div>
                            </div>
                            <div class="ml-3 text-gray-700 dark:text-gray-300 font-medium">Aktifkan Presensi</div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Presensi Awal -->
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow-sm">
                            <h4 class="font-bold text-gray-700 dark:text-white mb-3 border-b pb-1">Sesi Awal</h4>
                            <div class="mb-3">
                                <label class="text-xs font-bold text-gray-500 uppercase">Token Awal</label>
                                <input type="text" name="presensi[token_awal]"
                                    value="{{ old('presensi.token_awal', $kelas->presensiSetup?->token_awal ?? Str::random(6)) }}"
                                    class="w-full rounded border-gray-300 dark:bg-gray-600 dark:text-white font-mono tracking-widest text-center font-bold uppercase p-2 border">
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-xs">Buka</label>
                                    <input type="datetime-local" name="presensi[buka_awal]"
                                        value="{{ old('presensi.buka_awal', $kelas->presensiSetup ? \Carbon\Carbon::parse($kelas->presensiSetup->buka_awal)->format('Y-m-d\TH:i') : '') }}"
                                        class="w-full rounded border-gray-300 text-xs p-2 border">
                                </div>
                                <div>
                                    <label class="text-xs">Tutup</label>
                                    <input type="datetime-local" name="presensi[tutup_awal]"
                                        value="{{ old('presensi.tutup_awal', $kelas->presensiSetup ? \Carbon\Carbon::parse($kelas->presensiSetup->tutup_awal)->format('Y-m-d\TH:i') : '') }}"
                                        class="w-full rounded border-gray-300 text-xs p-2 border">
                                </div>
                            </div>
                        </div>

                        <!-- Presensi Akhir -->
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow-sm">
                            <h4 class="font-bold text-gray-700 dark:text-white mb-3 border-b pb-1">Sesi Akhir</h4>
                            <div class="mb-3">
                                <label class="text-xs font-bold text-gray-500 uppercase">Token Akhir</label>
                                <input type="text" name="presensi[token_akhir]"
                                    value="{{ old('presensi.token_akhir', $kelas->presensiSetup?->token_akhir ?? Str::random(6)) }}"
                                    class="w-full rounded border-gray-300 dark:bg-gray-600 dark:text-white font-mono tracking-widest text-center font-bold uppercase p-2 border">
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-xs">Buka</label>
                                    <input type="datetime-local" name="presensi[buka_akhir]"
                                        value="{{ old('presensi.buka_akhir', $kelas->presensiSetup ? \Carbon\Carbon::parse($kelas->presensiSetup->buka_akhir)->format('Y-m-d\TH:i') : '') }}"
                                        class="w-full rounded border-gray-300 text-xs p-2 border">
                                </div>
                                <div>
                                    <label class="text-xs">Tutup</label>
                                    <input type="datetime-local" name="presensi[tutup_akhir]"
                                        value="{{ old('presensi.tutup_akhir', $kelas->presensiSetup ? \Carbon\Carbon::parse($kelas->presensiSetup->tutup_akhir)->format('Y-m-d\TH:i') : '') }}"
                                        class="w-full rounded border-gray-300 text-xs p-2 border">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-right">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded hover:bg-indigo-700">Simpan Konfigurasi Presensi</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- TAB 3: MATERI & MODUL -->
        <div x-show="activeTab === 'materi'" x-transition style="display: none;" class="space-y-6">

            <!-- 1. Learning Path (Kurikulum) -->
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-800">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center">
                        <i class="fas fa-route mr-2 text-blue-500"></i> Learning Path (Kurikulum)
                    </h3>

                    @if(!$kelas->learningPath)
                        <!-- Form Buat Baru (Hanya Tombol) -->
                        <form action="{{ route('instructor.learningpath.store', $kelas->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="title" value="Kurikulum Utama">
                            <button type="submit" class="text-sm bg-blue-600 text-white px-3 py-1.5 rounded hover:bg-blue-700 font-bold">
                                <i class="fas fa-plus mr-1"></i> Buat Kurikulum
                            </button>
                        </form>
                    @else
                        <!-- Tombol Kelola -->
                        <a href="{{ route('instructor.learningpath.manage', $kelas->learningPath->id) }}" class="text-sm bg-yellow-500 text-white px-3 py-1.5 rounded hover:bg-yellow-600 font-bold">
                            <i class="fas fa-cog mr-1"></i> Kelola Kurikulum
                        </a>
                    @endif
                </div>

                @if($kelas->learningPath)
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded border border-blue-100 dark:border-blue-800">
                        <p class="font-bold text-blue-900 dark:text-blue-200">{{ $kelas->learningPath->title }}</p>
                        <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">Total: {{ $kelas->learningPath->sections->count() }} Bab Materi</p>
                    </div>
                @else
                    <p class="text-sm text-gray-400 italic p-2">Belum ada kurikulum terstruktur untuk kelas ini.</p>
                @endif
            </div>

            <!-- 2. Modul Bacaan -->
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-800">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center">
                        <i class="fas fa-book-open mr-2 text-green-500"></i> Modul Bacaan
                    </h3>
                    <a href="{{ route('instructor.modules.create', $kelas->id) }}" class="text-sm bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition">
                        <i class="fas fa-plus"></i> Tambah Modul
                    </a>
                </div>

                <ul class="space-y-2">
@forelse($kelas->modules as $mod)
    <li class="flex justify-between items-center p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded border border-transparent hover:border-gray-200 dark:hover:border-gray-600 transition">
        <div>
            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $mod->order }}. {{ $mod->title }}</span>
            <span class="ml-2 text-xs px-2 py-0.5 rounded-full bg-red-100 text-red-800">
                Wajib
            </span>
        </div>
        <a href="{{ route('instructor.modules.edit', $mod->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            <i class="fas fa-edit"></i> Edit
        </a>
    </li>
@empty


                        <li class="text-gray-400 italic list-none p-4 text-center bg-gray-50 dark:bg-gray-700/50 rounded border border-dashed border-gray-300 dark:border-gray-600">
                            Belum ada modul materi. Silakan tambah baru.
                        </li>
                    @endforelse
                </ul>
            </div>

            <!-- 3. Video -->
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-800">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center">
                        <i class="fab fa-youtube mr-2 text-red-500"></i> Video Pembelajaran
                    </h3>
                    <a href="{{ route('instructor.videos.create', $kelas->id) }}" class="text-sm bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition">
                        <i class="fas fa-plus"></i> Tambah Video
                    </a>
                </div>

                <ul class="space-y-2">
                    @forelse($kelas->videoEmbeds as $vid)
                        <li class="flex justify-between items-center p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded border border-transparent hover:border-gray-200 dark:hover:border-gray-600 transition">
                            <div class="flex items-center space-x-2 overflow-hidden">
                                <img src="https://img.youtube.com/vi/{{ $vid->youtube_id }}/default.jpg" class="h-8 w-auto rounded border border-gray-200">
                                <div>
                                    <span class="font-semibold text-gray-800 dark:text-gray-200 block truncate max-w-xs">{{ $vid->title }}</span>
                                    <span class="text-xs {{ $vid->is_published ? 'text-green-500' : 'text-red-500' }}">
                                        {{ $vid->is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('instructor.videos.edit', $vid->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium whitespace-nowrap ml-2">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </li>
                    @empty
                        <li class="text-gray-400 italic list-none p-4 text-center bg-gray-50 dark:bg-gray-700/50 rounded border border-dashed border-gray-300 dark:border-gray-600">
                            Belum ada video materi. Silakan tambah baru.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- TAB 4: TUGAS & UJIAN -->
        <div x-show="activeTab === 'tugas'" x-transition style="display: none;" class="space-y-6">

            <!-- Tugas -->
            @include('instructor.assignments.components.list', ['assignments' => $assignments, 'kelas' => $kelas])

            <!-- Kuis -->
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-800">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center">
                        <i class="fas fa-tasks mr-2 text-purple-500"></i> Kuis
                    </h3>
                    <a href="{{ route('instructor.quiz.create', $kelas->id) }}" class="text-sm bg-purple-600 text-white px-3 py-1.5 rounded hover:bg-purple-700 font-bold">
                        <i class="fas fa-plus mr-1"></i> Tambah Kuis
                    </a>
                </div>

                @include('instructor.quiz.components.list', ['quizzes' => $kelas->quizzes, 'kelas' => $kelas])
            </div>

            <!-- Ujian Essay -->
            @if(isset($kelas->essayExam) && $kelas->essayExam)
                @include('instructor.essay.components.card-summary', ['exam' => $kelas->essayExam])
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Ujian Essay Opsional</h3>
                    </div>

                    <div class="text-center py-6">
                        <div class="text-gray-400 dark:text-gray-500 mb-4">
                            <i class="fas fa-file-alt text-4xl"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">Belum ada ujian essay opsional untuk kelas ini</p>
                        <a href="{{ route('instructor.essay.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow">
                            <i class="fas fa-plus mr-2"></i>
                            Buat Ujian Essay Opsional
                        </a>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>

<style>
.toggle-presensi:checked ~ .toggle-line {
    background-color: #10B981;
}
.toggle-presensi:checked ~ .toggle-dot {
    transform: translateX(100%);
    background-color: #059669;
}
</style>

<script>
document.addEventListener('alpine:init', () => {
    // Toggle switch functionality
    document.querySelectorAll('.toggle-presensi').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const line = this.nextElementSibling;
            const dot = line.nextElementSibling;

            if (this.checked) {
                line.classList.add('bg-green-400');
                dot.classList.add('bg-green-600', 'translate-x-4');
            } else {
                line.classList.remove('bg-green-400');
                dot.classList.remove('bg-green-600', 'translate-x-4');
            }
        });

        // Trigger initial state
        toggle.dispatchEvent(new Event('change'));
    });
});
</script>
@endsection
