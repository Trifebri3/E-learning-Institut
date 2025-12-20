<div class="form-group">
    <label for="question_text">Pertanyaan *</label>
    <textarea class="form-control" id="question_text" name="question_text" rows="3"
              placeholder="Tulis pertanyaan di sini..." required>{{ old('question_text') }}</textarea>
</div>

<div class="form-group">
    <label for="image">Gambar (Opsional)</label>
    <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
    <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
</div>

<div class="form-group">
    <label>Pilihan Jawaban *</label>
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
                    <input type="text" class="form-control" name="options[]"
                           value="{{ $option }}" placeholder="Teks jawaban" required>
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
            </div>
        @endforeach
    </div>

    @if(count($oldOptions) < 5)
        <button type="button" class="btn btn-sm btn-outline-primary add-option mt-2">
            <i class="fas fa-plus"></i> Tambah Pilihan
        </button>
    @endif
    <small class="form-text text-muted">Minimal 2 pilihan. Centang jawaban yang benar.</small>
</div>
