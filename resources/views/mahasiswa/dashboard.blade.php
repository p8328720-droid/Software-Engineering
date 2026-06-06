@extends('layouts.mahasiswa')

@section('title', 'Dashboard Mahasiswa')

@section('mahasiswa-content')
    <div class="row mb-4 g-3">
        <div class="col-6 col-md-3">
            <div class="card text-center border-0">
                <div class="card-body stats-card-body">
                    <i class="fas fa-flag-checkered stats-icon text-orange"></i>
                    <div class="stats-label">Total Laporan</div>
                    <div class="stats-number text-orange">{{ $stats['total_reports'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center border-0">
                <div class="card-body stats-card-body">
                    <i class="fas fa-spinner stats-icon text-warning"></i>
                    <div class="stats-label">Dalam Proses</div>
                    <div class="stats-number text-warning">{{ $stats['in_progress_reports'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center border-0">
                <div class="card-body stats-card-body">
                    <i class="fas fa-check-circle stats-icon text-success"></i>
                    <div class="stats-label">Selesai</div>
                    <div class="stats-number text-success">{{ $stats['completed_reports'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center border-0">
                <div class="card-body stats-card-body">
                    <i class="fas fa-times-circle stats-icon text-danger"></i>
                    <div class="stats-label">Ditolak</div>
                    <div class="stats-number text-danger">{{ $stats['rejected_reports'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-history text-orange me-2"></i>Laporan Terbaru Anda</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="table-light">
                            <th>ID</th>
                            <th>Judul</th>
                            <th>Fasilitas</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentReports ?? [] as $report)
                            <tr>
                                <td>#{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $report->title }}</td>
                                <td>{{ $report->facility->name ?? '-' }}</td>
                                <td>{!! $report->status_badge !!}</td>
                                <td>{{ $report->created_at->format('d/m/Y') }}</td>
                                <td><a href="{{ route('mahasiswa.reports.show', $report) }}"
                                        class="btn btn-sm btn-outline-primary">Detail</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Belum ada laporan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-3"><a href="{{ route('mahasiswa.reports.create') }}" class="btn btn-primary"><i
                        class="fas fa-plus-circle me-1"></i> Buat Laporan Baru</a></div>
        </div>
    </div>
@endsection

@once
    @push('styles')
        <style>
            .stats-card-body {
                padding: 1.25rem 1rem;
            }

            .stats-icon {
                font-size: 2rem;
                margin-bottom: .5rem;
                display: block;
            }

            .stats-label {
                font-size: .8rem;
                font-weight: 500;
                color: #6c757d;
                margin-bottom: .25rem;
            }

            .stats-number {
                font-size: 1.75rem;
                font-weight: 700;
                line-height: 1;
            }

            /* Mobile — lebih compact */
            @media (max-width: 767.98px) {
                .stats-card-body {
                    padding: .875rem .75rem;
                }

                .stats-icon {
                    font-size: 1.5rem;
                    margin-bottom: .35rem;
                }

                .stats-number {
                    font-size: 1.4rem;
                }

                .stats-label {
                    font-size: .72rem;
                }
            }
        </style>
    @endpush
@endonce