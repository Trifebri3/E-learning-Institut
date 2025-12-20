@extends('instructor.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Buat Kuis Baru</h4>
                    <p class="text-muted mb-0">Kelas: {{ $kelas->title }}</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('instructor.quizzes.store', $kelas->id) }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul Kuis <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="duration_minutes" class="form-label">Durasi (menit) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror"
                                                   id="duration_minutes" name="duration_minutes"
                                                   value="{{ old('duration_minutes', 30) }}" min="1" required>
                                            @error('duration_minutes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="max_attempts" class="form-label">Maksimal Percobaan <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('max_attempts') is-invalid @enderror"
                                                   id="max_attempts" name="max_attempts"
                                                   value="{{ old('max_attempts', 1) }}" min="1" required>
                                            @error('max_attempts')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_published" name="is_published"
                                               {{ old('is_published') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_published">
                                            Publikasikan kuis
                                        </label>
                                    </div>
                                    <small class="text-muted">Jika dicentang, kuis akan langsung tersedia untuk peserta</small>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Informasi Kelas</h6>
                                        <p><strong>{{ $kelas->title }}</strong></p>
                                        <p class="mb-1"><small>Program: {{ $kelas->program->name }}</small></p>
                                        <p class="mb-0"><small>Tanggal: {{ $kelas->tanggal->format('d M Y') }}</small></p>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-save me-2"></i>Simpan Kuis
                                    </button>
                                    <a href="{{ route('instructor.kelas.edit', $kelas->id) }}"
                                       class="btn btn-secondary w-100 mt-2">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
