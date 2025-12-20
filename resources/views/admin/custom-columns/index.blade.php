@extends('adminprogram.layouts.app')


@section('title', 'Kolom Penilaian Custom')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-columns text-primary"></i>
        Kolom Penilaian Custom
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.custom-columns.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Kolom
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card card-shadow">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="kelas_filter" class="form-label">Filter by Kelas:</label>
                        <select id="kelas_filter" class="form-select">
                            <option value="">Semua Kelas</option>
                            @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        @if($customColumns->count() > 0)
            @foreach($customColumns as $kelasId => $columns)
            <div class="card card-shadow mb-4 kelas-section" data-kelas="{{ $kelasId }}">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chalkboard-teacher me-2"></i>
                        {{ $columns->first()->kelas->nama_kelas }}
                        <span class="badge bg-light text-dark ms-2">{{ $columns->count() }} kolom</span>
                    </h5>
                    <a href="{{ route('admin.custom-values.bulk-edit', $kelasId) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-edit me-1"></i> Input Nilai
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Kolom</th>
                                    <th>Jumlah Nilai</th>
                                    <th>Rata-rata Nilai</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($columns as $column)
                                <tr>
                                    <td>
                                        <strong>{{ $column->name }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $column->customGradeValues->count() }} nilai
                                        </span>
                                    </td>
                                    <td>
                                        @if($column->customGradeValues->count() > 0)
                                            {{ number_format($column->customGradeValues->avg('score'), 2) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $column->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.custom-columns.edit', $column->id) }}"
                                               class="btn btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.custom-columns.destroy', $column->id) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                        onclick="return confirm('Hapus kolom ini? Semua nilai yang terkait juga akan dihapus.')">
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
                </div>
            </div>
            @endforeach
        @else
            <div class="card card-shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-columns fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Belum ada kolom penilaian custom</h4>
                    <p class="text-muted">Tambahkan kolom penilaian custom untuk menilai aspek khusus</p>
                    <a href="{{ route('admin.custom-columns.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah Kolom Pertama
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kelasFilter = document.getElementById('kelas_filter');
    const kelasSections = document.querySelectorAll('.kelas-section');

    kelasFilter.addEventListener('change', function() {
        const selectedKelas = this.value;

        kelasSections.forEach(section => {
            if (!selectedKelas || section.dataset.kelas === selectedKelas) {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        });
    });
});
</script>
@endpush
