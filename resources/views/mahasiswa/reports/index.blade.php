@extends('layouts.mahasiswa')

@section('title', 'Daftar Laporan')

@section('mahasiswa-content')
<div class="card border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center"><h5 class="mb-0"><i class="fas fa-list text-orange me-2"></i>Daftar Laporan Saya</h5><a href="{{ route('mahasiswa.reports.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Buat Laporan</a></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr class="table-light"><th>No. Laporan</th><th>Judul</th><th>Fasilitas</th><th>Tanggal</th><th>Status</th><th>SLA</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr><td>#{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td><td>{{ $report->title }}</td><td>{{ $report->facility->name }}</td><td>{{ $report->created_at->format('d/m/Y') }}</td><td>{!! $report->status_badge !!}</td><td>@if($report->sla_deadline < now() && $report->status != 'completed')<span class="text-danger">Terlambat</span>@else<span class="text-success">On Track</span>@endif</td><td><a href="{{ route('mahasiswa.reports.show', $report) }}" class="btn btn-sm btn-outline-primary">Detail</a></td></tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-5"><i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i><h6 class="text-muted">Belum ada laporan</h6><a href="{{ route('mahasiswa.reports.create') }}" class="btn btn-primary btn-sm mt-2">Buat Laporan</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $reports->links() }}</div>
    </div>
</div>
@endsection