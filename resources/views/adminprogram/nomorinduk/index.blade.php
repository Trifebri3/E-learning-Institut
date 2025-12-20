@extends('adminprogram.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kelola Nomor Induk</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Cari peserta berdasarkan minat program untuk menerbitkan Nomor Induk baru.</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-8 sticky top-4 z-10">
        <form action="{{ route('adminprogram.ni.index') }}" method="GET">
            <div class="flex flex-col md:flex-row gap-4 items-end">

                <div class="flex-1 w-full">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">
                        Cari Minat Program <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="search_minat" value="{{ request('search_minat') }}" required
                               class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 text-lg"
                               placeholder="Ketik minat... (Contoh: Web, Akuntansi)">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-search text-lg"></i>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-48">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Urutan Daftar</label>
                    <select name="sort" class="w-full py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                    </select>
                </div>

                <button type="submit" class="w-full md:w-auto px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg transition shadow-lg shadow-indigo-500/30">
                    Tampilkan
                </button>
            </div>

            <div class="mt-3 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                <i class="fas fa-filter text-indigo-500"></i>
                <span>Filter Otomatis: Hanya menampilkan peserta dengan <strong>Biodata Lengkap</strong> (HP, Alamat, Tgl Lahir) & <strong>Belum Punya NI</strong>.</span>
            </div>
        </form>
    </div>

    @if($isSearched)

        <div class="mb-12">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm"><i class="fas fa-user-plus"></i></span>
                Kandidat Siap Generate
            </h3>

            @if($candidates->isEmpty())
                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl border border-dashed border-gray-300 dark:border-gray-700 p-10 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                        <i class="fas fa-user-check text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 font-medium text-lg">Tidak ada kandidat baru ditemukan.</p>
                    <p class="text-sm text-gray-400 mt-1 max-w-md mx-auto">
                        Tidak ada peserta dengan minat "<strong>{{ request('search_minat') }}</strong>" yang biodatanya lengkap dan belum memiliki nomor induk.
                    </p>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider font-semibold">
                                    <th class="px-6 py-4">Peserta</th>
                                    <th class="px-6 py-4">Minat Program</th>
                                    <th class="px-6 py-4 text-center">Verifikasi Data</th>
                                    <th class="px-6 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($candidates as $c)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold mr-3 text-sm">
                                                {{ substr($c->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 dark:text-white">{{ $c->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $c->email }}</div>
                                                <div class="text-[10px] text-gray-400 mt-0.5">
                                                    <i class="far fa-clock"></i> Daftar: {{ $c->created_at->format('d M Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 px-3 py-1 rounded-full text-xs font-semibold border border-blue-100 dark:border-blue-800">
                                            {{ $c->profile->minat_program }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1 items-center">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-400">
                                                <i class="fas fa-check-circle"></i> Lengkap
                                            </span>

                                            <div class="text-[10px] text-gray-400 text-center mt-1">
                                                <span title="No HP"><i class="fas fa-phone"></i></span> •
                                                <span title="Alamat"><i class="fas fa-map-marker-alt"></i></span> •
                                                <span title="Tgl Lahir"><i class="fas fa-birthday-cake"></i></span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('adminprogram.ni.store') }}" method="POST" class="inline-block">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $c->id }}">

                                            <button type="submit" class="group relative inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg shadow-md transition-all transform hover:-translate-y-0.5 overflow-hidden">
                                                <span class="relative z-10 flex items-center gap-2">
                                                    <i class="fas fa-magic"></i> Generate NI
                                                </span>
                                                <div class="absolute inset-0 h-full w-full scale-0 rounded-lg transition-all duration-300 group-hover:scale-100 group-hover:bg-indigo-500/30"></div>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">
                        {{ $candidates->appends(request()->all())->links() }}
                    </div>
                </div>
            @endif
        </div>

        @if($existingNIs->isNotEmpty())
            <div class="opacity-90 hover:opacity-100 transition-opacity duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 flex items-center justify-center text-xs"><i class="fas fa-history"></i></span>
                        <h3 class="text-md font-bold text-gray-600 dark:text-gray-300">
                            Riwayat / Sudah Memiliki Nomor Induk
                        </h3>
                    </div>
                    <span class="text-xs font-mono bg-gray-100 dark:bg-gray-800 text-gray-500 px-2 py-1 rounded">
                        Keyword: "{{ request('search_minat') }}"
                    </span>
                </div>

                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-100 dark:bg-gray-700/50 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Nomor Induk</th>
                                <th class="px-4 py-3 font-semibold">Nama Peserta</th>
                                <th class="px-4 py-3 font-semibold text-center">Status Akun</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($existingNIs as $ni)
                            <tr class="hover:bg-white dark:hover:bg-gray-700/20 transition">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono font-bold text-gray-700 dark:text-gray-300 select-all">{{ $ni->nomor_induk }}</span>
                                        @if($ni->program_id)
                                            <span class="text-[10px] bg-green-100 text-green-700 px-1.5 py-0.5 rounded border border-green-200">Redeemed</span>
                                        @else
                                            <span class="text-[10px] bg-yellow-100 text-yellow-700 px-1.5 py-0.5 rounded border border-yellow-200">Available</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3">{{ $ni->user->name }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($ni->is_active)
                                        <span class="text-green-600 text-xs"><i class="fas fa-circle text-[8px] mr-1"></i> Aktif</span>
                                    @else
                                        <span class="text-red-500 text-xs"><i class="fas fa-circle text-[8px] mr-1"></i> Non-Aktif</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    @else
        <div class="flex flex-col items-center justify-center py-24 text-center opacity-60">
            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-6 animate-pulse">
                <i class="fas fa-search text-5xl text-gray-300 dark:text-gray-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-300 mb-2">Mulai Pencarian Peserta</h2>
            <p class="text-gray-500 dark:text-gray-400 max-w-md">
                Masukkan kata kunci <strong>Minat Program</strong> pada kolom di atas untuk menampilkan daftar kandidat yang memenuhi syarat.
            </p>
        </div>
    @endif

</div>
@endsection
