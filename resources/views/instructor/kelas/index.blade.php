@extends('instructor.layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-6xl">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Daftar Kelas</h1>
        <!-- Tombol Buat Kelas dihapus karena tidak ada route create -->
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Judul</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Program</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($kelas as $k)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $loop->iteration + ($kelas->currentPage() - 1) * $kelas->perPage() }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-medium">
                            {{ $k->title }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ $k->program->title ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                {{ ucfirst($k->tipe) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ \Carbon\Carbon::parse($k->tanggal)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('instructor.kelas.togglePublish', $k->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                @if($k->is_published)
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 hover:bg-green-200 dark:hover:bg-green-800 transition">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Published
                                    </button>
                                @else
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Unpublished
                                    </button>
                                @endif
                            </form>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center space-x-2">
                                <!-- Edit Button -->
                                <a href="{{ route('instructor.kelas.edit', $k->id) }}"
                                   class="inline-flex items-center px-3 py-1 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                                    <i class="fas fa-edit mr-1"></i>
                                    Edit
                                </a>

                                <!-- Print Button -->
                                <a href="{{ route('instructor.presensi.exportKelas', $k->id) }}"
                                   target="_blank"
                                   class="inline-flex items-center px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                                    <i class="fas fa-print mr-1"></i>
                                    Cetak
                                </a>

                                <!-- Tombol Hapus dihapus karena tidak ada route destroy -->
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-600">
            {{ $kelas->links() }}
        </div>
    </div>
</div>
@endsection
