@extends('instructor.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">Edit Kuis: {{ $quiz->title }}</h4>
                        <p class="text-muted mb-0">Kelas: {{ $quiz->kelas->title }}</p>
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('instructor.quizzes.preview', $quiz->id) }}" class="btn btn-info">
                            <i class="fas fa-eye me-2"></i>Preview
                        </a>
                        <form action="{{ route('instructor.quizzes.toggle-publish', $quiz->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $quiz->is_published ? 'warning' : 'success' }}">
                                <i class="fas fa-{{ $quiz->is_published ? 'eye-slash' : 'eye' }} me-2"></i>
                                {{ $quiz->is_published ? 'Unpublish' : 'Publish' }}
                            </button>
                        </form>
                        <a href="{{ route('instructor.kelas.edit', $quiz->kelas_id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Informasi Kuis -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Kuis</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('instructor.quizzes.update', $quiz->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Kuis</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $quiz->title }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ $quiz->description }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="duration_minutes" class="form-label">Durasi (menit)</label>
                                    <input type="number" class="form-control" id="duration_minutes" name="duration_minutes"
                                           value="{{ $quiz->duration_minutes }}" min="1" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="max_attempts" class="form-label">Maks Percobaan</label>
                                    <input type="number" class="form-control" id="max_attempts" name="max_attempts"
                                           value="{{ $quiz->max_attempts }}" min="1" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_published" name="is_published"
                                       {{ $quiz->is_published ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_published">
                                    Publikasikan
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Update Kuis
                        </button>
                    </form>

                    <hr>

                    <!-- Statistik -->
                    <div class="mt-3">
                        <h6>Statistik Kuis</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-question-circle me-2 text-primary"></i> {{ $quiz->questions->count() }} Soal</li>
                            <li><i class="fas fa-clock me-2 text-primary"></i> {{ $quiz->duration_minutes }} Menit</li>
                            <li><i class="fas fa-redo me-2 text-primary"></i> {{ $quiz->max_attempts }} Percobaan</li>
                            <li>
                                <i class="fas fa-circle me-2 text-{{ $quiz->is_published ? 'success' : 'warning' }}"></i>
                                {{ $quiz->is_published ? 'Published' : 'Draft' }}
                            </li>
                        </ul>
                    </div>

                    <!-- Danger Zone -->
                    <div class="mt-4">
                        <h6 class="text-danger">Danger Zone</h6>
                        <form action="{{ route('instructor.quizzes.destroy', $quiz->id) }}" method="POST"
                              onsubmit="return confirm('Hapus kuis ini? Tindakan tidak dapat dibatalkan!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                <i class="fas fa-trash me-2"></i>Hapus Kuis
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Soal -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Soal</h5>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
                        <i class="fas fa-plus me-2"></i>Tambah Soal
                    </button>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($quiz->questions->count() > 0)
                        <div class="list-group">
                            @foreach($quiz->questions as $question)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Soal #{{ $loop->iteration }}</h6>
                                        <p class="mb-2">{{ $question->question_text }}</p>

                                        @if($question->image_path)
                                            <div class="mb-2">
                                                <img src="{{ Storage::url($question->image_path) }}" alt="Gambar soal"
                                                     class="img-thumbnail" style="max-height: 100px;">
                                            </div>
                                        @endif

                                        <div class="options">
                                            @foreach($question->answers as $answer)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" disabled
                                                           {{ $answer->is_correct ? 'checked' : '' }}>
                                                    <label class="form-check-label {{ $answer->is_correct ? 'text-success fw-bold' : '' }}">
                                                        {{ $answer->option_text }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="btn-group ms-3">
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editQuestionModal{{ $question->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('instructor.quizzes.questions.destroy', $question->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Hapus soal ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Question Modal -->
                            @include('instructor.quizzes.partials.question-form-edit', ['question' => $question])
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada soal</h5>
                            <p class="text-muted">Tambahkan soal pertama untuk kuis ini</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
                                <i class="fas fa-plus me-2"></i>Tambah Soal Pertama
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Question Modal -->
@include('instructor.quizzes.partials.question-form-create')

@endsection

@push('scripts')
<script>
// Dynamic options management
document.addEventListener('DOMContentLoaded', function() {
    // Add option
    document.getElementById('addOption').addEventListener('click', function() {
        const optionsContainer = document.getElementById('optionsContainer');
        const optionCount = optionsContainer.children.length;

        if (optionCount < 5) {
            const newOption = document.createElement('div');
            newOption.className = 'input-group mb-2';
            newOption.innerHTML = `
                <input type="text" class="form-control" name="options[]" placeholder="Opsi ${String.fromCharCode(65 + optionCount)}" required>
                <button type="button" class="btn btn-outline-danger remove-option">
                    <i class="fas fa-times"></i>
                </button>
            `;
            optionsContainer.appendChild(newOption);
        }
    });

    // Remove option
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-option') || e.target.closest('.remove-option')) {
            const optionDiv = e.target.closest('.input-group');
            if (document.getElementById('optionsContainer').children.length > 2) {
                optionDiv.remove();
            }
        }
    });
});
</script>
@endpush
