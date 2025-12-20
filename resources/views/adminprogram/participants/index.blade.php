@extends('adminprogram.layouts.app')

@section('content')
<div class="container mx-auto p-6" x-data="{ activeTab: 'list' }">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Kelola Peserta Program</h1>

        <div class="flex space-x-2">
            <!-- Tombol Tab -->
            <button @click="activeTab = 'list'"
                :class="activeTab === 'list' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 dark:bg-gray-800 dark:text-gray-300'"
                class="px-4 py-2 rounded-lg font-semibold shadow transition">
                <i class="fas fa-users mr-2"></i> Daftar Peserta
            </button>
            <button @click="activeTab = 'ni'"
                :class="activeTab === 'ni' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 dark:bg-gray-800 dark:text-gray-300'"
                class="px-4 py-2 rounded-lg font-semibold shadow transition">
                <i class="fas fa-id-card mr-2"></i> Nomor Induk
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- TAB 1: DAFTAR PESERTA (ENROLLED) -->
    <div x-show="activeTab === 'list'" x-transition>
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700">

            <!-- Toolbar -->
            <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex flex-wrap gap-4 justify-between items-center bg-gray-50 dark:bg-gray-700/50">
                <h3 class="font-bold text-gray-700 dark:text-gray-200">Peserta Aktif</h3>

                <div class="flex gap-2">
                    <!-- Form Search -->
                    <form action="{{ route('adminprogram.participants.index') }}" method="GET" class="flex">
                        <input type="hidden" name="active_tab" value="list">
                        <input type="text" name="search_participant" placeholder="Cari nama/email..." value="{{ request('search_participant') }}" class="px-3 py-2 border rounded-l-lg text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-indigo-500">
                        <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded-r-lg hover:bg-indigo-700"><i class="fas fa-search"></i></button>
                    </form>

                    <!-- Tombol Print -->
                    <a href="{{ route('adminprogram.participants.print') }}" target="_blank" class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm flex items-center">
                        <i class="fas fa-file-pdf mr-2"></i> PDF
                    </a>
                </div>
            </div>

            <!-- Tabel Peserta -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 text-xs uppercase font-bold">
                        <tr>
                            <th class="px-6 py-3">Nama Peserta</th>
                            <th class="px-6 py-3">Program Diikuti</th>
                            <th class="px-6 py-3">Nomor Induk (Aktif)</th>
                            <th class="px-6 py-3">Kontak</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($participants as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img class="h-8 w-8 rounded-full mr-3" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}" alt="">
                                    <div>
                                        <div class="font-bold text-gray-900 dark:text-white text-sm">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($user->programs as $prog)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-bold">{{ $prog->title }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono text-sm">
                                @foreach($user->nomorInduks as $ni)
                                    @if($ni->program_id)
                                        <div class="text-gray-700 dark:text-gray-300">{{ $ni->nomor_induk }}</div>
                                    @endif
                                @endforeach
                            </td>
                            <td class="px-6 py-4 text-sm">
                                {{ $user->profile->nomor_hp ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div x-data="{ open: false }" class="relative inline-block text-left">
                                    <button @click="open = !open" class="text-gray-500 hover:text-indigo-600"><i class="fas fa-ellipsis-v"></i></button>
                                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-50 border dark:border-gray-700 py-1">
                                        @foreach($user->programs as $prog)
                                            <form action="{{ route('adminprogram.participants.deactivate', $user->id) }}" method="POST" onsubmit="return confirm('Keluarkan peserta dari {{ $prog->title }}?');">
                                                @csrf
                                                <input type="hidden" name="program_id" value="{{ $prog->id }}">
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    Non-aktifkan ({{ Str::limit($prog->title, 15) }})
                                                </button>
                                            </form>
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $participants->appends(['active_tab' => 'list'])->links() }}
            </div>
        </div>
    </div>

    <!-- TAB 2: MANAJEMEN NOMOR INDUK -->
  <div x-show="activeTab === 'ni'" x-transition style="display: none;">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Form Generate NI (Kiri) -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-indigo-600 mb-4">Buat Nomor Induk Baru</h3>
                <form action="{{ route('adminprogram.participants.ni.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Pilih Calon Peserta</label>
                        <select name="user_id" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                            <option value="">-- Cari User --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Hanya user dengan role 'Participant'.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Kode Kustom (Opsional)</label>
                        <input type="text" name="custom_code" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" placeholder="Kosongkan untuk auto-generate">
                    </div>

                    <button type="submit" class="w-full py-2 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700 transition">
                        <i class="fas fa-magic mr-2"></i> Generate Kode
                    </button>
                </form>

                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded text-sm text-blue-800 dark:text-blue-300">
                    <i class="fas fa-info-circle mr-1"></i>
                    Berikan kode ini kepada peserta. Mereka akan menggunakannya saat <strong>Redeem Program</strong>.
                </div>
            </div>
        </div>

        <!-- Tabel Daftar NI (Kanan) -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h3 class="font-bold text-gray-700 dark:text-gray-200">Database Nomor Induk</h3>

                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <!-- Form Pencarian -->
                    <form action="{{ route('adminprogram.participants.index') }}" method="GET" class="flex gap-2">
                        <input type="hidden" name="active_tab" value="ni">
                        <input type="text" name="search_ni" placeholder="Cari NI / Nama..." value="{{ request('search_ni') }}"
                               class="px-3 py-1 border rounded text-sm dark:bg-gray-700 dark:text-white w-full sm:w-48">
                        <button type="submit" class="bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700 transition">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    <!-- Filter Status -->
                    <form action="{{ route('adminprogram.participants.index') }}" method="GET" class="flex gap-2">
                        <input type="hidden" name="active_tab" value="ni">
                        <input type="hidden" name="search_ni" value="{{ request('search_ni') }}">
                        <select name="status_filter" onchange="this.form.submit()"
                                class="px-3 py-1 border rounded text-sm dark:bg-gray-700 dark:text-white w-full sm:w-auto">
                            <option value="">Semua Status</option>
                            <option value="used" {{ request('status_filter') == 'used' ? 'selected' : '' }}>Sudah Dipakai</option>
                            <option value="unused" {{ request('status_filter') == 'unused' ? 'selected' : '' }}>Belum Dipakai</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Info Pagination & Sorting -->
            <div class="px-4 py-2 bg-gray-50 dark:bg-gray-700/30 border-b border-gray-100 dark:border-gray-700 text-xs text-gray-600 dark:text-gray-400 flex flex-wrap justify-between items-center">
                <div>
                    Menampilkan {{ $nomorInduks->firstItem() ?? 0 }} - {{ $nomorInduks->lastItem() ?? 0 }} dari {{ $nomorInduks->total() }} data
                </div>
                <div class="flex items-center gap-2 mt-1 sm:mt-0">
                    <span>Urutkan:</span>
                    <form action="{{ route('adminprogram.participants.index') }}" method="GET" class="flex gap-1">
                        <input type="hidden" name="active_tab" value="ni">
                        <input type="hidden" name="search_ni" value="{{ request('search_ni') }}">
                        <input type="hidden" name="status_filter" value="{{ request('status_filter') }}">
                        <select name="sort_by" onchange="this.form.submit()" class="text-xs border rounded dark:bg-gray-700 dark:text-white">
                            <option value="nomor_induk" {{ request('sort_by') == 'nomor_induk' ? 'selected' : '' }}>Nomor Induk</option>
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nama Peserta</option>
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                        </select>
                        <select name="sort_order" onchange="this.form.submit()" class="text-xs border rounded dark:bg-gray-700 dark:text-white">
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>A-Z</option>
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Z-A</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-3">Nomor Induk</th>
                            <th class="px-4 py-3">Pemilik</th>
                            <th class="px-4 py-3">Status Penggunaan</th>
                            <th class="px-4 py-3">Tanggal Dibuat</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($nomorInduks as $ni)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-4 py-3 font-mono font-bold text-gray-800 dark:text-gray-200">
                                {{ $ni->nomor_induk }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $ni->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $ni->user->email }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @if($ni->program)
                                    <span class="text-green-600 font-bold text-xs flex items-center">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        <span class="hidden sm:inline">Dipakai:</span> {{ Str::limit($ni->program->title, 15) }}
                                    </span>
                                @else
                                    <span class="text-yellow-600 font-bold text-xs flex items-center">
                                        <i class="fas fa-hourglass-start mr-1"></i> Belum Dipakai
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500">
                                {{ $ni->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <form action="{{ route('adminprogram.participants.ni.toggle', $ni->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    @if($ni->is_active)
                                        <button type="submit" class="text-red-600 hover:underline text-xs px-2 py-1 rounded border border-red-200 hover:bg-red-50 transition" title="Non-aktifkan">
                                            Non-aktifkan
                                        </button>
                                    @else
                                        <button type="submit" class="text-green-600 hover:underline text-xs px-2 py-1 rounded border border-green-200 hover:bg-green-50 transition" title="Aktifkan">
                                            Aktifkan
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                <i class="fas fa-inbox text-2xl mb-2 block"></i>
                                Tidak ada data nomor induk
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-700">
                {{ $nomorInduks->appends([
                    'active_tab' => 'ni',
                    'search_ni' => request('search_ni'),
                    'status_filter' => request('status_filter'),
                    'sort_by' => request('sort_by'),
                    'sort_order' => request('sort_order')
                ])->links() }}
            </div>
        </div>
    </div>
</div>

</div>
@endsection
