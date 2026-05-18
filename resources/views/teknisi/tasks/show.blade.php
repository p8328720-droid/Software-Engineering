@extends('layouts.dashboard')

@section('title', 'Detail Tugas')

@section('dashboard-content')
    <div class="container-fluid px-0">

        {{-- HEADER & TOMBOL KEMBALI --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 fw-bold mb-0">
                <i class="fas fa-wrench text-orange me-2"></i>Detail Tugas
                #{{ str_pad($task->report_id, 5, '0', STR_PAD_LEFT) }}
            </h1>
            <a href="{{ route('teknisi.tasks.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        {{-- PROGRESS TRACKER HORIZONTAL (SAMA DENGAN REPORTS.SHOW) --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fs-6 fw-bold"><i class="fas fa-tasks text-primary me-2"></i>Status Tracking Tugas</h5>
            </div>
            <div class="card-body py-4">
                @php
                    $currentLevel = 1;
                    if ($task->report->status === 'in_progress')
                        $currentLevel = 2;
                    if ($task->report->status === 'completed')
                        $currentLevel = 3;
                @endphp

                <div class="progress-tracker">
                    <div class="step {{ $currentLevel > 1 ? 'completed' : 'active' }}">
                        <div class="step-icon"><i class="fas fa-{{ $currentLevel > 1 ? 'check' : 'clock' }}"></i></div>
                        <div class="step-label">Laporan Masuk</div>
                        <div class="step-date">{{ $task->report->created_at->format('d M Y') }}</div>
                    </div>

                    <div class="step {{ $currentLevel > 2 ? 'completed' : ($currentLevel == 2 ? 'active' : '') }}">
                        <div class="step-icon"><i
                                class="fas fa-{{ $currentLevel > 2 ? 'check' : ($currentLevel == 2 ? 'spinner fa-spin' : 'cog') }}"></i>
                        </div>
                        <div class="step-label">Sedang Dikerjakan</div>
                        <div class="step-date">{{ $task->assigned_at ? $task->assigned_at->format('d M Y') : '-' }}</div>
                    </div>

                    <div class="step {{ $currentLevel == 3 ? 'completed' : '' }}">
                        <div class="step-icon"><i class="fas fa-{{ $currentLevel == 3 ? 'check' : 'flag-checkered' }}"></i>
                        </div>
                        <div class="step-label">Tugas Selesai</div>
                        <div class="step-date">{{ $task->completed_at ? $task->completed_at->format('d M Y') : '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- KOLOM KIRI: DETAIL LAPORAN & FORM PENYELESAIAN --}}
            <div class="col-md-8">
                <x-report-info-card :report="$task->report" />

                {{-- FORM PENYELESAIAN TUGAS --}}
                @if($task->report->status !== 'completed')
                    <div class="card border-0 shadow-sm mb-4 border-top border-4 border-success">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fs-6 fw-bold text-success"><i class="fas fa-check-double me-2"></i>Laporan
                                Penyelesaian Tugas</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('teknisi.tasks.complete', $task->id) }}" method="POST" id="completeTaskForm"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-uppercase">Catatan Perbaikan <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" name="technician_note" rows="4"
                                        placeholder="Jelaskan apa saja yang sudah diperbaiki secara detail..."
                                        required></textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-uppercase">Upload Bukti Perbaikan
                                        (Opsional)</label>
                                    <input type="file" name="completion_image" class="form-control" accept="image/*">
                                    <small class="text-muted italic">Format: JPG, PNG. Maksimal 2MB.</small>
                                </div>
                                <button type="button" class="btn btn-success btn-lg w-100 shadow-sm fw-bold"
                                    onclick="confirmComplete()">
                                    <i class="fas fa-paper-plane me-2"></i>Kirim Laporan & Tandai Selesai
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            {{-- KOLOM KANAN: STATUS, PELAPOR & TIMELINE --}}
            <div class="col-md-4">
                {{-- CARD STATUS & DEADLINE --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fs-6 fw-bold"><i class="fas fa-clock text-primary me-2"></i>Status & Waktu</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Status Laporan</label>
                            <x-report-status :status="$task->report->status" />
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Target Selesai
                                (Deadline)</label>
                            <p class="text-danger fw-bold mb-0 fs-5">
                                <i class="fas fa-calendar-times me-1"></i>
                                {{ $task->report->deadline ? $task->report->deadline->format('d M Y, H:i') : '-' }}
                            </p>
                        </div>
                        <div class="mb-0">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Ditugaskan Pada</label>
                            <p class="fw-medium mb-0">{{ $task->assigned_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>

                {{-- CARD INFORMASI PELAPOR --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fs-6 fw-bold"><i class="fas fa-user text-primary me-2"></i>Informasi Pelapor</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-user-circle text-secondary" style="font-size: 64px;"></i>
                        </div>
                        <h6 class="fw-bold mb-0 text-dark">{{ $task->report->reporter->name ?? 'Unknown' }}</h6>
                        <p class="text-muted small text-capitalize mb-3">{{ $task->report->reporter->role ?? 'Mahasiswa' }}
                        </p>
                        <hr>
                        <div class="text-start">
                            <p class="small mb-2 text-truncate"><i class="fas fa-envelope text-orange me-2"></i>
                                {{ $task->report->reporter->email ?? '-' }}</p>
                            <p class="small mb-0"><i class="fas fa-phone text-orange me-2"></i>
                                {{ $task->report->reporter->phone ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- CARD RIWAYAT AUDIT LOG --}}
                <x-report-audit-log :logs="$task->report->auditLogs" />
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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Pakai variabel 'deadline' sesuai struktur DB baru
        const deadlineStr = '{{ $task->report->deadline ? $task->report->deadline->toIso8601String() : "" }}';

        function updateSLATimer() {
            if (!deadlineStr) return;

            const deadline = new Date(deadlineStr);
            const now = new Date();
            const diff = deadline - now;

            const timerElement = document.getElementById('slaRemaining');
            if (!timerElement) return;

            if (diff <= 0) {
                timerElement.innerHTML = '<span class="text-danger fw-bold">WAKTU SLA HABIS!</span>';
                return;
            }

            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            timerElement.innerHTML = String(hours).padStart(2, '0') + ' Jam ' +
                String(minutes).padStart(2, '0') + ' Menit ' +
                String(seconds).padStart(2, '0') + ' Detik';
        }

        if (deadlineStr) {
            updateSLATimer();
            setInterval(updateSLATimer, 1000);
        }

        function confirmComplete() {
            const note = document.querySelector('textarea[name="technician_note"]').value;
            if (note.trim() === '') {
                Swal.fire('Catatan Kosong', 'Harap isi catatan perbaikan terlebih dahulu.', 'warning');
                return;
            }

            Swal.fire({
                title: 'Konfirmasi Penyelesaian',
                text: 'Apakah Anda yakin perbaikan sudah selesai dan ingin mengirim laporan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Selesai!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('completeTaskForm').submit();
                }
            });
        }
    </script>
@endpush