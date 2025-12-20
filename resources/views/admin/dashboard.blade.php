@extends('adminprogram.layouts.app')


@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tachometer-alt text-primary"></i>
        Dashboard
    </h1>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card card-shadow bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Rapor</h6>
                        <h3>{{ $stats['total_reports'] ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-file-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card card-shadow bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Rapor Published</h6>
                        <h3>{{ $stats['published_reports'] ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card card-shadow bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Peserta Lulus</h6>
                        <h3>{{ $stats['passed_reports'] ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-graduation-cap fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card card-shadow bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Rata-rata Nilai</h6>
                        <h3>{{ number_format($stats['average_score'] ?? 0, 2) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Grade Distribution -->
    <div class="col-md-6 mb-4">
        <div class="card card-shadow">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Distribusi Grade</h5>
            </div>
            <div class="card-body">
                <canvas id="gradeChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="col-md-6 mb-4">
        <div class="card card-shadow">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Rapor Terbaru</h5>
                <a href="{{ route('admin.class-reports.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                @if($recentReports->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentReports as $report)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $report->user->name }}</h6>
                                <small class="text-muted">{{ $report->kelas->nama_kelas }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge badge-grade-{{ $report->letter_grade }} me-2">
                                    {{ $report->letter_grade }}
                                </span>
                                <br>
                                <small class="text-muted">{{ $report->generated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-3">Belum ada rapor</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card card-shadow">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.grade-settings.create') }}" class="btn btn-outline-primary w-100 h-100 py-3">
                            <i class="fas fa-cog fa-2x mb-2"></i><br>
                            Tambah Pengaturan Bobot
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.custom-columns.create') }}" class="btn btn-outline-success w-100 h-100 py-3">
                            <i class="fas fa-columns fa-2x mb-2"></i><br>
                            Tambah Kolom Custom
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.class-reports.index') }}" class="btn btn-outline-info w-100 h-100 py-3">
                            <i class="fas fa-file-alt fa-2x mb-2"></i><br>
                            Kelola Rapor
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.class-reports.statistics') }}" class="btn btn-outline-warning w-100 h-100 py-3">
                            <i class="fas fa-chart-bar fa-2x mb-2"></i><br>
                            Lihat Statistik
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const gradeChart = document.getElementById('gradeChart').getContext('2d');

    // Sample data - replace with actual data from controller
    const gradeData = {
        labels: ['A', 'B', 'C', 'D', 'E'],
        datasets: [{
            label: 'Jumlah Peserta',
            data: [12, 19, 8, 5, 2],
            backgroundColor: [
                '#28a745',
                '#20c997',
                '#ffc107',
                '#fd7e14',
                '#dc3545'
            ],
            borderWidth: 1
        }]
    };

    new Chart(gradeChart, {
        type: 'bar',
        data: gradeData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
