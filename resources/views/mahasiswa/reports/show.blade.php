@extends('layouts.mahasiswa')
 
@section('title', 'Detail Laporan')
 
@section('mahasiswa-content')
<div class="row">
    <div class="col-md-8">
        <div class="card border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0">Detail Laporan #{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="35%">No. Laporan</th>
                        <td>#{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <th>Judul</th>
                        <td>{{ $report->title }}</td>
                    </tr>
                    <tr>
                        <th>Fasilitas</th>
                        <td>{{ $report->facility->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi Detail</th>
                        <td>{{ $report->location_detail }}</td>
                    </tr>
                    <tr>
                        <th>Urgensi</th>
                        <td>{!! $report->urgency_badge !!}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $report->description }}</td>
                    </tr>
                    @if($report->image_path)
                    <tr>
                        <th>Foto</th>
                        <td><img src="{{ asset('storage/' . $report->image_path) }}" class="img-fluid rounded" style="max-height:250px;"></td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
 
        {{-- ✅ Route diperbaiki: mahasiswa.reports.comment (bukan admin) --}}
        <x-chat-comments
            :report="$report"
            :commentUrl="route('mahasiswa.reports.comment', $report)"
            :currentUserId="Auth::id()" />
    </div>
 
    <div class="col-md-4">
        <!-- Status Card -->
        <div class="card border-0 mb-3">
            <div class="card-header bg-white">
                <h5 class="mb-0">Status Laporan</h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    @php
                        $iconMap  = ['pending'=>'clock','in_progress'=>'spinner fa-spin','completed'=>'check-circle','rejected'=>'times-circle','verified'=>'check'];
                        $colorMap = ['pending'=>'secondary','in_progress'=>'warning','completed'=>'success','rejected'=>'danger','verified'=>'info'];
                        $labelMap = ['pending'=>'Menunggu','in_progress'=>'Diproses','completed'=>'Selesai','rejected'=>'Ditolak','verified'=>'Diverifikasi'];
                    @endphp
                    <i class="fas fa-{{ $iconMap[$report->status] ?? 'clock' }} fa-3x text-{{ $colorMap[$report->status] ?? 'secondary' }}"></i>
                    <h3 class="mt-2">{{ $labelMap[$report->status] ?? $report->status }}</h3>
                </div>
                <hr>
                <div class="text-start">
                    <p><i class="fas fa-calendar me-2"></i> Dilaporkan: {{ $report->created_at ? $report->created_at->format('d M Y, H:i') : '-' }}</p>
                    <p><i class="fas fa-hourglass-half me-2"></i> SLA Deadline:
                        @if($report->sla_deadline)
                            {{ $report->sla_deadline->format('d M Y, H:i') }}
                            @if($report->sla_deadline < now() && $report->status != 'completed')
                                <span class="badge bg-danger ms-2">Terlambat</span>
                            @endif
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </p>
                    @if($report->resolved_at)
                    <p><i class="fas fa-check-circle me-2 text-success"></i> Selesai: {{ $report->resolved_at->format('d M Y, H:i') }}</p>
                    @endif
                </div>
            </div>
        </div>
 
        <!-- Rating Form -->
        <div id="ratingContainer">
            @if($report->canRate(Auth::id()))
            <div class="card border-0 mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-star text-warning me-2"></i>Beri Rating untuk Laporan Ini</h5>
                </div>
                <div class="card-body">
                    <form id="ratingForm">
                        @csrf
                        <div class="mb-3 text-center">
                            <label class="form-label fw-bold">Seberapa puas Anda dengan penanganan laporan ini?</label>
                            <div class="rating-stars mb-2">
                                <div class="star-rating">
                                    <input type="radio" name="rating" value="5" id="star5"><label for="star5">★</label>
                                    <input type="radio" name="rating" value="4" id="star4"><label for="star4">★</label>
                                    <input type="radio" name="rating" value="3" id="star3"><label for="star3">★</label>
                                    <input type="radio" name="rating" value="2" id="star2"><label for="star2">★</label>
                                    <input type="radio" name="rating" value="1" id="star1"><label for="star1">★</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Komentar (Opsional)</label>
                            <textarea name="rating_comment" id="ratingComment" class="form-control" rows="3" placeholder="Tulis komentar atau saran Anda..."></textarea>
                        </div>
                        <button type="button" class="btn btn-primary w-100" onclick="submitRating({{ $report->id }})">
                            <i class="fas fa-paper-plane me-2"></i> Kirim Rating
                        </button>
                    </form>
                </div>
            </div>
            @elseif($report->hasRated())
            <div class="card border-0 mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-star text-warning me-2"></i>Rating Anda untuk Laporan Ini</h5>
                </div>
                <div class="card-body text-center" id="ratingDisplay">
                    <div class="mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $report->rating)
                                <i class="fas fa-star text-warning fa-2x"></i>
                            @else
                                <i class="far fa-star text-secondary fa-2x"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="mb-0"><strong>{{ $report->rating }}/5 bintang</strong></p>
                    @if($report->rating_comment)
                        <p class="text-muted small mt-2">"{{ $report->rating_comment }}"</p>
                    @endif
                    <small class="text-muted">Rating sudah disimpan. Terima kasih!</small>
                </div>
            </div>
            @endif
        </div>
 
        <!-- Timeline Card -->
        <div class="card border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0">Timeline</h5>
            </div>
            <div class="card-body">
                @if($report->statusHistory && $report->statusHistory->count() > 0)
                    @foreach($report->statusHistory as $history)
                    <div class="timeline-item d-flex mb-3">
                        <div class="timeline-icon me-3">
                            @if($history->status == 'completed')
                                <i class="fas fa-check-circle text-success fa-lg"></i>
                            @elseif($history->status == 'in_progress')
                                <i class="fas fa-spinner fa-spin text-warning fa-lg"></i>
                            @else
                                <i class="fas fa-clock text-secondary fa-lg"></i>
                            @endif
                        </div>
                        <div class="timeline-content">
                            <h6 class="mb-0">{{ ucfirst($history->status) }}</h6>
                            <small class="text-muted">{{ $history->created_at ? $history->created_at->format('d M Y, H:i') : '-' }}</small>
                            @if($history->description)
                                <p class="mb-0 small text-muted">{{ $history->description }}</p>
                            @endif
                            <small class="text-muted">Oleh: {{ $history->user->name ?? 'Sistem' }}</small>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-history fa-2x text-muted mb-2 d-block"></i>
                        <p class="text-muted small">Belum ada aktivitas</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
 
