@extends('layouts.dashboard')

@section('title', 'Audit Trail')

@section('dashboard-content')
<div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-history me-2 text-orange"></i>Audit Trail</h1>
</div>

<div class="card border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.audit.index') }}" class="row g-3">
            <div class="col-md-3"><label class="form-label">Aksi</label><input type="text" name="action" class="form-control" placeholder="Cari aksi..." value="{{ request('action') }}"></div>
            <div class="col-md-2"><label class="form-label">Tabel</label><select name="table" class="form-select"><option value="">Semua</option>@foreach($tables as $table)<option value="{{ $table }}" {{ request('table') == $table ? 'selected' : '' }}>{{ $table }}</option>@endforeach</select></div>
            <div class="col-md-2"><label class="form-label">Dari Tanggal</label><input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}"></div>
            <div class="col-md-2"><label class="form-label">Sampai Tanggal</label><input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}"></div>
            <div class="col-md-3 d-flex align-items-end"><button type="submit" class="btn btn-primary me-2"><i class="fas fa-search me-1"></i> Filter</button><a href="{{ route('admin.audit.index') }}" class="btn btn-secondary"><i class="fas fa-undo me-1"></i> Reset</a></div>
        </form>
    </div>
</div>

<div class="card border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr class="table-light"><th>Waktu</th><th>User</th><th>Aksi</th><th>Tabel</th><th>Record ID</th><th>IP Address</th><th>Detail</th> </tr></thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td><small>{{ $log->created_at->format('d/m/Y H:i:s') }}</small></td>
                        <td>@if($log->user)<div class="d-flex align-items-center"><img src="{{ $log->user->avatar_url }}" class="rounded-circle me-2" width="28" height="28"><div><strong>{{ $log->user->name }}</strong><br><small class="text-muted">{{ $log->user->role }}</small></div></div>@else<span class="text-muted">System</span>@endif</td>
                        <td>@if(str_contains($log->action, 'create'))<span class="badge bg-success">Create</span>@elseif(str_contains($log->action, 'update'))<span class="badge bg-info">Update</span>@elseif(str_contains($log->action, 'delete'))<span class="badge bg-danger">Delete</span>@else<span class="badge bg-secondary">{{ Str::limit($log->action, 30) }}</span>@endif</td>
                        <td><code>{{ $log->table_name ?? '-' }}</code></td>
                        <td>{{ $log->record_id ?? '-' }}</td>
                        <td><small>{{ $log->ip_address ?? '-' }}</small></td>
                        <td><button type="button" class="btn btn-sm btn-outline-primary" onclick="showDetail({{ json_encode($log) }})"><i class="fas fa-eye"></i> Detail</button></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-5">Belum ada data audit log</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $logs->appends(request()->query())->links() }}</div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header bg-gradient-orange text-white"><h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Detail Audit Log</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><div class="modal-body" id="modalDetailContent"><div class="text-center py-4"><div class="spinner-border text-orange" role="status"></div></div></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div></div></div></div>
@endsection

@push('styles')
<style>.bg-gradient-orange { background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 100%); }</style>
@endpush

@push('scripts')
<script>
function showDetail(log) {
    let html = `<div class="mb-3"><strong>Waktu:</strong> ${new Date(log.created_at).toLocaleString('id-ID')}</div><div class="mb-3"><strong>User:</strong> ${log.user ? log.user.name : 'System'}</div><div class="mb-3"><strong>Aksi:</strong> ${log.action}</div><div class="mb-3"><strong>Tabel:</strong> ${log.table_name || '-'}</div><div class="mb-3"><strong>Record ID:</strong> ${log.record_id || '-'}</div><div class="mb-3"><strong>IP Address:</strong> ${log.ip_address || '-'}</div>`;
    if (log.old_values) html += `<div class="mb-3"><strong>Old Values:</strong><pre class="bg-light p-2 rounded small">${JSON.stringify(log.old_values, null, 2)}</pre></div>`;
    if (log.new_values) html += `<div class="mb-3"><strong>New Values:</strong><pre class="bg-light p-2 rounded small">${JSON.stringify(log.new_values, null, 2)}</pre></div>`;
    document.getElementById('modalDetailContent').innerHTML = html;
    new bootstrap.Modal(document.getElementById('detailModal')).show();
}
</script>
@endpush