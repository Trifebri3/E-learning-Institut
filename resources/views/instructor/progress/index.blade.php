@extends('instructor.layouts.app')

@section('title', 'Monitoring Progres Peserta')

@section('content')
<div class="container mx-auto p-6">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
            <i class="fas fa-chart-line text-purple-600 mr-2"></i> Progres & Penilaian
        </h1>
        <p class="text-gray-600 dark:text-gray-400">Pilih kelas untuk melihat detail progres dan mengelola nilai siswa.</p>
    </div>

    @foreach($programs as $program)
        <div class="mb-10">
            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4 border-b pb-2 dark:border-gray-700">
                {{ $program->title }}
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($program->kelas as $kelas)
                    <a href="{{ route('instructor.progress.show', $kelas->id) }}"
                       class="block bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm hover:shadow-md transition border-l-4 border-purple-500 hover:border-purple-600 dark:border-purple-600">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white text-lg">{{ $kelas->title }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    <i class="fas fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($kelas->tanggal)->format('d M Y') }}
                                </p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-purple-50 dark:bg-purple-900/50 flex items-center justify-center text-purple-600 dark:text-purple-300">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-2 text-xs text-gray-500">
                            <span class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ $kelas->tipe == 'interaktif' ? 'Interaktif' : 'Materi' }}</span>
                            @if($kelas->gradeSetting)
                                <span class="text-green-600"><i class="fas fa-check-circle"></i> Bobot OK</span>
                            @else
                                <span class="text-red-500"><i class="fas fa-exclamation-circle"></i> Bobot Belum Diatur</span>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="col-span-3 text-center py-4 text-gray-500">Belum ada kelas di program ini.</div>
                @endforelse
            </div>
        </div>
    @endforeach
</div>
@endsection
