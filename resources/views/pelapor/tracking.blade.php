@extends('layouts.dashboard')

@section('title', 'Tracking Laporan')

@section('dashboard-content')
<div class="container-fluid px-0">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-search text-orange me-2"></i>Tracking Laporan</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="input-group mb-4">
                        <input type="text" class="form-control" placeholder="Masukkan Nomor/ID Laporan" id="trackingCode">
                        <button class="btn btn-primary" onclick="trackReport()">
                            <i class="fas fa-search"></i> Lacak
                        </button>
                    </div>
                </div>
            </div>

            {{-- Loading State (Opsional, untuk UX yang lebih baik) --}}
            <div id="loadingState" class="text-center py-5" style="display: none;">
                <i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i>
                <p>Mencari laporan...</p>
            </div>

            {{-- Hasil Tracking --}}
            <div id="trackingResult" style="display: none;">
                <div class="alert alert-info border-0 shadow-sm">
                    <i class="fas fa-info-circle me-2"></i>Menampilkan hasil tracking untuk laporan: <strong id="reportCode"></strong>
                </div>
                
                <div class="progress-tracker" id="progressTracker">
                    </div>
                
                <div class="card mt-4 border-0 shadow-sm">
                    <div class="card-body" id="reportDetail">
                        </div>
                </div>
            </div>

            {{-- Tidak Ditemukan --}}
            <div id="noResult" class="text-center py-5" style="display: none;">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <p>Laporan tidak ditemukan. Silakan periksa kembali nomor laporan Anda.</p>
            </div>

            {{-- Pesan Default --}}
            <div id="defaultMessage" class="text-center py-5">
                <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                <p>Masukkan nomor laporan untuk melihat status terbaru</p>
                <small class="text-muted">Contoh: 1, 2, 3 (ID laporan)</small>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .progress-tracker {
        display: flex;
        justify-content: space-between;
        margin: 40px 0;
        position: relative;
    }
    
    .progress-tracker::before {
        content: '';
        position: absolute;
        top: 25px;
        left: 10%;
        right: 10%;
        height: 3px;
        background: #e0e0e0;
        z-index: 1;
    }
    
    .step {
        text-align: center;
        position: relative;
        z-index: 2;
        flex: 1;
    }
    
    .step-icon {
        width: 50px;
        height: 50px;
        background: white;
        border: 3px solid #e0e0e0;
        border-radius: 50%;
        margin: 0 auto 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #a0a0a0;
        transition: all 0.3s;
    }
    
    .step.completed .step-icon {
        background: #28a745;
        border-color: #28a745;
        color: white;
    }
    
    .step.active .step-icon {
        border-color: #FF6B35;
        background: #FF6B35;
        color: white;
    }
    
    .step-label {
        font-weight: 600;
        font-size: 14px;
        color: #333;
    }
    
    .step-date {
        font-size: 12px;
        color: #6c757d;
        margin-top: 5px;
    }
</style>
@endpush

@push('scripts')
<script>
// Fungsi untuk memformat tanggal (agar tidak error jika null)
function formatDate(dateString) {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleString('id-ID', {
        day: 'numeric', month: 'short', year: 'numeric', 
        hour: '2-digit', minute: '2-digit'
    });
}

// Fungsi untuk mencegah serangan XSS sederhana pada output teks
function escapeHtml(unsafe) {
    return (unsafe || '').toString()
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;");
}

async function trackReport() {
    const code = document.getElementById('trackingCode').value.trim();
    
    if (!code) {
        alert('Masukkan nomor laporan terlebih dahulu.');
        return;
    }

    // Reset semua tampilan
    document.getElementById('trackingResult').style.display = 'none';
    document.getElementById('noResult').style.display = 'none';
    document.getElementById('defaultMessage').style.display = 'none';
    document.getElementById('loadingState').style.display = 'block';

    try {
        const response = await fetch(`/pelapor/tracking/${code}`);
        
        // Jika response bukan 200 OK (misal: 404 Not Found)
        if (!response.ok) {
            throw new Error('Laporan tidak ditemukan');
        }

        const data = await response.json();

        // Menyembunyikan loading
        document.getElementById('loadingState').style.display = 'none';

        if (data) {
            document.getElementById('reportCode').innerText = '#' + String(code).padStart(5, '0');
            
            // Konfigurasi Timeline (3 Langkah)
            const statusLevel = {
                'pending': 1,
                'in_progress': 2,
                'completed': 3
            };
            
            const currentLevel = statusLevel[data.status] || 1;

            const timelineSteps = [
                {
                    level: 1,
                    label: 'Menunggu Verifikasi',
                    icon: currentLevel > 1 ? 'check' : 'clock',
                    status: currentLevel > 1 ? 'completed' : (currentLevel === 1 ? 'active' : ''),
                    date: formatDate(data.created_at)
                },
                {
                    level: 2,
                    label: 'Dalam Proses',
                    icon: currentLevel > 2 ? 'check' : (currentLevel === 2 ? 'spinner fa-spin' : 'cog'),
                    status: currentLevel > 2 ? 'completed' : (currentLevel === 2 ? 'active' : ''),
                    date: currentLevel >= 2 ? formatDate(data.updated_at) : '-'
                },
                {
                    level: 3,
                    label: 'Selesai',
                    icon: currentLevel === 3 ? 'check' : 'flag-checkered',
                    status: currentLevel === 3 ? 'completed' : '',
                    date: currentLevel === 3 ? formatDate(data.updated_at) : '-'
                }
            ];

            // Render Timeline
            let trackerHtml = '';
            timelineSteps.forEach(step => {
                trackerHtml += `
                    <div class="step ${step.status}">
                        <div class="step-icon">
                            <i class="fas fa-${step.icon}"></i>
                        </div>
                        <div class="step-label">${step.label}</div>
                        <div class="step-date">${step.date}</div>
                    </div>
                `;
            });
            document.getElementById('progressTracker').innerHTML = trackerHtml;

            // Render Detail (Menggunakan escapeHtml untuk mencegah XSS)
            document.getElementById('reportDetail').innerHTML = `
                <h6 class="mb-3 border-bottom pb-2">Detail Laporan</h6>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="150" class="text-muted">Judul</td>
                        <td class="fw-bold">: ${escapeHtml(data.title)}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Fasilitas</td>
                        <td>: ${data.facility ? escapeHtml(data.facility.name) : '-'}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Lokasi</td>
                        <td>: ${escapeHtml(data.location_detail)}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">SLA Deadline</td>
                        <td class="text-danger">: ${formatDate(data.sla_deadline)}</td>
                    </tr>
                </table>
            `;

            document.getElementById('trackingResult').style.display = 'block';
        } else {
            throw new Error('Data kosong');
        }
    } catch (error) {
        // Tangkap error (baik dari network maupun data tidak ditemukan)
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('noResult').style.display = 'block';
    }
}
</script>
@endpush