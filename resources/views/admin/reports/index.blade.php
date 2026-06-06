@extends('layouts.admin')

@section('title', 'Manajemen Laporan')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-file-alt me-2 text-orange"></i>Manajemen Laporan</h1>
</div>

<!-- Stats Cards -->
<div class="row mb-4 g-3">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center stats-card-body">
                <div class="stats-number text-orange">{{ number_format($stats['total'] ?? 0) }}</div>
                <div class="stats-label">Total Laporan</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center stats-card-body">
                <div class="stats-number text-warning">{{ number_format($stats['pending'] ?? 0) }}</div>
                <div class="stats-label">Pending</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center stats-card-body">
                <div class="stats-number text-info">{{ number_format($stats['in_progress'] ?? 0) }}</div>
                <div class="stats-label">Diproses</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center stats-card-body">
                <div class="stats-number text-success">{{ number_format($stats['completed'] ?? 0) }}</div>
                <div class="stats-label">Selesai</div>
            </div>
        </div>
    </div>
</div>

<!-- Reports Table -->
<div class="card border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="table-light">
                        <th>ID</th>
                        <th>Pelapor</th>
                        <th>Fasilitas</th>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Rating</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr>
                        <td class="align-middle">#{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="align-middle">{{ $report->user->name }}</td>
                        <td class="align-middle">{{ $report->facility->name ?? '-' }}</td>
                        <td class="align-middle">{{ Str::limit($report->title, 30) }}</td>
                        <td class="align-middle">{!! $report->status_badge !!}</td>
                        <td class="align-middle">
                            @if($report->rating)
                                <div class="text-nowrap">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $report->rating)
                                            <i class="fas fa-star text-warning fa-sm"></i>
                                        @else
                                            <i class="far fa-star text-secondary fa-sm"></i>
                                        @endif
                                    @endfor
                                    <span class="small">({{ $report->rating }})</span>
                                </div>
                            @else
                                <span class="text-muted small">Belum dinilai</span>
                            @endif
                        </td>
                        <td class="align-middle">{{ $report->created_at->format('d/m/Y H:i') }}</td>
                        <td class="align-middle">
                            <a href="{{ route('admin.reports.edit', $report->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteReport({{ $report->id }})" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="delete-form-{{ $report->id }}" action="{{ route('admin.reports.destroy', $report->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                            Belum ada laporan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteReport(id) {
    Swal.fire({
        title: 'Hapus Laporan?',
        text: "Laporan akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@endpush

@once
@push('styles')
<style>
    .stats-card-body { padding: 1.1rem .75rem; }
    .stats-number    { font-size: 1.75rem; font-weight: 700; line-height: 1; margin-bottom: .25rem; }
    .stats-label     { font-size: .78rem; font-weight: 500; color: #6c757d; }
 
    @media (max-width: 767.98px) {
        .stats-card-body { padding: .85rem .6rem; }
        .stats-number    { font-size: 1.4rem; }
        .stats-label     { font-size: .7rem; }
    }
</style>
@endpush
@endonce