@extends('layouts.dashboard')

@section('title', 'Aturan SLA')

@section('dashboard-content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between flex-wrap align-items-center mb-4">
        <h4 class="fw-bold"><i class="fas fa-clock text-orange me-2"></i>Aturan Service Level Agreement (SLA)</h4>
    </div>

    <div class="alert alert-info border-0 shadow-sm mb-4">
        <i class="fas fa-info-circle me-2"></i> SLA menentukan batas waktu respons dan penyelesaian laporan berdasarkan kategori fasilitas dan tingkat urgensi.
    </div>

    <x-data-table title="Aturan Service Level Agreement (SLA)">
                    <x-slot:thead>
                        <th class="ps-3 py-3 small-caps">Kategori Fasilitas</th>
                        <th class="py-3 small-caps">Tingkat Urgensi</th>
                        <th class="py-3 small-caps">Response Time (jam)</th>
                        <th class="py-3 small-caps">Resolution Time (jam)</th>
                        <th class="py-3 small-caps">Status</th>
                        <th class="py-3 small-caps text-center">Aksi</th>
                    </x-slot:thead>
                    @forelse($slaRules as $sla)
                    <tr>
                        <td class="ps-3 small fw-bold">{{ $sla->facility_category }}</td>
                        <td>
                            @if($sla->urgency == 'low')<span class="badge bg-success" style="font-size: 10px;">RENDAH</span>
                            @elseif($sla->urgency == 'medium')<span class="badge bg-warning" style="font-size: 10px;">SEDANG</span>
                            @else<span class="badge bg-danger" style="font-size: 10px;">TINGGI</span>@endif
                        </td>
                        <td class="small">{{ $sla->response_hours }} jam</td>
                        <td class="small">{{ $sla->resolution_hours }} jam</td>
                        <td>@if($sla->is_active)<span class="badge bg-success" style="font-size: 10px;">AKTIF</span>@else<span class="badge bg-secondary" style="font-size: 10px;">NONAKTIF</span>@endif</td>
                        <td class="text-center">
                            <a href="{{ route('admin.sla.edit', $sla->id) }}" class="btn btn-sm btn-light border p-1 px-2">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 small text-muted">Belum ada aturan SLA</td></tr>
                    @endforelse
                </x-data-table>
</div>
@endsection