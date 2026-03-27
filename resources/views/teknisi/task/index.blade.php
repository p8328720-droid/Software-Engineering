@extends('layouts.teknisi')

@section('title', 'Daftar Tugas')

@section('teknisi-content')
<div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom"><h1 class="h2"><i class="fas fa-tasks text-orange me-2"></i>Daftar Tugas</h1></div>
<ul class="nav nav-tabs mb-4"><li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#active">Tugas Aktif <span class="badge bg-danger ms-1">{{ $activeTasks->count() }}</span></button></li><li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#completed">Selesai</button></li></ul>
<div class="tab-content"><div class="tab-pane fade show active" id="active"><div class="card border-0"><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr class="table-light"><th>No. Laporan</th><th>Judul</th><th>Lokasi</th><th>Urgensi</th><th>SLA Deadline</th><th>Aksi</th></tr></thead><tbody>@forelse($activeTasks as $task)<tr><td>#{{ str_pad($task->report_id, 5, '0', STR_PAD_LEFT) }}</td><td>{{ $task->report->title }}</td><td>{{ $task->report->location_detail }}</td><td>{!! $task->report->urgency_badge !!}</td><td>{{ $task->report->sla_deadline->format('d/m/Y H:i') }}</td><td><a href="{{ route('teknisi.tasks.show', $task) }}" class="btn btn-sm btn-primary">Kerjakan</a></td></tr>@empty<tr><td colspan="6" class="text-center py-4">Tidak ada tugas aktif</td></tr>@endforelse</tbody></table></div></div></div></div>
<div class="tab-pane fade" id="completed"><div class="card border-0"><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr class="table-light"><th>No. Laporan</th><th>Judul</th><th>Tanggal Selesai</th><th>Aksi</th></tr></thead><tbody>@forelse($completedTasks as $task)<tr><td>#{{ str_pad($task->report_id, 5, '0', STR_PAD_LEFT) }}</td><td>{{ $task->report->title }}</td><td>{{ $task->completed_at ? $task->completed_at->format('d/m/Y H:i') : '-' }}</td><td><a href="{{ route('teknisi.tasks.show', $task) }}" class="btn btn-sm btn-outline-primary">Detail</a></td></tr>@empty<tr><td colspan="4" class="text-center py-4">Belum ada tugas selesai</td></tr>@endforelse</tbody></table></div></div></div></div></div>
@endsection

@push('styles')
<style>.nav-tabs .nav-link{color:#6c757d;border:none;padding:10px 20px}.nav-tabs .nav-link:hover{color:#FF6B35;border-bottom:2px solid #FF6B35}.nav-tabs .nav-link.active{color:#FF6B35;border-bottom:2px solid #FF6B35;background:transparent}</style>
@endpush