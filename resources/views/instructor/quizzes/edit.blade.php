@extends('instructor.layouts.app')

@section('title', 'Edit Kuis: ' . $quiz->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <!-- Alert Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="icon fas fa-check"></i> {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="icon fas fa-ban"></i> {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Card Informasi Kuis -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pengaturan Kuis</h3>
                </div>
                <!-- PERBAIKAN: Gunakan method PUT untuk update -->
                <form action="{{ route('instructor.quizzes.update', $quiz->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- Ini yang penting! -->
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Judul Kuis *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title', $quiz->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3">{{ old('description', $quiz->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="duration_minutes">Durasi (menit) *</label>
                                    <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror"
                                           id="duration_minutes" name="duration_minutes"
                                           value="{{ old('duration_minutes', $quiz->duration_minutes) }}" min="1" required>
                                    @error('duration_minutes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="max_attempts">Maksimal Percobaan *</label>
                                    <input type="number" class="form-control @error('max_attempts') is-invalid @enderror"
                                           id="max_attempts" name="max_attempts"
                                           value="{{ old('max_attempts', $quiz->max_attempts) }}" min="0" required>
                                    <small class="form-text text-muted">0 = Unlimited</small>
                                    @error('max_attempts')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_published" name="is_published" value="1"
                                       {{ old('is_published', $quiz->is_published) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_published">Publikasikan Kuis</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Kuis
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card Daftar Soal -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Daftar Soal ({{ $quiz->questions->count() }})</h3>
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addQuestionModal">
                        <i class="fas fa-plus"></i> Tambah Soal
                    </button>
                </div>
                <div class="card-body">
                    @if($quiz->questions->count() > 0)
                        <div class="list-group">
                            @foreach($quiz->questions as $index => $question)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="badge badge-primary mr-2">#{{ $index + 1 }}</span>
                                                <h6 class="mb-0">{{ Str::limit($question->question_text, 100) }}</h6>
                                            </div>

                                            @if($question->image_path)
                                                <div class="mt-2 mb-2">
                                                    <img src="{{ Storage::disk('public')->url($question->image_path) }}"
                                                         alt="Gambar soal" style="max-height: 80px;" class="img-thumbnail">
                                                </div>
                                            @endif

                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <strong>Jawaban:</strong>
                                                    @foreach($question->answers as $answer)
                                                        <span class="{{ $answer->is_correct ? 'text-success font-weight-bold' : 'text-muted' }}">
                                                            {{ $answer->option_text }}
                                                        </span>
                                                        @if(!$loop->last) | @endif
                                                    @endforeach
                                                </small>
                                            </div>
                                        </div>
                                        <div class="btn-group ml-3">
                                            <button type="button" class="btn btn-sm btn-outline-primary edit-question"
                                                    data-question-id="{{ $question->id }}"
                                                    data-toggle="tooltip" title="Edit Soal">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('instructor.quizzes.questions.destroy', $question->id) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Hapus soal ini? Tindakan tidak dapat dibatalkan.')"
                                                        data-toggle="tooltip" title="Hapus Soal">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada soal. Tambahkan soal pertama Anda.</p>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addQuestionModal">
                                <i class="fas fa-plus"></i> Tambah Soal Pertama
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Card Informasi -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Kuis</h3>
                </div>
                <div class="card-body">
                    <div class="info-box bg-light">
                        <span class="info-box-icon bg-info"><i class="fas fa-question-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Soal</span>
                            <span class="info-box-number">{{ $quiz->questions->count() }}</span>
                        </div>
                    </div>

                    <div class="info-box bg-light">
                        <span class="info-box-icon bg-success"><i class="fas fa-clock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Durasi</span>
                            <span class="info-box-number">{{ $quiz->duration_minutes }} menit</span>
                        </div>
                    </div>

                    <div class="info-box bg-light">
                        <span class="info-box-icon bg-warning"><i class="fas fa-redo"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Percobaan</span>
                            <span class="info-box-number">
                                {{ $quiz->max_attempts == 0 ? 'Unlimited' : $quiz->max_attempts . ' kali' }}
                            </span>
                        </div>
                    </div>

                    <div class="info-box bg-light">
                        <span class="info-box-icon {{ $quiz->is_published ? 'bg-success' : 'bg-secondary' }}">
                            <i class="fas fa-eye"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Status</span>
                            <span class="info-box-number">
                                {{ $quiz->is_published ? 'Diterbitkan' : 'Draft' }}
                            </span>
                        </div>
                    </div>

                    <hr>
                    <div class="d-grid gap-2">
                        <a href="{{ route('instructor.kelas.edit', $quiz->kelas_id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Kelas
                        </a>
                        <form action="{{ route('instructor.quizzes.destroy', $quiz->id) }}" method="POST" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Hapus kuis ini? Semua soal dan data terkait akan dihapus. Tindakan tidak dapat dibatalkan.')">
                                <i class="fas fa-trash"></i> Hapus Kuis
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Soal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('instructor.quizzes.questions.store', $quiz->id) }}" method="POST" enctype="multipart/form-data" id="addQuestionForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Soal Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="question_text">Pertanyaan *</label>
                        <textarea class="form-control @error('question_text') is-invalid @enderror"
                                  id="question_text" name="question_text" rows="3"
                                  placeholder="Tulis pertanyaan di sini..." required>{{ old('question_text') }}</textarea>
                        @error('question_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="image">Gambar (Opsional)</label>
                        <input type="file" class="form-control-file @error('image') is-invalid @enderror"
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                    </div>

                    <div class="form-group">
                        <label>Pilihan Jawaban * <small>(Minimal 2 pilihan)</small></label>
                        <div class="options-container">
                            @php
                                $oldOptions = old('options', ['', '']);
                                $oldCorrect = old('correct_option', 0);
                            @endphp

                            @foreach($oldOptions as $index => $option)
                                <div class="option-row mb-2">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{ chr(65 + $index) }}</span>
                                        </div>
                                        <input type="text" class="form-control @error('options.' . $index) is-invalid @enderror"
                                               name="options[]" value="{{ $option }}"
                                               placeholder="Teks jawaban" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <input type="radio" name="correct_option" value="{{ $index }}"
                                                       {{ $oldCorrect == $index ? 'checked' : '' }} required>
                                            </div>
                                            @if($index >= 2)
                                                <button type="button" class="btn btn-outline-danger remove-option">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    @error('options.' . $index)
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>

                        @if(count($oldOptions) < 5)
                            <button type="button" class="btn btn-sm btn-outline-primary add-option mt-2">
                                <i class="fas fa-plus"></i> Tambah Pilihan
                            </button>
                        @endif
                        @error('correct_option')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Centang jawaban yang benar.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Soal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Soal -->
<div class="modal fade" id="editQuestionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="editQuestionForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Soal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="editQuestionBody">
                    <!-- Form akan diisi via JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Soal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.option-row {
    transition: all 0.3s ease;
}

.info-box {
    margin-bottom: 1rem;
    border-radius: 0.25rem;
}

.list-group-item {
    border-left: 3px solid #007bff;
    margin-bottom: 0.5rem;
    border-radius: 0.25rem;
}

.info-box-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Edit Question Modal
    const editButtons = document.querySelectorAll('.edit-question');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const questionId = this.getAttribute('data-question-id');

            // Show loading state
            document.getElementById('editQuestionBody').innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat form edit...</p>
                </div>
            `;

            // Set form action
            document.getElementById('editQuestionForm').action = `/adminprogram/questions/${questionId}`;

            // Load form content via AJAX
            fetch(`/adminprogram/questions/${questionId}/edit`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(html => {
                    document.getElementById('editQuestionBody').innerHTML = html;
                    $('#editQuestionModal').modal('show');
                })
                .catch(error => {
                    console.error('Error loading edit form:', error);
                    document.getElementById('editQuestionBody').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            Gagal memuat form edit. Silakan refresh halaman dan coba lagi.
                        </div>
                    `;
                });
        });
    });

    // Add option field dynamically untuk form tambah soal
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-option')) {
            const optionsContainer = e.target.closest('.form-group').querySelector('.options-container');
            const optionCount = optionsContainer.querySelectorAll('.option-row').length;

            if (optionCount < 5) {
                const newOption = `
                    <div class="option-row mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">${String.fromCharCode(65 + optionCount)}</span>
                            </div>
                            <input type="text" class="form-control" name="options[]" placeholder="Teks jawaban" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <input type="radio" name="correct_option" value="${optionCount}" required>
                                </div>
                                <button type="button" class="btn btn-outline-danger remove-option">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                optionsContainer.insertAdjacentHTML('beforeend', newOption);

                // Scroll to new option
                const newOptionElement = optionsContainer.lastElementChild;
                newOptionElement.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                alert('Maksimal 5 pilihan jawaban (A-E)');
            }
        }

        // Remove option
        if (e.target.classList.contains('remove-option') || e.target.closest('.remove-option')) {
            const removeBtn = e.target.classList.contains('remove-option') ? e.target : e.target.closest('.remove-option');
            const optionRow = removeBtn.closest('.option-row');
            const optionsContainer = optionRow.parentElement;
            const optionRows = optionsContainer.querySelectorAll('.option-row');

            if (optionRows.length > 2) {
                // Check if we're removing the correct answer
                const correctRadio = optionRow.querySelector('input[type="radio"]');
                if (correctRadio && correctRadio.checked) {
                    // Set first option as correct if removing the correct one
                    optionRows[0].querySelector('input[type="radio"]').checked = true;
                }

                optionRow.remove();

                // Update option labels and radio values
                optionRows.forEach((row, index) => {
                    const label = row.querySelector('.input-group-text');
                    const radio = row.querySelector('input[type="radio"]');
                    if (label) {
                        label.textContent = String.fromCharCode(65 + index);
                    }
                    if (radio) {
                        radio.value = index;
                    }
                });
            } else {
                alert('Minimal harus ada 2 pilihan jawaban');
            }
        }
    });

    // Handle modal hidden events
    $('#addQuestionModal').on('hidden.bs.modal', function () {
        // Reset form when modal is closed
        document.getElementById('addQuestionForm').reset();

        // Reset to 2 options
        const optionsContainer = document.querySelector('#addQuestionModal .options-container');
        if (optionsContainer) {
            const optionRows = optionsContainer.querySelectorAll('.option-row');
            if (optionRows.length > 2) {
                for (let i = optionRows.length - 1; i >= 2; i--) {
                    optionRows[i].remove();
                }
            }

            // Set first option as correct
            const firstRadio = optionsContainer.querySelector('input[type="radio"]');
            if (firstRadio) {
                firstRadio.checked = true;
            }
        }
    });

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
});

// Form validation
function validateQuestionForm(form) {
    const questionText = form.querySelector('#question_text').value.trim();
    const options = form.querySelectorAll('input[name="options[]"]');
    const correctOption = form.querySelector('input[name="correct_option"]:checked');

    if (!questionText) {
        alert('Pertanyaan harus diisi');
        return false;
    }

    let hasEmptyOption = false;
    options.forEach(option => {
        if (!option.value.trim()) {
            hasEmptyOption = true;
        }
    });

    if (hasEmptyOption) {
        alert('Semua pilihan jawaban harus diisi');
        return false;
    }

    if (!correctOption) {
        alert('Pilih jawaban yang benar');
        return false;
    }

    return true;
}

// Add form validation to add question form
document.addEventListener('DOMContentLoaded', function() {
    const addQuestionForm = document.getElementById('addQuestionForm');
    if (addQuestionForm) {
        addQuestionForm.addEventListener('submit', function(e) {
            if (!validateQuestionForm(this)) {
                e.preventDefault();
            }
        });
    }

    const editQuestionForm = document.getElementById('editQuestionForm');
    if (editQuestionForm) {
        editQuestionForm.addEventListener('submit', function(e) {
            if (!validateQuestionForm(this)) {
                e.preventDefault();
            }
        });
    }
});
</script>
@endpush
