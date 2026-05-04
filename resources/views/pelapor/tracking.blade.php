@extends('layouts.dashboard')

@section('title', 'Tracking Laporan')

@section('dashboard-content')
<div class="card border-0">
    <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-search text-orange me-2"></i>Tracking Laporan</h5></div>
    <div class="card-body">
        <div class="row"><div class="col-md-6 mx-auto"><div class="input-group mb-4"><input type="text" class="form-control" placeholder="Masukkan Nomor Laporan" id="trackingCode"><button class="btn btn-primary" onclick="trackReport()"><i class="fas fa-search"></i> Lacak</button></div></div></div>
        <div id="trackingResult" style="display: none;"><div class="alert alert-info"><i class="fas fa-info-circle me-2"></i>Menampilkan hasil tracking untuk laporan: <strong id="reportCode"></strong></div><div class="progress-tracker" id="progressTracker"></div><div class="card mt-4" id="reportDetail"></div></div>
        <div id="noResult" class="text-center py-5" style="display: none;"><i class="fas fa-search fa-3x text-muted mb-3"></i><p>Laporan tidak ditemukan. Silakan periksa nomor laporan Anda.</p></div>
        <div id="defaultMessage" class="text-center py-5"><i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i><p>Masukkan nomor laporan untuk melihat status terbaru</p><small class="text-muted">Contoh: 1, 2, 3 (ID laporan)</small></div>
    </div>
</div>
@endsection

@push('styles')
<style>.progress-tracker{display:flex;justify-content:space-between;margin:30px 0;position:relative}.progress-tracker::before{content:'';position:absolute;top:25px;left:0;right:0;height:2px;background:#e0e0e0;z-index:1}.step{text-align:center;position:relative;z-index:2;flex:1}.step-icon{width:50px;height:50px;background:white;border:2px solid #e0e0e0;border-radius:50%;margin:0 auto 10px;display:flex;align-items:center;justify-content:center}.step.completed .step-icon{background:#28a745;border-color:#28a745;color:white}.step.active .step-icon{border-color:#FF6B35;background:#FF6B35;color:white}.step-label{font-weight:500;font-size:14px}.step-date{font-size:12px;color:#6c757d;margin-top:5px}</style>
@endpush

@push('scripts')
<script>
function trackReport() { const code = document.getElementById('trackingCode').value; if(!code){ alert('Masukkan nomor laporan'); return; } document.getElementById('trackingResult').style.display='none'; document.getElementById('noResult').style.display='none'; document.getElementById('defaultMessage').style.display='none'; fetch(`/mahasiswa/tracking/${code}`).then(res=>res.json()).then(data=>{ if(data){ document.getElementById('reportCode').innerText='#'+String(code).padStart(5,'0'); let steps=[{status:'completed',label:'Laporan Diterima',date:new Date(data.created_at).toLocaleString()},{status:data.status=='pending'?'active':(data.status=='completed'?'completed':'active'),label:data.status=='pending'?'Menunggu Verifikasi':(data.status=='in_progress'?'Dalam Proses':'Selesai'),date:data.status!='pending'?new Date(data.updated_at).toLocaleString():'-'}]; let html=''; steps.forEach(step=>{ html+=`<div class="step ${step.status}"><div class="step-icon"><i class="fas fa-${step.status=='completed'?'check':(step.status=='active'?'spinner fa-spin':'clock')}"></i></div><div class="step-label">${step.label}</div><div class="step-date">${step.date}</div></div>`; }); document.getElementById('progressTracker').innerHTML=html; document.getElementById('reportDetail').innerHTML=`<div class="card-body"><h6>Detail Laporan</h6><table class="table table-sm"><tr><td width="150">Judul</td><td>: ${data.title}</td></tr><tr><td>Fasilitas</td><td>: ${data.facility.name}</td></tr><tr><td>Lokasi</td><td>: ${data.location_detail}</td></tr><tr><td>SLA Deadline</td><td>: ${new Date(data.sla_deadline).toLocaleString()}</td></tr></table></div>`; document.getElementById('trackingResult').style.display='block'; } else { document.getElementById('noResult').style.display='block'; } }); }
</script>
@endpush