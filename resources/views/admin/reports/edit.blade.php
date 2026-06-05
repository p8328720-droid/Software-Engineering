@extends('layouts.admin')

@section('title', 'Edit Laporan')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-edit me-2 text-orange"></i>Edit Laporan #{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</h1>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="card border-0 mb-3">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informasi Laporan</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="fw-bold">Pelapor</label>
                        <p>{{ $report->user->name }} ({{ $report->user->email }})</p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Fasilitas</label>
                        <p>{{ $report->facility->name ?? '-' }} - {{ $report->facility->location ?? '-' }}</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="fw-bold">Judul</label>
                        <p>{{ $report->title }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Lokasi Detail</label>
                        <p>{{ $report->location_detail }}</p>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Deskripsi</label>
                    <p>{{ $report->description }}</p>
                </div>
                @if($report->image_path)
                <div class="mb-3">
                    <label class="fw-bold">Bukti Foto</label>
                    <div>
                        <img src="{{ asset('storage/'.$report->image_path) }}" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                </div>
                @endif
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="fw-bold">Tanggal Lapor</label>
                        <p>{{ $report->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">SLA Deadline</label>
                        <p>{{ $report->sla_deadline?->format('d M Y, H:i') ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <!-- Update Status Card -->
        <div class="card border-0 mb-3">
            <div class="card-header bg-white">
                <h5 class="mb-0">Ubah Status</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reports.update', $report->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="fw-bold">Status Saat Ini</label>
                        <div class="mb-2">
                            {!! $report->status_badge !!}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Ubah Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="verified" {{ $report->status == 'verified' ? 'selected' : '' }}>Diverifikasi</option>
                            <option value="in_progress" {{ $report->status == 'in_progress' ? 'selected' : '' }}>Diproses</option>
                            <option value="completed" {{ $report->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-1"></i> Update Status
                    </button>
                    <x-chat-comments
    :report="$report"
    :commentUrl="route('admin.reports.comment', $report)"
    :currentUserId="Auth::id()" />
                </form>
            </div>
        </div>

        <!-- Rating Card -->
        <div class="card border-0 mb-3">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-star text-warning me-2"></i>Rating</h5>
            </div>
            <div class="card-body text-center">
                @if($report->rating)
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
                        <p class="text-muted small mb-2">"{{ $report->rating_comment }}"</p>
                    @endif
                    <form action="{{ route('admin.reports.delete-rating', $report->id) }}" method="POST" onsubmit="return confirm('Hapus rating ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash me-1"></i> Hapus Rating
                        </button>
                    </form>
                @else
                    <i class="fas fa-star fa-3x text-muted mb-2 d-block"></i>
                    <p class="text-muted">Belum ada rating</p>
                @endif
            </div>
        </div>

        <!-- Timeline Card -->
        <div class="card border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-history text-orange me-2"></i>Timeline</h5>
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