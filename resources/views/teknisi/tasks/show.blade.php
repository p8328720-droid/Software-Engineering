@extends('layouts.teknisi')

@section('title', 'Detail Tugas')

@section('teknisi-content')
<div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-clipboard-list text-orange me-2"></i>Detail Tugas #{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</h1>
    <a href="{{ route('teknisi.tasks.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informasi Laporan</h5>
            </div>
            <div class="card-body">
                @if($report->sla_deadline && $report->sla_deadline < now() && $report->status != 'completed')
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Perhatian!</strong> Laporan ini telah melewati batas SLA.
                    </div>
                @elseif($report->sla_deadline && $report->sla_deadline->diffInHours(now()) < 24 && $report->status != 'completed')
                    <div class="alert alert-warning">
                        <i class="fas fa-clock me-2"></i>
                        <strong>Peringatan!</strong> Sisa waktu SLA kurang dari 24 jam.
                    </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="fw-bold">No. Laporan</label>
                        <p>#{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold">Status</label>
                        <p>{!! $report->status_badge !!}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold">Urgensi</label>
                        <p>{!! $report->urgency_badge !!}</p>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Judul</label>
                    <p>{{ $report->title }}</p>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Lokasi</label>
                    <p>{{ $report->location_detail }}</p>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Deskripsi</label>
                    <div class="p-3 bg-light rounded">
                        <p class="mb-0">{{ $report->description }}</p>
                    </div>
                </div>

                @if($report->image_path)
                <div class="mb-3">
                    <label class="fw-bold">Bukti Foto</label>
                    <div>
                        <img src="{{ asset('storage/'.$report->image_path) }}" class="img-thumbnail" style="max-width:300px; cursor:pointer" onclick="openImageModal(this.src)">
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Update Status Card -->
        <div class="card border-0 mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Update Status</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('teknisi.status.update', $report) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="fw-bold">Ubah Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $report->status=='pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="verified" {{ $report->status=='verified' ? 'selected' : '' }}>Diverifikasi</option>
                            <option value="in_progress" {{ $report->status=='in_progress' ? 'selected' : '' }}>Diproses</option>
                            <option value="completed" {{ $report->status=='completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="rejected" {{ $report->status=='rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Catatan</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Tambahkan catatan..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                </form>
            </div>
        </div>

        <!-- Rating Card -->
        @if($report->status == 'completed' && $report->rating)
        <div class="card border-0 mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-star text-warning me-2"></i>Rating Laporan</h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $report->rating)
                            <i class="fas fa-star text-warning fa-2x"></i>
                        @else
                            <i class="far fa-star text-secondary fa-2x"></i>
                        @endif
                    @endfor
                </div>
                <p class="mb-1"><strong>{{ $report->rating }}/5 bintang</strong></p>
                @if($report->rating_comment)
                    <p class="text-muted small mt-2">"{{ $report->rating_comment }}"</p>
                @endif
                <small class="text-muted">Rating dari mahasiswa</small>
            </div>
        </div>
        @endif

        <!-- Timeline Card -->
        <div class="card border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0">Timeline</h5>
            </div>
            <div class="card-body">
                @foreach($report->statusHistory as $history)
                <div class="timeline-item d-flex mb-3">
                    <div class="timeline-icon me-2">
                        @if($history->status == 'completed')
                            <i class="fas fa-check-circle text-success"></i>
                        @elseif($history->status == 'in_progress')
                            <i class="fas fa-spinner fa-spin text-warning"></i>
                        @else
                            <i class="fas fa-clock text-secondary"></i>
                        @endif
                    </div>
                    <div class="timeline-content">
                        <strong>{{ ucfirst($history->status) }}</strong>
                        <br>
                        <small class="text-muted">{{ $history->created_at->format('d M Y, H:i') }}</small>
                        @if($history->description)
                            <p class="small mb-0 text-muted">{{ $history->description }}</p>
                        @endif
                        <small>Oleh: {{ $history->user->name ?? 'Sistem' }}</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bukti Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalImage" class="img-fluid">
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline-icon {
    width: 24px;
    text-align: center;
}
.timeline-content {
    flex: 1;
}
</style>
@endpush

@push('scripts')
<script>
function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}
</script>
@endpush