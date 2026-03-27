@extends('layouts.teknisi')

@section('title', 'Dashboard Teknisi')

@section('teknisi-content')
<div class="row mb-4">
    <div class="col-12"><div class="card bg-gradient-orange text-white border-0"><div class="card-body"><div class="d-flex justify-content-between"><div><h3 class="mb-0">Selamat Datang, {{ Auth::user()->name }}!</h3><p class="mb-0 mt-2">Panel Teknisi SiRUKA</p></div><div><i class="fas fa-wrench fa-4x opacity-50"></i></div></div></div></div></div>
</div>
<div class="row mb-4">
    <div class="col-md-3 mb-3"><div class="card stat-card border-0"><div class="card-body"><div class="d-flex justify-content-between"><div><p class="text-muted">Tugas Aktif</p><h2 class="text-warning">{{ $stats['active_tasks'] }}</h2></div><div><i class="fas fa-tasks fa-3x text-warning"></i></div></div></div></div></div>
    <div class="col-md-3 mb-3"><div class="card stat-card border-0"><div class="card-body"><div class="d-flex justify-content-between"><div><p class="text-muted">Tugas Selesai</p><h2 class="text-success">{{ $stats['completed_tasks'] }}</h2></div><div><i class="fas fa-check-circle fa-3x text-success"></i></div></div></div></div></div>
    <div class="col-md-3 mb-3"><div class="card stat-card border-0"><div class="card-body"><div class="d-flex justify-content-between"><div><p class="text-muted">Rata-rata Waktu</p><h2 class="text-info">0</h2><small>jam</small></div><div><i class="fas fa-hourglass-half fa-3x text-info"></i></div></div></div></div></div>
    <div class="col-md-3 mb-3"><div class="card stat-card border-0"><div class="card-body"><div class="d-flex justify-content-between"><div><p class="text-muted">Rating</p><h2 class="text-orange">0.0</h2><small>/5</small></div><div><i class="fas fa-star fa-3x text-orange"></i></div></div></div></div></div>
</div>
<div class="card border-0"><div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-tasks text-orange me-2"></i>Tugas Aktif</h5></div><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr class="table-light"><th>No. Laporan</th><th>Judul</th><th>Lokasi</th><th>Urgensi</th><th>SLA Deadline</th><th>Aksi</th></tr></thead><tbody>@forelse($active_tasks as $task)<tr><td>#{{ str_pad($task->report_id, 5, '0', STR_PAD_LEFT) }}</td><td>{{ $task->report->title }}</td><td>{{ $task->report->location_detail }}</td><td>{!! $task->report->urgency_badge !!}</td><td>{{ $task->report->sla_deadline->format('d/m/Y H:i') }}</td><td><a href="{{ route('teknisi.tasks.show', $task) }}" class="btn btn-sm btn-primary">Kerjakan</a></td></tr>@empty<tr><td colspan="6" class="text-center py-4">Tidak ada tugas aktif</td></tr>@endforelse</tbody></table></div></div></div>
@endsection

@push('styles')
<style>.bg-gradient-orange{background:linear-gradient(135deg,#FF6B35 0%,#FF8C42 100%)}.stat-card{transition:transform .3s ease;border-radius:15px}.stat-card:hover{transform:translateY(-5px)}.text-orange{color:#FF6B35}</style>
@endpush