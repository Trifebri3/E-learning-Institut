@extends('instructor.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">Preview Kuis: {{ $quiz->title }}</h4>
                        <p class="text-muted mb-0">Tampilan seperti yang dilihat peserta</p>
                    </div>
                    <div>
                        <a href="{{ route('instructor.quizzes.edit', $quiz->id) }}" class="btn btn-secondary">
                            <i class="fas fa-edit me-2"></i>Edit Kuis
                        </a>
                        <a href="{{ route('instructor.kelas.edit', $quiz->kelas_id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Quiz Info -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h5>{{ $quiz->title }}</h5>
                            @if($quiz->description)
                                <p class="text-muted">{{ $quiz->description }}</p>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <h6 class="mb-0">{{ $quiz->questions->count() }}</h6>
                                            <small class="text-muted">Soal</small>
                                        </div>
                                        <div class="col-4">
                                            <h6 class="mb-0">{{ $quiz->duration_minutes }}</h6>
                                            <small class="text-muted">Menit</small>
                                        </div>
                                        <div class="col-4">
                                            <h6 class="mb-0">{{ $quiz->max_attempts }}</h6>
                                            <small class="text-muted">Percobaan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Questions Preview -->
                    @if($quiz->questions->count() > 0)
                        <div class="questions-preview">
                            @foreach($quiz->questions as $index => $question)
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Soal #{{ $index + 1 }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="question-content mb-3">
                                        <p class="mb-3">{{ $question->question_text }}</p>

                                        @if($question->image_path)
                                            <div class="mb-3">
                                                <img src="{{ Storage::url($question->image_path) }}"
                                                     alt="Gambar soal" class="img-fluid rounded" style="max-height: 300px;">
                                            </div>
                                        @endif
                                    </div>

                                    <div class="options">
                                        @foreach($question->answers as $answerIndex => $answer)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="question_{{ $question->id }}"
                                                   id="option_{{ $answer->id }}" disabled>
                                            <label class="form-check-label w-100 {{ $answer->is_correct ? 'text-success fw-bold' : '' }}"
                                                   for="option_{{ $answer->id }}">
                                                <span class="badge bg-secondary me-2">{{ chr(65 + $answerIndex) }}</span>
                                                {{ $answer->option_text }}
                                                @if($answer->is_correct)
                                                    <span class="badge bg-success ms-2">Jawaban Benar</span>
                                                @endif
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada soal</h5>
                            <p class="text-muted">Tambahkan soal untuk melihat preview</p>
                            <a href="{{ route('instructor.quizzes.edit', $quiz->id) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah Soal
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.questions-preview .card {
    border-left: 4px solid #007bff;
}

.form-check-label {
    cursor: pointer;
    padding: 8px 12px;
    border-radius: 5px;
    transition: background-color 0.2s;
}

.form-check-label:hover {
    background-color: #f8f9fa;
}
</style>
@endpush
