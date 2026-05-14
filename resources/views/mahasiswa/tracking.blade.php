@extends('layouts.mahasiswa')

@section('title', 'Tracking Laporan')

@section('mahasiswa-content')
<div class="card border-0">
    <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-search text-orange me-2"></i>Tracking Laporan</h5></div>
    <div class="card-body">
        <div class="row"><div class="col-md-6 mx-auto"><div class="input-group mb-4"><input type="text" class="form-control" placeholder="Masukkan Nomor Laporan (contoh: 1, 2, 3)" id="trackingCode"><button class="btn btn-primary" onclick="trackReport()"><i class="fas fa-search"></i> Lacak</button></div><small class="text-muted d-block text-center"><i class="fas fa-info-circle"></i> Masukkan ID laporan (angka) untuk melacak status laporan Anda</small></div></div>
        <div id="loadingIndicator" class="text-center py-5" style="display:none;"><div class="spinner-border text-orange"></div><p class="mt-2 text-muted">Sedang mencari laporan...</p></div>
        <div id="trackingResult" style="display:none;"></div>
        <div id="noResult" class="text-center py-5" style="display:none;"><i class="fas fa-search fa-3x text-muted mb-3 d-block"></i><h6 class="text-muted">Laporan tidak ditemukan</h6><p class="text-muted small">Silakan periksa kembali nomor laporan Anda</p></div>
        <div id="defaultMessage" class="text-center py-5"><i class="fas fa-map-marker-alt fa-3x text-muted mb-3 d-block"></i><p class="text-muted">Masukkan nomor laporan untuk melihat status terbaru</p><small class="text-muted">Contoh: 1, 2, 3 (ID laporan)</small></div>
    </div>
</div>
@endsection

@push('styles')
<style>.timeline-container{position:relative;padding-left:30px}.timeline-item{position:relative;margin-bottom:20px}.timeline-badge{position:absolute;left:-30px;width:24px;height:24px;border-radius:50%;text-align:center;line-height:24px;color:white}.timeline-badge.completed{background:#28a745}.timeline-badge.active{background:#FF6B35}.timeline-badge.pending{background:#6c757d}.timeline-content{padding-left:10px}</style>
@endpush

@push('scripts')
<script>
function trackReport(){const code=document.getElementById('trackingCode').value;if(!code){Swal.fire({icon:'warning',title:'Perhatian',text:'Masukkan nomor laporan terlebih dahulu!',confirmButtonColor:'#FF6B35'});return}
document.getElementById('loadingIndicator').style.display='block';document.getElementById('trackingResult').style.display='none';document.getElementById('noResult').style.display='none';document.getElementById('defaultMessage').style.display='none'
fetch(`{{ route('mahasiswa.tracking.search') }}?code=${encodeURIComponent(code)}`,{headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}}).then(response=>response.json()).then(data=>{document.getElementById('loadingIndicator').style.display='none';if(data.success){displayTrackingResult(data.data)}else{document.getElementById('noResult').style.display='block'}}).catch(error=>{console.error('Error:',error);document.getElementById('loadingIndicator').style.display='none';document.getElementById('noResult').style.display='block'})}
function displayTrackingResult(report){let timelineHtml='';if(report.timeline&&report.timeline.length>0){report.timeline.forEach(item=>{timelineHtml+=`<div class="timeline-item"><div class="timeline-badge ${item.status}"><i class="fas fa-${item.status==='completed'?'check':(item.status==='active'?'spinner fa-spin':'clock')}"></i></div><div class="timeline-content"><small class="text-muted">${item.date}</small><p class="mb-0 fw-bold">${item.title}</p>${item.description?`<small class="text-muted">${item.description}</small>`:''}<small class="d-block text-muted">Oleh: ${item.user}</small></div></div>`})}else{timelineHtml=`<div class="text-center py-3"><i class="fas fa-history fa-2x text-muted mb-2 d-block"></i><p class="text-muted small">Belum ada aktivitas</p></div>`}
const html=`<div class="alert alert-info"><i class="fas fa-info-circle me-2"></i>Menampilkan hasil tracking untuk laporan: <strong>${report.code}</strong></div><div class="row"><div class="col-md-7"><div class="card border-0 mb-3"><div class="card-header bg-white"><h6 class="mb-0">Detail Laporan</h6></div><div class="card-body"><table class="table table-sm"><tr><td width="120"><strong>Judul</strong></td><td>: ${report.title}</td></tr><tr><td><strong>Fasilitas</strong></td><td>: ${report.facility}</td></tr><tr><td><strong>Lokasi</strong></td><td>: ${report.location}</td></tr><tr><td><strong>Deskripsi</strong></td><td>: ${report.description.substring(0,100)}${report.description.length>100?'...':''}</td></tr><tr><td><strong>Tanggal Lapor</strong></td><td>: ${report.created_at}</td></tr><tr><td><strong>SLA Deadline</strong></td><td>: ${report.sla_deadline}</td></tr></table></div></div></div><div class="col-md-5"><div class="card border-0"><div class="card-header bg-white"><h6 class="mb-0">Status Laporan</h6></div><div class="card-body text-center"><div class="mb-3">${report.status_badge}<h4 class="mt-2">${report.status_label}</h4></div></div></div></div></div><div class="card border-0 mt-3"><div class="card-header bg-white"><h6 class="mb-0"><i class="fas fa-history text-orange me-2"></i>Timeline Pengaduan</h6></div><div class="card-body"><div class="timeline-container">${timelineHtml}</div></div></div>`;document.getElementById('trackingResult').innerHTML=html;document.getElementById('trackingResult').style.display='block'}
</script>
@endpush