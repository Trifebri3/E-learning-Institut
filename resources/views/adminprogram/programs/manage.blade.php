{{-- resources/views/adminprogram/program/manage.blade.php --}}
@extends('adminprogram.layouts.app')

@section('content')
<div class="container mx-auto p-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Kelola Program: {{ $program->name }}</h1>
        <p class="text-gray-500 dark:text-gray-400">Pilih menu untuk mengelola ujian, tugas, dan peserta</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Ujian Essay -->
        <a href="{{ route('adminprogram.essay.index') }}" class="block bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow p-6 transition">
            <div class="flex items-center">
                <i class="fas fa-file-alt text-3xl mr-4"></i>
                <div>
                    <h3 class="text-lg font-semibold">Ujian Essay</h3>
                    <p class="text-sm">Kelola ujian, soal, dan submission peserta</p>
                </div>
            </div>
        </a>

        <!-- Tugas / Assignment -->
        <a href="{{ route('adminprogram.assignments.index') }}" class="block bg-green-600 hover:bg-green-700 text-white rounded-lg shadow p-6 transition">
            <div class="flex items-center">
                <i class="fas fa-tasks text-3xl mr-4"></i>
                <div>
                    <h3 class="text-lg font-semibold">Tugas / Assignment</h3>
                    <p class="text-sm">Kelola tugas, deadline, dan penilaian</p>
                </div>
            </div>
        </a>

        <!-- Soal -->
        <a href="{{ route('adminprogram.questions.index') }}" class="block bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow p-6 transition">
            <div class="flex items-center">
                <i class="fas fa-list-ol text-3xl mr-4"></i>
                <div>
                    <h3 class="text-lg font-semibold">Soal</h3>
                    <p class="text-sm">Kelola bank soal untuk ujian dan tugas</p>
                </div>
            </div>
        </a>

        <!-- Submission / Peserta -->
        <a href="{{ route('adminprogram.submissions.index') }}" class="block bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg shadow p-6 transition">
            <div class="flex items-center">
                <i class="fas fa-users text-3xl mr-4"></i>
                <div>
                    <h3 class="text-lg font-semibold">Submission & Peserta</h3>
                    <p class="text-sm">Lihat submission peserta dan status penilaian</p>
                </div>
            </div>
        </a>

        <!-- Nilai & Feedback -->
        <a href="{{ route('adminprogram.grades.index') }}" class="block bg-red-600 hover:bg-red-700 text-white rounded-lg shadow p-6 transition">
            <div class="flex items-center">
                <i class="fas fa-star text-3xl mr-4"></i>
                <div>
                    <h3 class="text-lg font-semibold">Nilai & Feedback</h3>
                    <p class="text-sm">Input nilai, feedback, dan review peserta</p>
                </div>
            </div>
        </a>

        <!-- Statistik / Laporan -->
        <a href="{{ route('adminprogram.reports.index') }}" class="block bg-purple-600 hover:bg-purple-700 text-white rounded-lg shadow p-6 transition">
            <div class="flex items-center">
                <i class="fas fa-chart-bar text-3xl mr-4"></i>
                <div>
                    <h3 class="text-lg font-semibold">Statistik & Laporan</h3>
                    <p class="text-sm">Lihat statistik ujian, tugas, dan performa peserta</p>
                </div>
            </div>
        </a>

    </div>

</div>
@endsection
