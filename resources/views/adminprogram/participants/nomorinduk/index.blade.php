@extends('adminprogram.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Nomor Induk</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Generate dan kelola akses peserta melalui kode unik.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-100 dark:border-gray-700 sticky top-6">

                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <i class="fas fa-barcode text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Generate Baru</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Buat kode akses peserta</p>
                    </div>
                </div>

                <form action="{{ route('adminprogram.ni.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">
                            Pilih Calon Peserta <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="user_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 py-2.5" required>
                                <option value="">-- Pilih User (Data Lengkap) --</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 flex items-start gap-1">
                            <i class="fas fa-info-circle mt-0.5"></i>
                            <span>Hanya menampilkan peserta dengan biodata lengkap.</span>
                        </p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">
                            Kode Kustom (Opsional)
                        </label>
                        <input type="text" name="custom_code"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 py-2.5"
                               placeholder="Contoh: NI-2025-ABC">
                        <p class="text-xs text-gray-400 mt-1">Kosongkan untuk auto-generate sistem.</p>
                    </div>

                    <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold transition-all transform hover:-translate-y-0.5 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-magic"></i> Generate Kode
                    </button>
                </form>

                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-lg flex gap-3">
                    <i class="fas fa-lightbulb text-blue-600 dark:text-blue-400 mt-0.5"></i>
                    <div class="text-sm text-blue-800 dark:text-blue-300">
                        <p class="font-semibold mb-1">Panduan:</p>
                        <p class="text-xs opacity-90">
                            Berikan kode ini kepada peserta. Peserta wajib memasukkan kode ini di menu <strong>Redeem Program</strong> untuk masuk ke kelas.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">

                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-list text-indigo-500"></i> Daftar Nomor Induk
                    </h3>

                    <form method="GET" action="{{ route('adminprogram.ni.index') }}" class="w-full sm:w-auto">
                        <div class="relative">
                            <input type="text" name="search_ni" value="{{ request('search_ni') }}"
                                   class="w-full sm:w-64 pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Cari Kode / Nama...">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider font-semibold">
                                <th class="px-6 py-4">Nomor Induk</th>
                                <th class="px-6 py-4">Pemilik</th>
                                <th class="px-6 py-4">Status Redeem</th>
                                <th class="px-6 py-4 text-center">Status Aktif</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($nomorInduks as $ni)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-2 py-1 rounded select-all">
                                            {{ $ni->nomor_induk }}
                                        </span>
                                        <button onclick="navigator.clipboard.writeText('{{ $ni->nomor_induk }}')" class="text-gray-400 hover:text-indigo-500 transition" title="Copy">
                                            <i class="far fa-copy"></i>
                                        </button>
                                    </div>
                                    <div class="text-[10px] text-gray-400 mt-1">
                                        {{ $ni->created_at->format('d M Y') }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-500 dark:text-gray-300 font-bold text-xs mr-3">
                                            {{ substr($ni->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $ni->user->name }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $ni->user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    @if($ni->program_id)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                            <i class="fas fa-check-circle mr-1"></i> Terpakai
                                        </span>
                                        <div class="text-[10px] text-gray-500 mt-1 truncate max-w-[150px]" title="{{ $ni->program->title ?? '-' }}">
                                            di: {{ $ni->program->title ?? '-' }}
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                            <i class="fas fa-hourglass-start mr-1"></i> Belum Dipakai
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('adminprogram.ni.toggle', $ni->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 {{ $ni->is_active ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-600' }}"
                                            role="switch">
                                            <span aria-hidden="true"
                                                class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $ni->is_active ? 'translate-x-5' : 'translate-x-0' }}">
                                            </span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-search text-3xl mb-3 opacity-50"></i>
                                    <p>Data tidak ditemukan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    {{ $nomorInduks->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
