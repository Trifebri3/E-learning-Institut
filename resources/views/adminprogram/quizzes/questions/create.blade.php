@extends('adminprogram.layouts.app')

@section('title', 'Tambah Kuis Baru')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Kuis Baru</h3>
                    <div class="card-tools">
                        <a href="{{ route('adminprogram.kelas.edit', $kelas->id) }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Kelas
                        </a>
                    </div>
                </div>
                <form action="{{ route('adminprogram.quizzes.store', $kelas->id) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Judul Kuis *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
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
                                           value="{{ old('duration_minutes', 30) }}" min="1" required>
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
                                           value="{{ old('max_attempts', 1) }}" min="0" required>
                                    <small class="form-text text-muted">0 = Unlimited</small>
                                    @error('max_attempts')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_published" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_published">Publikasikan Kuis</label>
                            </div>
                            <small class="form-text text-muted">Jika dipublikasikan, siswa dapat mengerjakan kuis ini.</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Buat Kuis
                        </button>
                        <a href="{{ route('adminprogram.kelas.edit', $kelas->id) }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Kelas</h3>
                </div>
                <div class="card-body">
                    <h5>{{ $kelas->nama_kelas }}</h5>
                    <p class="text-muted">{{ $kelas->program->nama_program }}</p>
                    <hr>
                    <small>
                        <strong>Note:</strong> Setelah membuat kuis, Anda dapat menambahkan soal-soal pada halaman edit kuis.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
