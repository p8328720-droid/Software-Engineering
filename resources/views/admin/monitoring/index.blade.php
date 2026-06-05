@extends('layouts.dashboard')

@section('title', 'Monitoring Kinerja')

@section('dashboard-content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between flex-wrap align-items-center mb-4">
        <h4 class="fw-bold">Monitoring Kinerja Teknisi</h4>
    </div>
    <x-data-table title="Kinerja Teknisi">
                <x-slot:thead>
                    <th class="ps-3 py-3 small-caps">No</th>
                    <th class="py-3 small-caps">Nama Teknisi</th>
                    <th class="py-3 small-caps">Total Tugas</th>
                    <th class="py-3 small-caps">Tugas Selesai</th>
                    <th class="py-3 small-caps">Completion Rate</th>
                    <th class="py-3 small-caps text-center">Aksi</th>
                </x-slot:thead>
                @forelse($performance as $index => $p)
                    <tr>
                        <td class="ps-3">{{ $index+1 }}</td>
                        <td><div class="d-flex align-items-center"><img src="{{ $p['technician']->avatar_url }}" class="rounded-circle me-2" width="32">{{ $p['technician']->name }}</div></td>
                        <td>{{ $p['total_tasks'] }}</td>
                        <td>{{ $p['completed_tasks'] }}</td>
                        <td><div class="progress" style="height:8px"><div class="progress-bar bg-{{ $p['completion_rate'] >= 80 ? 'success' : ($p['completion_rate'] >= 50 ? 'warning' : 'danger') }}" style="width:{{ $p['completion_rate'] }}%"></div></div><small>{{ round($p['completion_rate']) }}%</small></td>
                        <td class="text-center"><a href="{{ route('admin.monitoring.show', $p['technician']->id) }}" class="btn btn-sm btn-light border p-1 px-2">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 small text-muted">Belum ada data teknisi</td></tr>
                @endforelse
            </x-data-table>
</div>
@endsection

@push('styles')
<style>
    .small-caps { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 800; color: #6c757d; }
</style>
@endpush
