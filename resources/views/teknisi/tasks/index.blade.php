@extends('layouts.teknisi')

@section('title', 'Daftar Tugas')

@section('teknisi-content')
<div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom"><h1 class="h2"><i class="fas fa-tasks text-orange me-2"></i>Daftar Tugas</h1></div>
<div class="card border-0"><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr class="table-light"><th>No. Laporan</th><th>Judul</th><th>Fasilitas</th><th>Lokasi</th><th>Urgensi</th><th>Status</th><th>Aksi</th></tr></thead><tbody>@forelse($activeTasks as $task)<tr><td>#{{ str_pad($task->id, 5, '0', STR_PAD_LEFT) }}</td><td>{{ $task->title }}</td><td>{{ $task->facility->name ?? '-' }}</td><td>{{ $task->location_detail }}</td><td>{!! $task->urgency_badge !!}</td><td>{!! $task->status_badge !!}</td><td><a href="{{ route('teknisi.tasks.show', $task) }}" class="btn btn-sm btn-primary">Detail</a></td></tr>@empty<tr><td colspan="7" class="text-center py-4"><i class="fas fa-check-circle fa-2x text-success mb-2 d-block"></i>Tidak ada tugas</td></tr>@endforelse</tbody></table></div></div></div>
@endsection