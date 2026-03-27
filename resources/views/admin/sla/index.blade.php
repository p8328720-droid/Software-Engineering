@extends('layouts.admin')

@section('title', 'Aturan SLA')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-clock me-2 text-orange"></i>Aturan Service Level Agreement (SLA)</h1>
</div>

<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i> SLA menentukan batas waktu respons dan penyelesaian laporan berdasarkan kategori fasilitas dan tingkat urgensi.
</div>

<div class="card border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr class="table-light"><th>Kategori Fasilitas</th><th>Tingkat Urgensi</th><th>Response Time (jam)</th><th>Resolution Time (jam)</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($slaRules as $sla)
                    <tr>
                        <td><strong>{{ $sla->facility_category }}</strong></td>
                        <td>@if($sla->urgency == 'low')<span class="badge bg-success">Rendah</span>@elseif($sla->urgency == 'medium')<span class="badge bg-warning">Sedang</span>@else<span class="badge bg-danger">Tinggi</span>@endif</td>
                        <td>{{ $sla->response_hours }} jam</td>
                        <td>{{ $sla->resolution_hours }} jam</td>
                        <td>@if($sla->is_active)<span class="badge bg-success">Aktif</span>@else<span class="badge bg-secondary">Nonaktif</span>@endif</td>
                        <td><a href="{{ route('admin.sla.edit', $sla->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i> Edit</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4">Belum ada aturan SLA</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection