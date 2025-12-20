@extends('participant.layouts.app')

@section('title', 'Dashboard')

@section('content')

    @include('components.profile-mini', ['user' => Auth::user()])
{{-- Ambil program aktif user --}}

        @yield('kelas-card')

        {{-- Di dalam dashboard.blade.php, di kolom aktivitas --}}

<h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Tugas Terbaru</h3>
<div class="space-y-4">
    @foreach($recentAssignments as $assignment)
        @php
            // Cari submission user untuk tugas ini
            $submission = $assignment->userSubmission(Auth::id());
        @endphp

        <x-assignment-status-card :assignment="$assignment" :submission="$submission" isCompact="true" />
    @endforeach
</div>
@endsection
