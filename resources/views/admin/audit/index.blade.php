@extends('layouts.dashboard')

@section('title', 'Audit Trail')

@section('dashboard-content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between flex-wrap align-items-center mb-4">
        <h4 class="fw-bold"><i class="fas fa-history text-orange me-2"></i>Audit Trail</h4>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.audit.index') }}" class="row g-3">
                <div class="col-md-3"><label class="form-label small-caps">Aksi</label><input type="text" name="action" class="form-control" placeholder="Cari aksi..." value="{{ request('action') }}"></div>
                <div class="col-md-2"><label class="form-label small-caps">Tabel</label><select name="table" class="form-select"><option value="">Semua</option>@foreach($tables as $table)<option value="{{ $table }}" {{ request('table') == $table ? 'selected' : '' }}>{{ $table }}</option>@endforeach</select></div>
                <div class="col-md-2"><label class="form-label small-caps">Dari Tanggal</label><input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}"></div>
                <div class="col-md-2"><label class="form-label small-caps">Sampai Tanggal</label><input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}"></div>
                <div class="col-md-3 d-flex align-items-end"><button type="submit" class="btn btn-primary fw-bold shadow-sm">Filter</button><a href="{{ route('admin.audit.index') }}" class="btn btn-secondary ms-2 fw-bold">Reset</a></div>
            </form>
        </div>
    </div>

    <x-data-table title="Log Audit Sistem">
                    <x-slot:thead>
                        <th class="ps-3 py-3 small-caps">Waktu</th>
                        <th class="py-3 small-caps">User</th>
                        <th class="py-3 small-caps">Aksi</th>
                        <th class="py-3 small-caps">Tabel</th>
                        <th class="py-3 small-caps">Record ID</th>
                        <th class="py-3 small-caps">IP Address</th>
                        <th class="py-3 small-caps text-center">Detail</th>
                    </x-slot:thead>
                    @forelse($logs as $log)
                    <tr>
                        <td class="ps-3"><small>{{ $log->created_at->format('d/m/Y H:i:s') }}</small></td>
                        <td>@if($log->user)<div class="d-flex align-items-center"><img src="{{ $log->user->avatar_url }}" class="rounded-circle me-2" width="28" height="28"><div><strong class="small">{{ $log->user->name }}</strong><br><small class="text-muted" style="font-size: 10px;">{{ $log->user->role }}</small></div></div>@else<span class="text-muted small">System</span>@endif</td>
                        <td>@if(str_contains($log->action, 'create'))<span class="badge bg-success" style="font-size: 10px;">Create</span>@elseif(str_contains($log->action, 'update'))<span class="badge bg-info" style="font-size: 10px;">Update</span>@elseif(str_contains($log->action, 'delete'))<span class="badge bg-danger" style="font-size: 10px;">Delete</span>@else<span class="badge bg-secondary" style="font-size: 10px;">{{ Str::limit($log->action, 30) }}</span>@endif</td>
                        <td><code class="small">{{ $log->table_name ?? '-' }}</code></td>
                        <td class="small">{{ $log->record_id ?? '-' }}</td>
                        <td><small class="text-muted">{{ $log->ip_address ?? '-' }}</small></td>
                        <td class="text-center"><button type="button" class="btn btn-sm btn-light border p-1 px-2" onclick="showDetail({{ json_encode($log) }})">Detail</button></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 small text-muted">Belum ada data audit log</td></tr>
                    @endforelse
                </x-data-table>
        <div class="mt-3">{{ $logs->appends(request()->query())->links() }}</div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header bg-gradient-orange text-white"><h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Detail Audit Log</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><div class="modal-body" id="modalDetailContent"><div class="text-center py-4"><div class="spinner-border text-orange" role="status"></div></div></div><div class="modal-footer"><button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Tutup</button></div></div></div></div>
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