@extends('participant.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-10">

    <div class="bg-white shadow-lg p-7 rounded-lg max-w-3xl mx-auto">

        <h1 class="text-2xl font-semibold">{{ $exam->title }}</h1>

        <p class="text-gray-700 mt-3 whitespace-pre-line">
            {{ $exam->instructions }}
        </p>

        <div class="mt-6 text-gray-600">
            <p>Durasi Ujian: <b>{{ $exam->duration_minutes }} menit</b></p>
        </div>

        <form action="{{ route('participant.essay.startExam', $exam->id) }}" method="POST" class="mt-8">
            @csrf

            <button class="px-6 py-2 bg-green-600 text-white rounded-lg">
                Mulai Ujian
            </button>
        </form>

    </div>

</div>
@endsection
