<!-- Modal Tambah Soal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1" aria-labelledby="addQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addQuestionModalLabel">Tambah Soal Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('instructor.quizzes.questions.store', $quiz->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="question_text" class="form-label">Pertanyaan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="question_text" name="question_text" rows="3" required>{{ old('question_text') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar (Opsional)</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Opsi Jawaban <span class="text-danger">*</span></label>
                        <div id="optionsContainer">
                            @for($i = 0; $i < 2; $i++)
                            <div class="input-group mb-2">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="correct_option"
                                           value="{{ $i }}" {{ $i == 0 ? 'checked' : '' }}>
                                </div>
                                <input type="text" class="form-control" name="options[]"
                                       placeholder="Opsi {{ chr(65 + $i) }}" required>
                                @if($i >= 2)
                                <button type="button" class="btn btn-outline-danger remove-option">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                            </div>
                            @endfor
                        </div>

                        <button type="button" id="addOption" class="btn btn-outline-primary btn-sm mt-2">
                            <i class="fas fa-plus me-1"></i>Tambah Opsi
                        </button>
                        <div class="form-text">Pilih opsi yang benar dengan mencentang radio button.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Soal</button>
                </div>
            </form>
        </div>
    </div>
</div>
