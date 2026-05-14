@extends('layouts.admin')

@section('title', 'Kelola Fasilitas')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-building me-2 text-orange"></i>Kelola Fasilitas</h1>
    <a href="{{ route('admin.facilities.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Tambah Fasilitas
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card border-0">
            <div class="card-body text-center">
                <h3 class="text-primary mb-0">{{ $stats['total'] ?? 0 }}</h3>
                <small class="text-muted">Total Fasilitas</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card border-0">
            <div class="card-body text-center">
                <h3 class="text-info mb-0">{{ $stats['lab'] ?? 0 }}</h3>
                <small class="text-muted">Lab</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card border-0">
            <div class="card-body text-center">
                <h3 class="text-success mb-0">{{ $stats['kelas'] ?? 0 }}</h3>
                <small class="text-muted">Kelas</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card border-0">
            <div class="card-body text-center">
                <h3 class="text-warning mb-0">{{ $stats['perlu_perbaikan'] ?? 0 }}</h3>
                <small class="text-muted">Perlu Perbaikan</small>
            </div>
        </div>
    </div>
</div>

<!-- Facilities Table -->
<div class="card border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="table-light">
                        <th>#</th>
                        <th>Nama Fasilitas</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>SLA (jam)</th>
                        <th>Status Aktif</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facilities as $index => $facility)
                    <tr>
                        <td class="align-middle">{{ $facilities->firstItem() + $index }}</td>
                        <td class="align-middle">
                            <strong>{{ $facility->name }}</strong>
                            @if($facility->description)
                                <br><small class="text-muted">{{ Str::limit($facility->description, 50) }}</small>
                            @endif
                        </td>
                        <td class="align-middle">
                            @if($facility->category == 'Lab')
                                <span class="badge bg-primary">Lab</span>
                            @else
                                <span class="badge bg-success">Kelas</span>
                            @endif
                        </td>
                        <td class="align-middle">{{ $facility->location }}</td>
                        <td class="align-middle">
                            @if($facility->status == 'baik')
                                <span class="badge bg-success">Baik</span>
                            @elseif($facility->status == 'perlu_perbaikan')
                                <span class="badge bg-warning">Perlu Perbaikan</span>
                            @else
                                <span class="badge bg-danger">Rusak</span>
                            @endif
                        </td>
                        <td class="align-middle">{{ $facility->sla_hours }}</td>
                        <td class="align-middle">
                            @if($facility->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="align-middle">
                            <a href="{{ route('admin.facilities.edit', $facility->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteFacility({{ $facility->id }})">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                            <form id="delete-form-{{ $facility->id }}" action="{{ route('admin.facilities.destroy', $facility->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-building fa-3x text-muted mb-3 d-block"></i>
                            <h6 class="text-muted">Belum ada fasilitas</h6>
                            <a href="{{ route('admin.facilities.create') }}" class="btn btn-primary btn-sm mt-2">
                                <i class="fas fa-plus"></i> Tambah Fasilitas
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- PAGINATION MANUAL YANG AMAN -->
        @if($facilities->hasPages())
        <div class="d-flex justify-content-center mt-4">
            <nav>
                <ul class="pagination">
                    {{-- Previous Page Link --}}
                    @if($facilities->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">« Sebelumnya</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $facilities->previousPageUrl() }}" rel="prev">« Sebelumnya</a>
                        </li>
                    @endif

                    {{-- Next Page Link --}}
                    @if($facilities->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $facilities->nextPageUrl() }}" rel="next">Selanjutnya »</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">Selanjutnya »</span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
        @endif
        
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteFacility(id) {
    Swal.fire({
        title: 'Hapus Fasilitas?',
        text: "Fasilitas akan dihapus permanen!",
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