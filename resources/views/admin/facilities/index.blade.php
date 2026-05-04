@extends('layouts.dashboard')

@section('title', 'Kelola Fasilitas')

@section('dashboard-content')
<div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-building me-2 text-orange"></i>Kelola Fasilitas</h1>
    <a href="{{ route('admin.facilities.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Tambah Fasilitas</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert"><i class="fas fa-check-circle me-2"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row mb-4">
    <div class="col-md-3 mb-3"><div class="card stat-card border-0"><div class="card-body text-center"><h3 class="text-orange mb-0">{{ $stats['total'] }}</h3><small>Total Fasilitas</small></div></div></div>
    <div class="col-md-3 mb-3"><div class="card stat-card border-0"><div class="card-body text-center"><h3 class="text-success mb-0">{{ $stats['baik'] }}</h3><small>Baik</small></div></div></div>
    <div class="col-md-3 mb-3"><div class="card stat-card border-0"><div class="card-body text-center"><h3 class="text-warning mb-0">{{ $stats['perlu_perbaikan'] }}</h3><small>Perlu Perbaikan</small></div></div></div>
    <div class="col-md-3 mb-3"><div class="card stat-card border-0"><div class="card-body text-center"><h3 class="text-danger mb-0">{{ $stats['rusak'] }}</h3><small>Rusak</small></div></div></div>
</div>

<div class="card border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr class="table-light"><th>#</th><th>Nama Fasilitas</th><th>Kategori</th><th>Lokasi</th><th>Status</th><th>SLA (jam)</th><th>Status Aktif</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($facilities as $index => $facility)
                    <tr>
                        <td>{{ $facilities->firstItem() + $index }}</td>
                        <td><strong>{{ $facility->name }}</strong>@if($facility->description)<br><small class="text-muted">{{ Str::limit($facility->description, 50) }}</small>@endif</td>
                        <td>{{ $facility->category }}</td>
                        <td>{{ $facility->location }}</td>
                        <td>@if($facility->status == 'baik')<span class="badge bg-success">Baik</span>@elseif($facility->status == 'perlu_perbaikan')<span class="badge bg-warning">Perlu Perbaikan</span>@else<span class="badge bg-danger">Rusak</span>@endif</td>
                        <td>{{ $facility->sla_hours }} jam</td>
                        <td>@if($facility->is_active)<span class="badge bg-success">Aktif</span>@else<span class="badge bg-secondary">Nonaktif</span>@endif</td>
                        <td>
                            <a href="{{ route('admin.facilities.edit', $facility->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteFacility({{ $facility->id }})"><i class="fas fa-trash"></i></button>
                            <form id="delete-form-{{ $facility->id }}" action="{{ route('admin.facilities.destroy', $facility->id) }}" method="POST" style="display: none;">@csrf @method('DELETE')</form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-5">Belum ada fasilitas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $facilities->links() }}</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteFacility(id) {
    Swal.fire({ title: 'Hapus Fasilitas?', text: "Fasilitas akan dihapus permanen!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal' }).then((result) => { if (result.isConfirmed) document.getElementById('delete-form-' + id).submit(); });
}
</script>
@endpush