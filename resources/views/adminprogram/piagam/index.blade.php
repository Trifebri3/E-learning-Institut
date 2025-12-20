@extends('adminprogram.layouts.app')

@section('content')

<div class="container mx-auto p-4 md:p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center mb-3">
            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-blue-100 text-blue-600 mr-3">
                <i class="fas fa-certificate text-lg"></i>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                Piagam Peserta - {{ $program->title }}
            </h1>
        </div>
        <p class="text-gray-600 ml-13">
            Kelola persetujuan dan nilai piagam peserta program.
        </p>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-1"></i>
                    Cari Peserta
                </label>
                <input type="text"
                       name="q"
                       placeholder="Cari nama / email..."
                       value="{{ request('q') }}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
            </div>

            <div class="w-full md:w-48">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-sort mr-1"></i>
                    Urutkan
                </label>
                <select name="sort" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                </select>
            </div>

            <button type="submit"
                    class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl flex items-center gap-2">
                <i class="fas fa-filter"></i>
                Terapkan Filter
            </button>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i>
                            Nama Peserta
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-envelope mr-2"></i>
                            Email
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-star mr-2"></i>
                            Predikat
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-info-circle mr-2"></i>
                            Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-cog mr-2"></i>
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($piagam as $p)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <!-- Nama -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $p->user->name }}
                            </div>
                        </td>

                        <!-- Email -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">
                                {{ $p->user->email }}
                            </div>
                        </td>

                        <!-- Predikat -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('adminprogram.piagam.updateGrade', $p->id) }}" method="POST" class="flex items-center space-x-2">
                                @csrf
                                @method('PUT')
                                <input type="text"
                                       name="grade"
                                       value="{{ $p->grade }}"
                                       class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                       placeholder="A / B+ / 90">
                                <button type="submit"
                                        class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-lg transition-all duration-200 flex items-center gap-1">
                                    <i class="fas fa-save text-xs"></i>
                                    Update
                                </button>
                            </form>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($p->is_approved)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Disetujui
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Menunggu
                                </span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                @if(!$p->is_approved)
                                <form action="{{ route('adminprogram.piagam.approve', $p->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition-all duration-200 transform hover:-translate-y-0.5">
                                        <i class="fas fa-check"></i>
                                        Approve
                                    </button>
                                </form>
                                @endif

                                <a href="{{ route('adminprogram.piagam.preview', $p->id) }}"
                                   target="_blank"
                                   class="inline-flex items-center gap-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-all duration-200 transform hover:-translate-y-0.5">
                                    <i class="fas fa-eye"></i>
                                    Preview
                                </a>

                                <a href="{{ route('adminprogram.piagam.download', $p->id) }}"
                                   class="inline-flex items-center gap-1 px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg transition-all duration-200 transform hover:-translate-y-0.5">
                                    <i class="fas fa-download"></i>
                                    Download
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($piagam->hasPages())
    <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
        {{ $piagam->links() }}
    </div>
    @endif

    <!-- Empty State -->
    @if($piagam->isEmpty())
    <div class="text-center py-16 px-6 bg-white rounded-2xl shadow-sm border border-gray-200">
        <div class="w-20 h-20 mx-auto mb-4 flex items-center justify-center rounded-full bg-gray-100">
            <i class="fas fa-certificate text-3xl text-gray-400"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">
            Tidak Ada Data Piagam
        </h3>
        <p class="text-gray-500 max-w-md mx-auto">
            Belum ada peserta yang mengajukan piagam untuk program ini.
        </p>
    </div>
    @endif
</div>

@endsection
