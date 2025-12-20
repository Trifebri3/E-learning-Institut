@php
    $answers = $question->answers->sortBy('id'); // Sort by ID untuk konsistensi
@endphp

<div class="form-group">
    <label for="edit_question_text">Pertanyaan *</label>
    <textarea class="form-control @error('question_text') is-invalid @enderror"
              id="edit_question_text" name="question_text" rows="3" required>{{ old('question_text', $question->question_text) }}</textarea>
    @error('question_text')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="edit_image">Gambar (Opsional)</label>

    @if($question->image_path)
        <div class="mb-3">
            <p><strong>Gambar saat ini:</strong></p>
            <img src="{{ Storage::disk('public')->url($question->image_path) }}"
                 alt="Gambar soal" style="max-height: 150px;" class="img-thumbnail d-block mb-2">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="remove_image">
                <label class="form-check-label text-danger" for="remove_image">
                    <i class="fas fa-trash"></i> Hapus gambar ini
                </label>
            </div>
        </div>
        <hr>
    @endif

    <input type="file" class="form-control-file @error('image') is-invalid @enderror"
           id="edit_image" name="image" accept="image/*">
    @error('image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB. Biarkan kosong jika tidak ingin mengubah.</small>
</div>

<div class="form-group">
    <label>Pilihan Jawaban *</label>
    <div class="options-container">
        @foreach($answers as $index => $answer)
            <div class="option-row mb-2">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{ chr(65 + $index) }}</span>
                    </div>
                    <input type="text" class="form-control @error('options.' . $answer->id) is-invalid @enderror"
                           name="options[{{ $answer->id }}]"
                           value="{{ old("options.{$answer->id}", $answer->option_text) }}" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <input type="radio" name="correct_option" value="{{ $answer->id }}"
                                   {{ old('correct_option', $answer->is_correct ? $answer->id : '') == $answer->id ? 'checked' : '' }} required>
                        </div>
                    </div>
                </div>
                @error('options.' . $answer->id)
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
        @endforeach
    </div>
    @error('correct_option')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
    <small class="form-text text-muted">Centang jawaban yang benar.</small>
</div>

<input type="hidden" name="question_id" value="{{ $question->id }}">
