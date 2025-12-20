@extends('adminprogram.layouts.app')
@section('title', 'Pengaturan Bobot Nilai')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-cog text-primary"></i>
        Pengaturan Bobot Nilai
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.grade-settings.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Pengaturan
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-shadow">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Daftar Pengaturan Bobot per Kelas</h5>
            </div>
            <div class="card-body">
                @if($gradeSettings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Kelas</th>
                                    <th>Presensi</th>
                                    <th>Tugas</th>
                                    <th>Quiz</th>
                                    <th>Essay</th>
                                    <th>Progress</th>
                                    <th>Custom</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gradeSettings as $setting)
                                <tr>
                                    <td>
                                        <strong>{{ $setting->kelas->nama_kelas }}</strong>
                                    </td>
                                    <td>{{ $setting->weight_presensi }}%</td>
                                    <td>{{ $setting->weight_tugas }}%</td>
                                    <td>{{ $setting->weight_quiz }}%</td>
                                    <td>{{ $setting->weight_essay }}%</td>
                                    <td>{{ $setting->weight_progress }}%</td>
                                    <td>{{ $setting->weight_custom }}%</td>
                                    <td>
                                        <span class="badge {{ $setting->is_valid_weight ? 'bg-success' : 'bg-danger' }}">
                                            {{ $setting->total_weight }}%
                                        </span>
                                    </td>
                                    <td>
                                        @if($setting->is_valid_weight)
                                            <span class="badge bg-success">Valid</span>
                                        @else
                                            <span class="badge bg-danger">Invalid</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.grade-settings.edit', $setting->id) }}"
                                               class="btn btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.grade-settings.destroy', $setting->id) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                        onclick="return confirm('Hapus pengaturan ini?')">
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
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-cog fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada pengaturan bobot</p>
                        <a href="{{ route('admin.grade-settings.create') }}" class="btn btn-primary">
                            Buat Pengaturan Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Kelas tanpa pengaturan -->
@if($kelasWithoutSettings->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card card-shadow">
            <div class="card-header bg-warning">
                <h5 class="card-title mb-0 text-white">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Kelas Belum Memiliki Pengaturan Bobot
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($kelasWithoutSettings as $kelas)
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6>{{ $kelas->nama_kelas }}</h6>
                                <a href="{{ route('admin.grade-settings.create') }}?kelas_id={{ $kelas->id }}"
                                   class="btn btn-sm btn-primary">
                                    Buat Pengaturan
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
