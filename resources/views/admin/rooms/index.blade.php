@extends('layouts.dashboard')

@section('title', 'Kelola Ruangan')

@section('dashboard-content')
    <div class="container-fluid px-0">
        {{-- BANNER HEADER --}}
        <div class="card border-0 shadow-sm mb-4 bg-gradient-orange text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-1"><i class="fas fa-door-open me-2"></i>Kelola Ruangan</h3>
                        <p class="mb-0 opacity-75">Manajemen lokasi dan identitas ruangan kampus.</p>
                    </div>
                    <a href="{{ route('admin.rooms.create') }}"
                        class="btn btn-white text-orange fw-bold shadow-sm d-none d-md-inline-block">
                        <i class="fas fa-plus-circle me-1"></i> Tambah Ruangan
                    </a>
                </div>
            </div>
        </div>

        {{-- STAT TILES --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="p-3 border rounded bg-white shadow-sm h-100 border-start border-4 border-orange">
                    <small class="text-muted d-block small-caps mb-1">Total Ruangan</small>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="fw-bold mb-0 text-orange">{{ $stats['total'] }}</h3>
                        <i class="fas fa-building opacity-25 fs-4"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 border rounded bg-white shadow-sm h-100 border-start border-4 border-danger">
                    <small class="text-muted d-block small-caps mb-1">Ruangan Bermasalah</small>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="fw-bold mb-0 text-danger">{{ $stats['room_with_reports'] }}</h3>
                        <i class="fas fa-exclamation-triangle opacity-25 fs-4"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 border rounded bg-white shadow-sm h-100 border-start border-4 border-success">
                    <small class="text-muted d-block small-caps mb-1">Status Clear</small>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="fw-bold mb-0 text-success">{{ $stats['total'] - $stats['room_with_reports'] }}</h3>
                        <i class="fas fa-check-double opacity-25 fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- DATA TABLE --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold small-caps"><i class="fas fa-list text-orange me-2"></i>Daftar Inventaris Ruangan
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3 py-3 small-caps">ID</th>
                                <th class="py-3 small-caps">Nama Ruangan</th>
                                <th class="py-3 small-caps">Lokasi / Lantai</th>
                                <th class="py-3 small-caps text-center">Status</th>
                                <th class="py-3 small-caps text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rooms as $room)
                                <tr>
                                    <td class="ps-3 fw-bold text-muted">#{{ str_pad($room->id, 3, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        <div class="fw-bold small text-dark">{{ $room->name }}</div>
                                        <small class="text-muted"
                                            style="font-size: 11px;">{{ Str::limit($room->description, 50) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-muted border fw-normal" style="font-size: 10px;">
                                            <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                                            {{ $room->location ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($room->reports_count > 0)
                                            <span class="badge bg-danger-light text-danger p-1 px-2" style="font-size: 10px;">
                                                {{ $room->reports_count }} LAPORAN AKTIF
                                            </span>
                                        @else
                                            <span class="badge bg-success-light text-success p-1 px-2"
                                                style="font-size: 10px;">NORMAL</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('admin.rooms.edit', $room->id) }}"
                                                class="btn btn-sm btn-light border p-1 px-2 shadow-sm">
                                                <i class="fas fa-edit small text-muted"></i>
                                            </a>
                                            <button class="btn btn-sm btn-light border p-1 px-2 text-danger shadow-sm"
                                                onclick="confirmDelete({{ $room->id }})">
                                                <i class="fas fa-trash small"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $room->id }}"
                                            action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted small">Belum ada data ruangan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 py-3">
                {{ $rooms->links() }}
            </div>
        </div>
    </div>
@endsection