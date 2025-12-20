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
                Kelola Piagam Program
            </h1>
        </div>
        <p class="text-gray-600 ml-13">
            Pilih program untuk mengelola piagam dan sertifikat peserta.
        </p>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-list-alt text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Program</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $programs->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Piagam Disetujui</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $approvedCount ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-yellow-100 text-yellow-600 mr-4">
                    <i class="fas fa-clock text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Menunggu Persetujuan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $pendingCount ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Programs Grid -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-folder-open text-blue-500 mr-3"></i>
            Daftar Program
        </h2>

        @if($programs->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-16 px-6 bg-white rounded-2xl shadow-sm border border-gray-200">
                <div class="w-20 h-20 mx-auto mb-4 flex items-center justify-center rounded-full bg-gray-100">
                    <i class="fas fa-folder-open text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">
                    Tidak Ada Program
                </h3>
                <p class="text-gray-500 max-w-md mx-auto">
                    Belum ada program yang tersedia untuk dikelola.
                </p>
            </div>
        @else
            <!-- Programs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($programs as $program)
                    <a href="{{ route('adminprogram.piagam.index', $program->id) }}"
                       class="group block transition-all duration-300 transform hover:-translate-y-2">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-xl transition-all duration-300 overflow-hidden h-full flex flex-col">
                            <!-- Program Header -->
                            <div class="p-6 flex-1">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 text-white group-hover:from-blue-600 group-hover:to-blue-700 transition-all duration-300">
                                        <i class="fas fa-certificate text-lg"></i>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $program->piagams_count ?? 0 }} Piagam
                                    </span>
                                </div>

                                <h3 class="font-bold text-lg text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors duration-200">
                                    {{ $program->title }}
                                </h3>

                                <div class="space-y-2 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <i class="fas fa-hashtag text-gray-400 mr-2 w-4"></i>
                                        <span class="font-mono">{{ $program->code }}</span>
                                    </div>

                                    @if($program->start_date && $program->end_date)
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-alt text-gray-400 mr-2 w-4"></i>
                                        <span>{{ \Carbon\Carbon::parse($program->start_date)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($program->end_date)->translatedFormat('d M Y') }}</span>
                                    </div>
                                    @endif

                                    @if($program->location)
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt text-gray-400 mr-2 w-4"></i>
                                        <span class="line-clamp-1">{{ $program->location }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Footer with CTA -->
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 group-hover:bg-blue-50 transition-colors duration-200">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700 transition-colors duration-200">
                                        Kelola Piagam
                                    </span>
                                    <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-all duration-200"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Additional Info -->
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
        <div class="flex items-start">
            <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-100 text-blue-600 mr-3 mt-1">
                <i class="fas fa-info-circle"></i>
            </div>
            <div>
                <h4 class="font-semibold text-blue-900 mb-2">Informasi Pengelolaan Piagam</h4>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Klik pada program untuk melihat daftar piagam peserta</li>
                    <li>• Setujui atau tolak pengajuan piagam dari peserta</li>
                    <li>• Update nilai atau predikat peserta sesuai kebutuhan</li>
                    <li>• Download piagam yang sudah disetujui</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