@push('styles')
<style>
.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: center;
    gap: 10px;
}
.star-rating input {
    display: none;
}
.star-rating label {
    font-size: 40px;
    color: #ddd;
    cursor: pointer;
    transition: all 0.2s;
}
.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: #ffc107;
}
.timeline-icon {
    min-width: 32px;
    text-align: center;
}
.timeline-content {
    flex: 1;
}
</style>
@endpush
 
@push('scripts')
<script>
// Submit Rating via AJAX
function submitRating(reportId) {
    const rating = document.querySelector('input[name="rating"]:checked');
    if (!rating) {
        Swal.fire('Info', 'Pilih rating terlebih dahulu', 'info');
        return;
    }
 
    const ratingComment = document.getElementById('ratingComment').value;
 
    Swal.fire({
        title: 'Kirim Rating?',
        text: 'Apakah Anda yakin dengan rating ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#FF6B35',
        confirmButtonText: 'Ya, Kirim!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ url('mahasiswa/reports') }}/${reportId}/rating`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    rating: rating.value,
                    rating_comment: ratingComment
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const ratingContainer = document.getElementById('ratingContainer');
                    ratingContainer.innerHTML = `
                        <div class="card border-0 mb-3">
                            <div class="card-header bg-white">
                                <h5 class="mb-0"><i class="fas fa-star text-warning me-2"></i>Rating Anda untuk Laporan Ini</h5>
                            </div>
                            <div class="card-body text-center">
                                <div class="mb-2">${generateStars(data.rating)}</div>
                                <p class="mb-0"><strong>${data.rating}/5 bintang</strong></p>
                                ${data.rating_comment ? `<p class="text-muted small mt-2">"${data.rating_comment}"</p>` : ''}
                                <small class="text-muted">Rating sudah disimpan. Terima kasih!</small>
                            </div>
                        </div>
                    `;
                    Swal.fire('Berhasil!', data.message, 'success');
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                }
            })
            .catch(() => Swal.fire('Error!', 'Terjadi kesalahan', 'error'));
        }
    });
}
 
function generateStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        stars += i <= rating
            ? '<i class="fas fa-star text-warning fa-2x"></i>'
            : '<i class="far fa-star text-secondary fa-2x"></i>';
    }
    return stars;
}
</script>
@endpush