@extends('adminprogram.layouts.app')


@section('title', 'Rapor Kelas')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-file-alt text-primary"></i>
        Rapor Kelas
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.class-reports.statistics') }}" class="btn btn-info me-2">
            <i class="fas fa-chart-bar me-1"></i> Statistik
        </a>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-shadow">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.class-reports.index') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="kelas_id" class="form-label">Kelas</label>
                            <select name="kelas_id" id="kelas_id" class="form-select">
                                <option value="">Semua Kelas</option>
                                @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="is_published" class="form-label">Status Publikasi</label>
                            <select name="is_published" id="is_published" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="1" {{ request('is_published') === '1' ? 'selected' : '' }}>Published</option>
                                <option value="0" {{ request('is_published') === '0' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="is_passed" class="form-label">Status Kelulusan</label>
                            <select name="is_passed" id="is_passed" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="1" {{ request('is_passed') === '1' ? 'selected' : '' }}>Lulus</option>
                                <option value="0" {{ request('is_passed') === '0' ? 'selected' : '' }}>Tidak Lulus</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
@if(isset($kelas) && $kelas->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-shadow">
            <div class="card-header bg-warning">
                <h5 class="card-title mb-0 text-white">
                    <i class="fas fa-bolt me-2"></i>Generate Rapor Massal
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($kelas as $k)
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6>{{ $k->nama_kelas }}</h6>
                                <a href="{{ route('admin.class-reports.generate', $k->id) }}"
                                   class="btn btn-sm btn-primary"
                                   onclick="return confirm('Generate rapor untuk semua peserta di kelas {{ $k->nama_kelas }}?')">
                                    <i class="fas fa-sync me-1"></i> Generate
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

<!-- Reports Table -->
<div class="row">
    <div class="col-12">
        <div class="card card-shadow">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Daftar Rapor</h5>
                <div>
                    <button type="button" class="btn btn-sm btn-success me-1" id="bulkPublishBtn">
                        <i class="fas fa-check me-1"></i> Publish Selected
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" id="bulkUpdateStatusBtn">
                        <i class="fas fa-edit me-1"></i> Update Status
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($classReports->count() > 0)
                    <form id="bulkActionForm" method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="50">
                                            <input type="checkbox" id="selectAll">
                                        </th>
                                        <th>Peserta</th>
                                        <th>Kelas</th>
                                        <th>Nilai Akhir</th>
                                        <th>Grade</th>
                                        <th>Status</th>
                                        <th>Publikasi</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($classReports as $report)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="report_ids[]" value="{{ $report->id }}" class="report-checkbox">
                                        </td>
                                        <td>
                                            <strong>{{ $report->user->name }}</strong>
                                            <br><small class="text-muted">{{ $report->user->email }}</small>
                                        </td>
                                        <td>{{ $report->kelas->nama_kelas }}</td>
                                        <td>
                                            <strong>{{ number_format($report->final_score, 2) }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-grade-{{ $report->letter_grade }}">
                                                {{ $report->letter_grade }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($report->is_passed)
                                                <span class="badge bg-success">Lulus</span>
                                            @else
                                                <span class="badge bg-danger">Tidak Lulus</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($report->is_published)
                                                <span class="badge bg-success">Published</span>
                                            @else
                                                <span class="badge bg-warning">Draft</span>
                                            @endif
                                        </td>
                                        <td>{{ $report->generated_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.class-reports.show', $report->id) }}"
                                                   class="btn btn-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.class-reports.edit', $report->id) }}"
                                                   class="btn btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.class-reports.print', $report->id) }}"
                                                   class="btn btn-secondary" title="Print" target="_blank">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                Menampilkan {{ $classReports->firstItem() }} - {{ $classReports->lastItem() }} dari {{ $classReports->total() }} rapor
                            </div>
                            {{ $classReports->links() }}
                        </div>
                    </form>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Belum ada rapor</h4>
                        <p class="text-muted">Generate rapor untuk melihat data di sini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Modals -->
<div class="modal fade" id="bulkPublishModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Publish Rapor Terpilih</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Anda akan mempublish <span id="selectedCountPublish">0</span> rapor. Tindakan ini tidak dapat dibatalkan.</p>
                <p class="text-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Certificate dan badge akan otomatis tergenerate untuk peserta yang lulus.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.class-reports.bulk-publish') }}" method="POST" id="bulkPublishForm">
                    @csrf
                    <button type="submit" class="btn btn-success">Ya, Publish</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="bulkUpdateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status Kelulusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Update status kelulusan untuk <span id="selectedCountStatus">0</span> rapor.</p>
                <div class="mb-3">
                    <label class="form-label">Status Kelulusan:</label>
                    <select name="is_passed" class="form-select" id="bulkStatusSelect">
                        <option value="1">Lulus</option>
                        <option value="0">Tidak Lulus</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.class-reports.bulk-update-status') }}" method="POST" id="bulkUpdateStatusForm">
                    @csrf
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const reportCheckboxes = document.querySelectorAll('.report-checkbox');
    const bulkPublishBtn = document.getElementById('bulkPublishBtn');
    const bulkUpdateStatusBtn = document.getElementById('bulkUpdateStatusBtn');
    const bulkPublishForm = document.getElementById('bulkPublishForm');
    const bulkUpdateStatusForm = document.getElementById('bulkUpdateStatusForm');

    // Select all functionality
    selectAll.addEventListener('change', function() {
        reportCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    // Update selected count
    function updateSelectedCount() {
        const selectedCount = document.querySelectorAll('.report-checkbox:checked').length;
        document.getElementById('selectedCountPublish').textContent = selectedCount;
        document.getElementById('selectedCountStatus').textContent = selectedCount;

        // Enable/disable bulk action buttons
        bulkPublishBtn.disabled = selectedCount === 0;
        bulkUpdateStatusBtn.disabled = selectedCount === 0;
    }

    reportCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // Bulk publish
    bulkPublishBtn.addEventListener('click', function() {
        if (document.querySelectorAll('.report-checkbox:checked').length === 0) {
            alert('Pilih minimal satu rapor untuk dipublish.');
            return;
        }

        // Update form with selected checkboxes
        const selectedIds = Array.from(document.querySelectorAll('.report-checkbox:checked'))
            .map(cb => cb.value);

        const form = bulkPublishForm;
        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'report_ids[]';
            input.value = id;
            form.appendChild(input);
        });

        new bootstrap.Modal(document.getElementById('bulkPublishModal')).show();
    });

    // Bulk update status
    bulkUpdateStatusBtn.addEventListener('click', function() {
        if (document.querySelectorAll('.report-checkbox:checked').length === 0) {
            alert('Pilih minimal satu rapor untuk diupdate status.');
            return;
        }

        // Update form with selected checkboxes
        const selectedIds = Array.from(document.querySelectorAll('.report-checkbox:checked'))
            .map(cb => cb.value);

        const form = bulkUpdateStatusForm;
        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'report_ids[]';
            input.value = id;
            form.appendChild(input);
        });

        // Add status value
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'is_passed';
        statusInput.value = document.getElementById('bulkStatusSelect').value;
        form.appendChild(statusInput);

        new bootstrap.Modal(document.getElementById('bulkUpdateStatusModal')).show();
    });

    // Initial update
    updateSelectedCount();
});
</script>
@endpush
