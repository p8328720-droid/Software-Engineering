@extends('layouts.dashboard')

@section('title', 'Detail Laporan')

@section('dashboard-content')
    <div class="container-fluid px-0">

        {{-- PROGRESS TRACKER HORIZONTAL --}}
        @if(Auth::user()->isPelapor() || Auth::user()->isTeknisi())
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-tasks text-primary me-2"></i>Status Tracking Laporan</h5>
                </div>
                <div class="card-body py-4">
                    @php
                        $currentLevel = 1;
                        if ($report->status === 'in_progress') {
                            $currentLevel = 2;
                        } elseif ($report->status === 'completed') {
                            $currentLevel = 3;
                        }
                    @endphp

                    <div class="progress-tracker">
                        <div class="step {{ $currentLevel > 1 ? 'completed' : 'active' }}">
                            <div class="step-icon"><i class="fas fa-{{ $currentLevel > 1 ? 'check' : 'clock' }}"></i></div>
                            <div class="step-label">Menunggu Verifikasi</div>
                            <div class="step-date">{{ $report->created_at->format('d M Y, H:i') }}</div>
                        </div>

                        <div class="step {{ $currentLevel > 2 ? 'completed' : ($currentLevel == 2 ? 'active' : '') }}">
                            <div class="step-icon"><i
                                    class="fas fa-{{ $currentLevel > 2 ? 'check' : ($currentLevel == 2 ? 'spinner fa-spin' : 'cog') }}"></i>
                            </div>
                            <div class="step-label">Dalam Proses</div>
                            <div class="step-date">
                                {{ $currentLevel >= 2 ? $report->updated_at->format('d M Y, H:i') : '-' }}
                            </div>
                        </div>

                        <div class="step {{ $currentLevel == 3 ? 'completed' : '' }}">
                            <div class="step-icon"><i class="fas fa-{{ $currentLevel == 3 ? 'check' : 'flag-checkered' }}"></i>
                            </div>
                            <div class="step-label">Selesai</div>
                            <div class="step-date">
                                {{ $currentLevel == 3 ? $report->updated_at->format('d M Y, H:i') : '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Main Content Row --}}
        <div class="row">
            {{-- Kolom Kiri: Detail Utama Laporan --}}
            <div class="col-md-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-info-circle text-info me-2"></i>Detail Laporan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="text-muted small fw-bold text-uppercase">Judul</label>
                                <p class="mb-0 fw-medium">{{ $report->title }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small fw-bold text-uppercase">Fasilitas</label>
                                <p class="mb-0">{{ $report->facility->name }} - {{ $report->facility->location }}</p>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="text-muted small fw-bold text-uppercase">Lokasi Detail</label>
                                <p class="mb-0">{{ $report->location_detail }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small fw-bold text-uppercase">Tingkat Urgensi</label>
                                <div class="mt-1">
                                    <x-urgency-badge :urgency="$report->urgency" />
                                </div>
                            </div>
                        </div>

                        @if(Auth::user()->isTeknisi() || Auth::user()->isAdmin())
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="text-muted small fw-bold text-uppercase">Pelapor</label>
                                    <p class="mb-0">{{ $report->user->name }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="text-muted small fw-bold text-uppercase">Deskripsi</label>
                            <p class="mb-0 bg-light p-3 rounded border">{{ $report->description }}</p>
                        </div>

                        @if($report->image_path)
                            <div class="mb-3">
                                <label class="text-muted small fw-bold text-uppercase mb-2">Bukti Foto</label>
                                <div>
                                    <img src="{{ asset('storage/' . $report->image_path) }}" alt="Bukti Foto Laporan"
                                        class="img-thumbnail rounded shadow-sm"
                                        style="max-width: 100%; height: auto; max-height: 400px; object-fit: contain;">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Timeline, Comments & Admin Actions --}}
            <div class="col-md-4">

                {{-- Card Info Status & SLA --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-info-circle text-info me-2"></i>Status & Waktu</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-start">
                            <p class="mb-3 pb-3 border-bottom">
                                <i class="fas fa-calendar text-muted me-2"></i>
                                <span class="text-muted">Dilaporkan:</span><br>
                                <strong class="ms-4">{{ $report->created_at->format('d M Y, H:i') }}</strong>
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-hourglass-half text-danger me-2"></i>
                                <span class="text-muted">SLA Deadline:</span><br>
                                <strong class="ms-4 text-danger">{{ $report->sla_deadline->format('d M Y, H:i') }}</strong>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Timeline Section --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-history text-secondary me-2"></i>Riwayat Timeline</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($report->statusHistory) && $report->statusHistory->isNotEmpty())
                            @php
                                $timelineItems = $report->statusHistory->map(function ($history) {
                                    $statusClass = 'pending';
                                    if ($history->status == 'in_progress') {
                                        $statusClass = 'active';
                                    } elseif ($history->status == 'completed') {
                                        $statusClass = 'completed';
                                    }
                                    return [
                                        'status' => $statusClass,
                                        'title' => ucfirst(str_replace('_', ' ', $history->status)),
                                        'date' => $history->created_at->format('d M Y, H:i'),
                                        'description' => $history->description ?? ''
                                    ];
                                })->toArray();
                            @endphp
                            <x-timeline :items="$timelineItems" />
                        @else
                            <p class="text-muted text-center">Belum ada riwayat status.</p>
                        @endif
                    </div>
                </div>

                {{-- Comments Section (visible for Teknisi and Admin) --}}
                @if(Auth::user()->isTeknisi() || Auth::user()->isAdmin())
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0"><i class="fas fa-comments text-primary me-2"></i>Komentar</h5>
                        </div>
                        <div class="card-body">
                            <div class="comments-list mb-3">
                                @forelse($report->comments as $comment)
                                    <div class="d-flex mb-3">
                                        <img src="{{ $comment->user->avatar_url ?? asset('images/default-avatar.png') }}"
                                            class="rounded-circle me-2" width="32" alt="{{ $comment->user->name }}">
                                        <div>
                                            <strong>{{ $comment->user->name }}</strong>
                                            <br><small class="text-muted">{{ $comment->created_at->format('d M Y, H:i') }}</small>
                                            <p class="mb-0 small">{{ $comment->comment }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted text-center">Belum ada komentar</p>
                                @endforelse
                            </div>
                            <form action="{{ route('reports.comments.store', $report->id) }}" method="POST">
                                @csrf
                                <textarea class="form-control" rows="3" placeholder="Tulis komentar..."
                                    name="comment"></textarea>
                                <button type="submit" class="btn btn-primary btn-sm mt-2">Kirim</button>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- Admin Specific Actions --}}
                @if(Auth::user()->isAdmin())
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0"><i class="fas fa-cogs text-danger me-2"></i>Aksi Admin</h5>
                        </div>
                        <div class="card-body text-center">
                            @if($report->status == 'pending')
                                <button type="button" class="btn btn-success me-2 mb-2" data-bs-toggle="modal"
                                    data-bs-target="#verifyReportModal">
                                    <i class="fas fa-check me-1"></i>Verifikasi Laporan
                                </button>
                            @endif

                            <button type="button" class="btn btn-info mb-2" data-bs-toggle="modal"
                                data-bs-target="#forwardReportModal">
                                <i class="fas fa-paper-plane me-1"></i>Teruskan ke Teknisi
                            </button>
                        </div>
                    </div>
                @endif

            </div> {{-- End Col-md-4 --}}
        </div> {{-- End Row --}}

        {{-- MODALS dipindahkan ke DALAM section agar tidak merusak layout layout utama --}}

        {{-- Verify Report Modal --}}
        <div class="modal fade" id="verifyReportModal" tabindex="-1" aria-labelledby="verifyReportModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verifyReportModalLabel">Verifikasi & Tetapkan Laporan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Verifikasi laporan ini dan tetapkan teknisi yang akan menanganinya.</p>
                        <form id="verifyForm" action="{{ route('admin.reports.verify', $report->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label for="assignee_id" class="form-label">Tetapkan ke Teknisi</label>
                                <select class="form-select" id="assignee_id" name="assignee_id" required>
                                    <option value="">Pilih Teknisi...</option>
                                    @foreach($technicians as $technician)
                                        <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="verification_notes" class="form-label">Catatan Verifikasi (Opsional)</label>
                                <textarea class="form-control" id="verification_notes" name="verification_notes"
                                    rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" form="verifyForm" class="btn btn-primary">Verifikasi & Tetapkan</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Forward Report Modal --}}
        <div class="modal fade" id="forwardReportModal" tabindex="-1" aria-labelledby="forwardReportModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="forwardReportModalLabel">Teruskan Laporan ke Teknisi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Pilih teknisi untuk meneruskan laporan ini.</p>
                        <form id="forwardForm" action="{{ route('admin.reports.forward', $report->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label for="forward_assignee_id" class="form-label">Tetapkan ke Teknisi</label>
                                <select class="form-select" id="forward_assignee_id" name="assignee_id" required>
                                    <option value="">Pilih Teknisi...</option>
                                    {{-- Loop dinamis menggunakan variabel $technicians --}}
                                    @foreach($technicians as $technician)
                                        <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="forwarding_notes" class="form-label">Catatan Penerusan (Opsional)</label>
                                <textarea class="form-control" id="forwarding_notes" name="forwarding_notes"
                                    rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" form="forwardForm" class="btn btn-primary">Teruskan</button>
                    </div>
                </div>
            </div>
        </div>

    </div> {{-- End Container Fluid --}}
@endsection

@push('styles')
    <style>
        .progress-tracker {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            position: relative;
            overflow: hidden;
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
            padding: 0 5px;
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
            transition: all 0.3s ease;
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

        .card-header h5 i {
            vertical-align: middle;
            margin-right: 8px;
        }

        .modal-body img {
            max-width: 100%;
            height: auto;
        }
    </style>
@endpush