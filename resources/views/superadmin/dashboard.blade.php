@extends('superadmin.layouts.app')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="container mx-auto p-6">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard Overview</h1>
        <p class="text-gray-600 dark:text-gray-400">Selamat datang kembali, Super Admin. Berikut ringkasan sistem hari ini.</p>
    </div>

    <!-- 1. STATISTIC CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <!-- Card: Peserta -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border-l-4 border-blue-500 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 uppercase font-semibold">Total Peserta</p>
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $stats['total_participants'] }}</h2>
            </div>
            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-full text-blue-600 dark:text-blue-400">
                <i class="fas fa-users text-xl"></i>
            </div>
        </div>

        <!-- Card: Program -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border-l-4 border-green-500 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 uppercase font-semibold">Program Aktif</p>
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $stats['total_programs'] }}</h2>
            </div>
            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full text-green-600 dark:text-green-400">
                <i class="fas fa-chalkboard-teacher text-xl"></i>
            </div>
        </div>

        <!-- Card: Tiket Bantuan (Pending) -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border-l-4 border-red-500 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 uppercase font-semibold">Tiket Terbuka</p>
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $stats['pending_tickets'] }}</h2>
            </div>
            <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-full text-red-600 dark:text-red-400">
                <i class="fas fa-life-ring text-xl"></i>
            </div>
        </div>

        <!-- Card: Total Tugas Dikumpulkan -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border-l-4 border-purple-500 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 uppercase font-semibold">Tugas Masuk</p>
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $stats['total_submissions'] }}</h2>
            </div>
            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-full text-purple-600 dark:text-purple-400">
                <i class="fas fa-file-alt text-xl"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- 2. GRAFIK PENDAFTARAN (KOLOM KIRI - 2/3) -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">
                <i class="fas fa-chart-line text-indigo-500 mr-2"></i> Tren Pendaftaran Peserta (7 Hari)
            </h3>

            <!-- Simple Bar Chart dengan CSS & Flexbox (Tanpa Library JS Berat) -->
            <div class="flex items-end justify-between h-64 mt-6 space-x-2">
                @php $maxVal = max($chartData['data']) > 0 ? max($chartData['data']) : 1; @endphp
                @foreach($chartData['data'] as $index => $value)
                    <div class="w-full flex flex-col items-center group relative">
                        <!-- Tooltip -->
                        <div class="absolute bottom-full mb-2 hidden group-hover:block bg-black text-white text-xs rounded py-1 px-2 z-10">
                            {{ $value }} Peserta
                        </div>
                        <!-- Bar -->
                        <div class="w-full bg-indigo-200 dark:bg-indigo-900/50 rounded-t-md relative overflow-hidden hover:bg-indigo-300 transition-all duration-300"
                             style="height: {{ ($value / $maxVal) * 100 }}%;">
                             <div class="absolute bottom-0 w-full bg-indigo-500 h-full opacity-80"></div>
                        </div>
                        <!-- Label -->
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ $chartData['labels'][$index] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 3. USER TERBARU (KOLOM KANAN - 1/3) -->
        <div class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-100 dark:border-gray-700">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Peserta Baru</h3>
                <a href="{{ route('superadmin.users.index', ['role' => 'participant']) }}" class="text-xs text-blue-600 hover:underline">Lihat Semua</a>
            </div>

            <div class="space-y-4">
                @foreach($recentUsers as $user)
                    <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=fff" class="w-10 h-10 rounded-full">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                        </div>
                        <div class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</div>
                    </div>
                @endforeach
                @if($recentUsers->isEmpty())
                    <p class="text-sm text-gray-500 text-center">Belum ada peserta baru.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- 4. TIKET SUPPORT TERBARU (FULL WIDTH) -->
    <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                <i class="fas fa-exclamation-circle text-red-500 mr-2"></i> Tiket Bantuan Perlu Respon
            </h3>
            <a href="#" class="text-sm text-blue-600 hover:underline">Kelola Tiket</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 uppercase font-bold text-xs">
                    <tr>
                        <th class="px-6 py-3">Pengirim</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Subjek</th>
                        <th class="px-6 py-3">Prioritas</th>
                        <th class="px-6 py-3">Waktu</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($recentTickets as $ticket)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            {{ $ticket->user->name }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-bold bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-200">
                                {{ ucfirst($ticket->category) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-300 truncate max-w-xs">
                            {{ $ticket->subject }}
                        </td>
                        <td class="px-6 py-4">
                            @if($ticket->priority == 'high')
                                <span class="text-red-600 font-bold text-xs flex items-center"><i class="fas fa-arrow-up mr-1"></i> Tinggi</span>
                            @elseif($ticket->priority == 'medium')
                                <span class="text-yellow-600 font-bold text-xs flex items-center"><i class="fas fa-minus mr-1"></i> Sedang</span>
                            @else
                                <span class="text-blue-600 font-bold text-xs flex items-center"><i class="fas fa-arrow-down mr-1"></i> Rendah</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $ticket->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900 font-medium">Jawab</a>
                        </td>
                    </tr>
                    @endforeach

                    @if($recentTickets->isEmpty())
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-check-circle text-green-500 text-2xl mb-2 block"></i>
                            Tidak ada tiket terbuka saat ini. Kerja bagus!
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div id="support-latest"></div>

<script>
function loadLatestTickets() {
    fetch("{{ route('superadmin.support.latest') }}")
        .then(res => res.text())
        .then(html => {
            document.getElementById('support-latest').innerHTML = html;
        });
}
loadLatestTickets();
setInterval(loadLatestTickets, 30000);
</script>

    </div>

</div>
@endsection
