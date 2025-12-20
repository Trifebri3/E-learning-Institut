<!-- Modal Edit Soal -->
<div class="modal fade" id="editQuestionModal{{ $question->id }}" tabindex="-1" aria-labelledby="editQuestionModalLabel{{ $question->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editQuestionModalLabel{{ $question->id }}">Edit Soal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('instructor.quizzes.questions.update', $question->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="question_text_{{ $question->id }}" class="form-label">Pertanyaan</label>
                        <textarea class="form-control" id="question_text_{{ $question->id }}" name="question_text" rows="3" required>{{ $question->question_text }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar Saat Ini</label>
                        @if($question->image_path)
                            <div class="mb-2">
                                <img src="{{ Storage::url($question->image_path) }}" alt="Gambar soal" class="img-thumbnail" style="max-height: 150px;">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image_{{ $question->id }}">
                                    <label class="form-check-label" for="remove_image_{{ $question->id }}">
                                        Hapus gambar
                                    </label>
                                </div>
                            </div>
                        @else
                            <p class="text-muted">Tidak ada gambar</p>
                        @endif

                        <label for="image_{{ $question->id }}" class="form-label">Gambar Baru (Opsional)</label>
                        <input type="file" class="form-control" id="image_{{ $question->id }}" name="image" accept="image/*">
                        <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Opsi Jawaban</label>
                        <div id="optionsContainer_{{ $question->id }}">
                            @foreach($question->answers as $answer)
                            <div class="input-group mb-2">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="correct_option"
                                           value="{{ $answer->id }}" {{ $answer->is_correct ? 'checked' : '' }}>
                                </div>
                                <input type="text" class="form-control" name="options[{{ $answer->id }}]"
                                       value="{{ $answer->option_text }}" required>
                                <button type="button" class="btn btn-outline-danger remove-option">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-sm mt-2 add-option" data-target="optionsContainer_{{ $question->id }}">
                            <i class="fas fa-plus me-1"></i>Tambah Opsi
                        </button>
                        <div class="form-text">Pilih opsi yang benar dengan mencentang radio button.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Soal</button>
                </div>
            </form>
        </div>
    </div>
</div>
