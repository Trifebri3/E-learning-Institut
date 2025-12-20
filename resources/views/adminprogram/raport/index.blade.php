@extends('adminprogram.layouts.app')

@section('title', 'Daftar Kelas - Raport')

@section('content')
<div class="container mx-auto p-6 max-w-7xl">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Daftar Kelas</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola dan lihat raport peserta per kelas</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <input type="text" placeholder="Cari kelas..."
                       class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border border-gray-100 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <i class="fas fa-chalkboard-teacher text-blue-600 dark:text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Kelas</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $kelasList->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border border-gray-100 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                    <i class="fas fa-user-graduate text-green-600 dark:text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Peserta</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $totalPeserta ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border border-gray-100 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <i class="fas fa-file-alt text-purple-600 dark:text-purple-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Quiz Tersedia</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $totalQuiz ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border border-gray-100 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg">
                    <i class="fas fa-tasks text-orange-600 dark:text-orange-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Raport Tersedia</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $kelasList->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Kelas -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-sort mr-1"></i>
                            Kelas
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-sort mr-1"></i>
                            Program
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-users mr-1"></i>
                            Jumlah Peserta
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-file-alt mr-1"></i>
                            Total Quiz
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($kelasList as $kelas)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900 dark:text-white">{{ $kelas->title }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $kelas->tanggal ? \Carbon\Carbon::parse($kelas->tanggal)->format('d M Y') : '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900 dark:text-white">{{ $kelas->program->title ?? '-' }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $kelas->program->category ?? '' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                <i class="fas fa-user mr-1"></i>
                                {{ $kelas->participants->count() }} peserta
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                <i class="fas fa-question-circle mr-1"></i>
                                {{ $kelas->quizzes->count() }} quiz
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('adminprogram.raport.show', $kelas->id) }}"
                                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow hover:shadow-lg">
                                   <i class="fas fa-file-contract mr-2"></i>
                                   Lihat Raport
                                </a>
                                <a href="{{ route('adminprogram.raport.export', $kelas->id) }}"
                                   class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition shadow hover:shadow-lg">
                                   <i class="fas fa-download mr-2"></i>
                                   Export PDF
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-400 dark:text-gray-500">
                                <i class="fas fa-chalkboard-teacher text-4xl mb-3"></i>
                                <p class="text-lg font-medium">Belum ada kelas</p>
                                <p class="text-sm mt-1">Tidak ada data kelas yang tersedia untuk raport</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($kelasList->hasPages())
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-600">
            {{ $kelasList->links() }}
        </div>
        @endif
    </div>

    <!-- Info Box -->
    <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
            <div class="text-sm text-blue-800 dark:text-blue-300">
                <p class="font-semibold mb-2">Fitur Raport Kelas:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Lihat nilai dan progress semua peserta dalam satu kelas</li>
                    <li>Export raport dalam format PDF untuk distribusi</li>
                    <li>Monitor completion rate dan rata-rata nilai kelas</li>
                    <li>Identifikasi peserta yang membutuhkan bantuan tambahan</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    .hover\:shadow-lg:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
</style>
@endsection
