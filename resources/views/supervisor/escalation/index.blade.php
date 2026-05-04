@extends('layouts.dashboard')

@section('title', 'Eskalasi Laporan')

@section('dashboard-content')
<div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom"><h1 class="h2"><i class="fas fa-arrow-up text-danger me-2"></i>Eskalasi Laporan</h1></div>
<div class="alert alert-warning"><i class="fas fa-info-circle me-2"></i>Laporan yang melewati batas SLA akan muncul di sini. Segera lakukan eskalasi ke manajemen terkait.</div>
<div class="card border-0"><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr class="table-light"><th>No. Laporan</th><th>Judul</th><th>Fasilitas</th><th>Lokasi</th><th>Teknisi</th><th>SLA Deadline</th><th>Keterlambatan</th><th>Aksi</th></tr></thead><tbody>@forelse($escalatedReports as $report)<tr><td>#{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td><td>{{ $report->title }}</td><td>{{ $report->facility->name }}</td><td>{{ $report->location_detail }}</td><td>{{ $report->technicianAssignments->first()?->technician->name ?? '-' }}</td><td>{{ $report->sla_deadline->format('d/m/Y H:i') }}</td><td><span class="text-danger">{{ $report->sla_deadline->diffInHours(now()) }} jam</span></td><td><button class="btn btn-sm btn-danger" onclick="escalate({{ $report->id }})"><i class="fas fa-arrow-up"></i> Eskalasi</button></td></tr>@empty<tr><td colspan="8" class="text-center py-4"><i class="fas fa-check-circle fa-3x text-success mb-2 d-block"></i>Tidak ada laporan yang perlu dieskalasi</td></tr>@endforelse</tbody></table></div></div></div>
@endsection

@push('scripts')
<script>function escalate(id){Swal.fire({title:'Eskalasi Laporan?',text:'Laporan akan di-escalate ke manajemen',icon:'warning',showCancelButton:true,confirmButtonColor:'#dc3545',confirmButtonText:'Ya, Eskalasi'}).then((result)=>{if(result.isConfirmed){Swal.fire('Berhasil!','Laporan telah di-escalate','success');}});}</script>
@endpush