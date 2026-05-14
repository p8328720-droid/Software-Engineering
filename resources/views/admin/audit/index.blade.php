@extends('layouts.admin')

@section('title', 'Audit Trail')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-history me-2 text-orange"></i>Audit Trail</h1>
</div>

<!-- Filter Form -->
<div class="card border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.audit') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Aksi</label>
                <input type="text" name="action" class="form-control" placeholder="Cari aksi..." value="{{ request('action') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Tabel</label>
                <select name="table" class="form-select">
                    <option value="">Semua</option>
                    <option value="reports" {{ request('table') == 'reports' ? 'selected' : '' }}>reports</option>
                    <option value="users" {{ request('table') == 'users' ? 'selected' : '' }}>users</option>
                    <option value="facilities" {{ request('table') == 'facilities' ? 'selected' : '' }}>facilities</option>
                    <option value="comments" {{ request('table') == 'comments' ? 'selected' : '' }}>comments</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
                <a href="{{ route('admin.audit') }}" class="btn btn-secondary">
                    <i class="fas fa-undo me-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Audit Logs Table -->
<div class="card border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="table-light">
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Aksi</th>
                        <th>Tabel</th>
                        <th>Record ID</th>
                        <th>IP Address</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td><small>{{ $log->created_at->format('d/m/Y H:i:s') }}</small></td>
                        <td>
                            @if($log->user)
                                <strong>{{ $log->user->name }}</strong>
                                <br><small class="text-muted">{{ $log->user->role }}</small>
                            @else
                                <span class="text-muted">System</span>
                            @endif
                        </td>
                        <td>
                            @if(str_contains($log->action, 'create'))
                                <span class="badge bg-success">Create</span>
                            @elseif(str_contains($log->action, 'update'))
                                <span class="badge bg-info">Update</span>
                            @elseif(str_contains($log->action, 'delete'))
                                <span class="badge bg-danger">Delete</span>
                            @else
                                <span class="badge bg-secondary">{{ Str::limit($log->action, 30) }}</span>
                            @endif
                        </td>
                        <td><code>{{ $log->table_name ?? $log->auditable_type ?? '-' }}</code></td>
                        <td>{{ $log->record_id ?? $log->auditable_id ?? '-' }}</td>
                        <td><small>{{ $log->ip_address ?? '-' }}</small></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="showDetail({{ $log->id }})">
                                <i class="fas fa-eye"></i> Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-history fa-3x text-muted mb-3 d-block"></i>
                            <h6 class="text-muted">Belum ada data audit log</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $logs->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-orange text-white">
                <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Detail Audit Log</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalDetailContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-orange" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showDetail(id) {
    // Tampilkan loading
    document.getElementById('modalDetailContent').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-orange" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memuat data...</p>
        </div>
    `;
    
    // Buka modal
    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    modal.show();
    
    // Fetch data via AJAX
    fetch(`/admin/audit/${id}/detail`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let html = `
                <div class="mb-3">
                    <strong>Waktu:</strong> ${data.log.created_at}
                </div>
                <div class="mb-3">
                    <strong>User:</strong> ${data.log.user_name || 'System'}
                </div>
                <div class="mb-3">
                    <strong>Aksi:</strong> ${data.log.action}
                </div>
                <div class="mb-3">
                    <strong>Tabel:</strong> ${data.log.table_name || data.log.auditable_type || '-'}
                </div>
                <div class="mb-3">
                    <strong>Record ID:</strong> ${data.log.record_id || data.log.auditable_id || '-'}
                </div>
                <div class="mb-3">
                    <strong>IP Address:</strong> ${data.log.ip_address || '-'}
                </div>
            `;
            
            if (data.log.old_values && Object.keys(data.log.old_values).length > 0) {
                html += `
                    <div class="mb-3">
                        <strong>Old Values:</strong>
                        <pre class="bg-light p-2 rounded small" style="max-height: 200px; overflow: auto;">${JSON.stringify(data.log.old_values, null, 2)}</pre>
                    </div>
                `;
            }
            
            if (data.log.new_values && Object.keys(data.log.new_values).length > 0) {
                html += `
                    <div class="mb-3">
                        <strong>New Values:</strong>
                        <pre class="bg-light p-2 rounded small" style="max-height: 200px; overflow: auto;">${JSON.stringify(data.log.new_values, null, 2)}</pre>
                    </div>
                `;
            }
            
            document.getElementById('modalDetailContent').innerHTML = html;
        } else {
            document.getElementById('modalDetailContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Gagal memuat data: ${data.message}
                </div>
            `;
        }
    })
    .catch(error => {
        document.getElementById('modalDetailContent').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                Terjadi kesalahan saat memuat data.
            </div>
        `;
    });
}
</script>
@endpush