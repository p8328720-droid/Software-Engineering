@extends('layouts.supervisor')

@section('title', 'Dashboard Supervisor')

@section('supervisor-content')
<div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-4 border-bottom"><h1 class="h2"><i class="fas fa-tachometer-alt text-orange me-2"></i>Dashboard Supervisor</h1></div>
<div class="row mb-4"><div class="col-md-3 mb-3"><div class="card stat-card border-0"><div class="card-body"><div class="d-flex justify-content-between"><div><p class="text-muted">Kepatuhan SLA</p><h2 class="text-success">0<small>%</small></h2></div><div><i class="fas fa-check-double fa-3x text-success"></i></div></div><div class="progress mt-2"><div class="progress-bar bg-success" style="width:0%"></div></div></div></div></div>
<div class="col-md-3 mb-3"><div class="card stat-card border-0"><div class="card-body"><div class="d-flex justify-content-between"><div><p class="text-muted">Rata-rata Respon</p><h2 class="text-info">0<small>jam</small></h2></div><div><i class="fas fa-hourglass-half fa-3x text-info"></i></div></div></div></div></div>
<div class="col-md-3 mb-3"><div class="card stat-card border-0"><div class="card-body"><div class="d-flex justify-content-between"><div><p class="text-muted">Rata-rata Penyelesaian</p><h2 class="text-warning">0<small>jam</small></h2></div><div><i class="fas fa-clock fa-3x text-warning"></i></div></div></div></div></div>
<div class="col-md-3 mb-3"><div class="card stat-card border-0"><div class="card-body"><div class="d-flex justify-content-between"><div><p class="text-muted">SLA Violation</p><h2 class="text-danger">{{ $stats['sla_violations'] }}</h2></div><div><i class="fas fa-exclamation-triangle fa-3x text-danger"></i></div></div></div></div></div></div>
<div class="row mb-4"><div class="col-md-6"><div class="card border-0"><div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-chart-line text-orange me-2"></i>Kinerja Teknisi</h5></div><div class="card-body"><canvas id="performanceChart" height="250"></canvas></div></div></div>
<div class="col-md-6"><div class="card border-0"><div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-chart-pie text-orange me-2"></i>Distribusi Laporan</h5></div><div class="card-body"><canvas id="statusChart" height="250"></canvas></div></div></div></div>
<div class="card border-0"><div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-list-alt text-orange me-2"></i>Laporan Terbaru</h5></div><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr class="table-light"><th>ID</th><th>Pelapor</th><th>Fasilitas</th><th>Judul</th><th>Status</th><th>Teknisi</th><th>SLA</th></tr></thead><tbody>@forelse($reports as $report)<tr><td>#{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td><td>{{ $report->user->name }}</td><td>{{ $report->facility->name }}</td><td>{{ Str::limit($report->title, 30) }}</td><td>{!! $report->status_badge !!}</td><td>{{ $report->technicianAssignments->first()?->technician->name ?? '-' }}</td><td>@if($report->sla_deadline < now() && $report->status != 'completed')<span class="text-danger">Terlambat</span>@else<span class="text-success">On Track</span>@endif</td></tr>@empty<tr><td colspan="7" class="text-center py-4">Belum ada laporan</td></tr>@endforelse</tbody></table></div></div></div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('performanceChart'),{type:'line',data:{labels:['Teknisi 1','Teknisi 2','Teknisi 3','Teknisi 4'],datasets:[{label:'Tugas Selesai',data:[0,0,0,0],borderColor:'#FF6B35',backgroundColor:'rgba(255,107,53,0.1)',fill:true}]}});
new Chart(document.getElementById('statusChart'),{type:'doughnut',data:{labels:['Pending','Diproses','Selesai'],datasets:[{data:[0,0,0],backgroundColor:['#6c757d','#ffc107','#28a745']}]}});
</script>
@endpush