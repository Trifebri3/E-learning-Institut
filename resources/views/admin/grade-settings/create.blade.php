@extends('adminprogram.layouts.app')

@section('title', 'Tambah Pengaturan Bobot')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus text-primary"></i>
        Tambah Pengaturan Bobot
    </h1>
    <a href="{{ route('admin.grade-settings.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card card-shadow">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Form Pengaturan Bobot</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.grade-settings.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="kelas_id" class="form-label">Pilih Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="form-select @error('kelas_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ old('kelas_id', request('kelas_id')) == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="weight_presensi" class="form-label">Bobot Presensi (%)</label>
                                <input type="number" name="weight_presensi" id="weight_presensi"
                                       class="form-control weight-input @error('weight_presensi') is-invalid @enderror"
                                       value="{{ old('weight_presensi', 0) }}" min="0" max="100" required>
                                @error('weight_presensi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="weight_tugas" class="form-label">Bobot Tugas (%)</label>
                                <input type="number" name="weight_tugas" id="weight_tugas"
                                       class="form-control weight-input @error('weight_tugas') is-invalid @enderror"
                                       value="{{ old('weight_tugas', 0) }}" min="0" max="100" required>
                                @error('weight_tugas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="weight_quiz" class="form-label">Bobot Quiz (%)</label>
                                <input type="number" name="weight_quiz" id="weight_quiz"
                                       class="form-control weight-input @error('weight_quiz') is-invalid @enderror"
                                       value="{{ old('weight_quiz', 0) }}" min="0" max="100" required>
                                @error('weight_quiz')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="weight_essay" class="form-label">Bobot Essay (%)</label>
                                <input type="number" name="weight_essay" id="weight_essay"
                                       class="form-control weight-input @error('weight_essay') is-invalid @enderror"
                                       value="{{ old('weight_essay', 0) }}" min="0" max="100" required>
                                @error('weight_essay')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="weight_progress" class="form-label">Bobot Progress (%)</label>
                                <input type="number" name="weight_progress" id="weight_progress"
                                       class="form-control weight-input @error('weight_progress') is-invalid @enderror"
                                       value="{{ old('weight_progress', 0) }}" min="0" max="100" required>
                                @error('weight_progress')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="weight_custom" class="form-label">Bobot Custom (%)</label>
                                <input type="number" name="weight_custom" id="weight_custom"
                                       class="form-control weight-input @error('weight_custom') is-invalid @enderror"
                                       value="{{ old('weight_custom', 0) }}" min="0" max="100" required>
                                @error('weight_custom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Total Bobot: <span id="total-weight">0</span>%</strong>
                        <span id="weight-status" class="ms-2"></span>
                    </div>

                    @error('total_weight')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-shadow">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-lightbulb me-2"></i>Petunjuk
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Total bobot harus tepat 100%
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Bobot untuk komponen yang tidak digunakan bisa diisi 0
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Sistem akan menyesuaikan perhitungan otomatis
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const weightInputs = document.querySelectorAll('.weight-input');
    const totalWeightSpan = document.getElementById('total-weight');
    const weightStatusSpan = document.getElementById('weight-status');

    function calculateTotalWeight() {
        let total = 0;
        weightInputs.forEach(input => {
            total += parseInt(input.value) || 0;
        });

        totalWeightSpan.textContent = total;

        if (total === 100) {
            weightStatusSpan.innerHTML = '<span class="badge bg-success">Valid</span>';
        } else if (total < 100) {
            weightStatusSpan.innerHTML = `<span class="badge bg-warning">Kurang ${100 - total}%</span>`;
        } else {
            weightStatusSpan.innerHTML = `<span class="badge bg-danger">Lebih ${total - 100}%</span>`;
        }
    }

    weightInputs.forEach(input => {
        input.addEventListener('input', calculateTotalWeight);
    });

    // Initial calculation
    calculateTotalWeight();
});
</script>
@endpush
