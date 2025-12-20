@extends('instructor.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Daftar Kuis</h4>
                    <div>
                        @if($firstKelas)
                            <a href="{{ route('instructor.quizzes.create', $firstKelas->id) }}" class="btn btn-primary me-2">
                                <i class="fas fa-plus me-2"></i>Buat Kuis Baru
                            </a>
                        @endif
                        <a href="{{ route('instructor.kelas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Kelas
                        </a>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="card-body border-bottom">
                    <form action="{{ route('instructor.quizzes.index') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="kelas_id" class="form-label">Filter Kelas</label>
                            <select name="kelas_id" id="kelas_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Kelas</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">Filter Status</label>
                            <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <a href="{{ route('instructor.quizzes.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-refresh me-2"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($quizzes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Judul Kuis</th>
                                        <th>Kelas</th>
                                        <th>Program</th>
                                        <th>Jumlah Soal</th>
                                        <th>Durasi</th>
                                        <th>Status</th>
                                        <th>Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($quizzes as $quiz)
                                    <tr>
                                        <td>{{ $loop->iteration + ($quizzes->currentPage() - 1) * $quizzes->perPage() }}</td>
                                        <td>
                                            <strong>{{ $quiz->title }}</strong>
                                            @if($quiz->description)
                                                <br><small class="text-muted">{{ Str::limit($quiz->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $quiz->kelas->title }}</td>
                                        <td>{{ $quiz->kelas->program->name }}</td>
                                        <td>{{ $quiz->questions_count }} Soal</td>
                                        <td>{{ $quiz->duration_minutes }} menit</td>
                                        <td>
                                            @if($quiz->is_published)
                                                <span class="badge bg-success">Published</span>
                                            @else
                                                <span class="badge bg-warning">Draft</span>
                                            @endif
                                        </td>
                                        <td>{{ $quiz->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('instructor.quizzes.edit', $quiz->id) }}"
                                                   class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('instructor.quizzes.preview', $quiz->id) }}"
                                                   class="btn btn-sm btn-info" title="Preview">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <form action="{{ route('instructor.quizzes.toggle-publish', $quiz->id) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-{{ $quiz->is_published ? 'warning' : 'success' }}"
                                                            title="{{ $quiz->is_published ? 'Unpublish' : 'Publish' }}">
                                                        <i class="fas fa-{{ $quiz->is_published ? 'eye-slash' : 'eye' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('instructor.quizzes.destroy', $quiz->id) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Hapus kuis ini?')" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $quizzes->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada kuis</h5>
                            <p class="text-muted">Mulai dengan membuat kuis pertama Anda</p>
                            @if($kelas->count() > 0)
                                <div class="mt-3">
                                    <a href="{{ route('instructor.quizzes.create', $kelas->first()->id) }}" class="btn btn-primary me-2">
                                        <i class="fas fa-plus me-2"></i>Buat Kuis Pertama
                                    </a>
                                    <a href="{{ route('instructor.kelas.index') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-list me-2"></i>Lihat Kelas
                                    </a>
                                </div>
                            @else
                                <div class="mt-3">

                                </div>
                            @endif
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
.btn-group .btn {
    margin-right: 2px;
}
.table th {
    border-top: none;
    font-weight: 600;
}
</style>
@endpush
