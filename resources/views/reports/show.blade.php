@extends('layouts.dashboard')

@section('title', 'Detail Laporan')

@section('dashboard-content')
    <div class="container-fluid px-0">

        {{-- PROGRESS TRACKER --}}
        @if(Auth::user()->role === 'pelapor' || Auth::user()->role === 'teknisi')
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-tasks text-primary me-2"></i>Status Tracking Laporan</h5>
                </div>
                <div class="card-body py-4">
                    @if($report->status === 'rejected')
                        <div class="alert alert-danger text-center mb-0">
                            <i class="fas fa-times-circle me-2"></i>Laporan ini telah ditolak.
                        </div>
                    @else
                        @php
                            // Sinkronisasi dengan lowercase enum
                            $currentLevel = 1;
                            if ($report->status === 'in_progress')
                                $currentLevel = 2;
                            if ($report->status === 'completed')
                                $currentLevel = 3;
                        @endphp

                        <div class="progress-tracker">
                            <div class="step {{ $currentLevel > 1 ? 'completed' : 'active' }}">
                                <div class="step-icon"><i class="fas fa-{{ $currentLevel > 1 ? 'check' : 'clock' }}"></i></div>
                                <div class="step-label">Menunggu Verifikasi</div>
                                <div class="step-date">{{ $report->created_at->format('d M Y') }}</div>
                            </div>

                            <div class="step {{ $currentLevel > 2 ? 'completed' : ($currentLevel == 2 ? 'active' : '') }}">
                                <div class="step-icon"><i
                                        class="fas fa-{{ $currentLevel > 2 ? 'check' : ($currentLevel == 2 ? 'spinner fa-spin' : 'cog') }}"></i>
                                </div>
                                <div class="step-label">Dalam Proses</div>
                                <div class="step-date">{{ $currentLevel >= 2 ? $report->updated_at->format('d M Y') : '-' }}</div>
                            </div>

                            <div class="step {{ $currentLevel == 3 ? 'completed' : '' }}">
                                <div class="step-icon"><i class="fas fa-{{ $currentLevel == 3 ? 'check' : 'flag-checkered' }}"></i>
                                </div>
                                <div class="step-label">Selesai</div>
                                <div class="step-date">{{ $currentLevel == 3 ? $report->updated_at->format('d M Y') : '-' }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <div class="row">
            {{-- KOLOM KIRI: DETAIL LAPORAN --}}
            <div class="col-md-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-info-circle text-info me-2"></i>Detail Laporan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="text-muted small fw-bold text-uppercase">Judul</label>
                                <p class="mb-0 fw-medium">{{ $report->title }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small fw-bold text-uppercase">Ruangan</label>
                                <p class="mb-0">{{ $report->room->name ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="text-muted small fw-bold text-uppercase">Kategori</label>
                                <p class="mb-0"><span class="badge bg-secondary">{{ $report->sla->facility_category ?? '-' }}</span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small fw-bold text-uppercase">Tingkat Urgensi</label>
                                <div class="mt-1">
                                    @if($report->urgency == 'high')
                                        <span class="badge bg-danger">High / Emergency</span>
                                    @elseif($report->urgency == 'medium')
                                        <span class="badge bg-warning text-dark">Medium</span>
                                    @else
                                        <span class="badge bg-info text-dark">Low</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if(Auth::user()->role !== 'pelapor')
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="text-muted small fw-bold text-uppercase">Pelapor</label>
                                    <p class="mb-0">{{ $report->reporter->name ?? 'User' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small fw-bold text-uppercase">Teknisi Bertugas</label>
                                    <p class="mb-0 fw-bold text-primary">
                                        {{ $report->assignment->technician->name ?? 'Belum Ditugaskan' }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="text-muted small fw-bold text-uppercase">Deskripsi Masalah</label>
                            <p class="bg-light p-3 rounded border mb-0">{{ $report->description }}</p>
                        </div>

                        @if($report->image_path)
                            <div class="mb-0">
                                <label class="text-muted small fw-bold text-uppercase mb-2">Bukti Foto</label>
                                <img src="{{ asset('storage/' . $report->image_path) }}"
                                    class="img-fluid rounded border d-block" style="max-height: 400px; width: auto;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: STATUS, RIWAYAT & AKSI --}}
            <div class="col-md-4">
                {{-- STATUS & DEADLINE --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 small fw-bold"><i class="fas fa-clock text-info me-2"></i>STATUS & WAKTU</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Status</label>
                            @if($report->status == 'pending') <span class="badge bg-warning text-dark px-3">Pending</span>
                            @elseif($report->status == 'in_progress') <span class="badge bg-info px-3">Diproses</span>
                            @elseif($report->status == 'completed') <span class="badge bg-success px-3">Selesai</span>
                            @elseif($report->status == 'rejected') <span class="badge bg-danger px-3">Ditolak</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Dilaporkan</label>
                            <span class="fw-medium">{{ $report->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="mb-0">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Batas SLA (Deadline)</label>
                            <span class="text-danger fw-bold"><i class="fas fa-hourglass-half me-1"></i>
                                {{ $report->sla_deadline ? $report->sla_deadline->format('d M Y, H:i') : '-' }}</span>
                        </div>
                    </div>
                </div>

                {{-- RIWAYAT / AUDIT LOG --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 small fw-bold"><i class="fas fa-history text-secondary me-2"></i>RIWAYAT & CATATAN
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($report->auditLogs as $log)
                                <div class="list-group-item border-0 border-bottom py-3">
                                    <div class="d-flex w-100 justify-content-between mb-1">
                                        <small class="fw-bold text-primary">{{ $log->user?->name ?? 'System' }}</small>
                                        <small class="text-muted small">{{ $log->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1 small">
                                        Status: <span
                                            class="fw-bold text-capitalize">{{ str_replace('_', ' ', $log->status_changed_to) }}</span>
                                    </p>
                                    @if($log->notes)
                                        <div class="bg-light p-2 rounded small mt-1 border-start border-4 border-info">
                                            <em>"{{ $log->notes }}"</em>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="p-4 text-center text-muted small">Belum ada riwayat aktivitas.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- ADMIN ACTIONS --}}
                @if(Auth::user()->role === 'admin')
                    <div class="card border-0 shadow-sm mb-4 bg-light border-start border-4 border-danger">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3 small"><i class="fas fa-user-shield me-2 text-danger"></i>KONTROL ADMIN</h6>

                            @if($report->status !== 'completed' && $report->status !== 'rejected')
                                <button type="button" class="btn btn-primary btn-sm w-100 shadow-sm" data-bs-toggle="modal"
                                    data-bs-target="#assignTechnicianModal">
                                    <i
                                        class="fas fa-user-plus me-2"></i>{{ $report->status === 'pending' ? 'Verifikasi & Tugaskan' : 'Alihkan Teknisi' }}
                                </button>
                            @else
                                <p class="text-muted small mb-0 text-center">Laporan sudah bersifat final.</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL PENUGASAN TEKNISI --}}
    <div class="modal fade" id="assignTechnicianModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0">
                <form action="{{ route('admin.reports.assign', $report->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header bg-primary text-white border-0">
                        <h5 class="modal-title">Penugasan Teknisi</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Teknisi</label>
                            <select class="form-select" name="assignee_id" required>
                                <option value="">-- Pilih Teknisi --</option>
                                @foreach($technicians as $technician)
                                    <option value="{{ $technician->id }}" {{ ($report->assignment?->technician_id == $technician->id) ? 'selected' : '' }}>
                                        {{ $technician->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">Catatan Instruksi</label>
                            <textarea class="form-control" name="notes" rows="3"
                                placeholder="Berikan instruksi khusus untuk teknisi..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">Simpan Penugasan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .progress-tracker {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
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
            font-size: 18px;
            color: #a0a0a0;
            transition: 0.3s;
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
            font-size: 13px;
            color: #333;
        }

        .step-date {
            font-size: 11px;
            color: #6c757d;
        }

        .italic {
            font-style: italic;
        }

        .x-small {
            font-size: 10px;
        }
    </style>
@endpush