@extends('layouts.dashboard')

@section('title', 'Detail Laporan')

@section('dashboard-content')
<div class="container-fluid px-0">
    
    {{-- PROGRESS TRACKER HORIZONTAL (BARU) --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-tasks text-primary me-2"></i>Status Tracking Laporan
            </h5>
        </div>
        <div class="card-body py-4">
            @php
                // Menentukan level progres (1: Pending, 2: In Progress, 3: Completed)
                $currentLevel = $report->status == 'completed' ? 3 : ($report->status == 'in_progress' ? 2 : 1);
            @endphp
            
            <div class="progress-tracker">
                {{-- Step 1: Menunggu Verifikasi --}}
                <div class="step {{ $currentLevel > 1 ? 'completed' : 'active' }}">
                    <div class="step-icon">
                        <i class="fas fa-{{ $currentLevel > 1 ? 'check' : 'clock' }}"></i>
                    </div>
                    <div class="step-label">Menunggu Verifikasi</div>
                    <div class="step-date">{{ $report->created_at->format('d M Y, H:i') }}</div>
                </div>

                {{-- Step 2: Dalam Proses --}}
                <div class="step {{ $currentLevel > 2 ? 'completed' : ($currentLevel == 2 ? 'active' : '') }}">
                    <div class="step-icon">
                        <i class="fas fa-{{ $currentLevel > 2 ? 'check' : ($currentLevel == 2 ? 'spinner fa-spin' : 'cog') }}"></i>
                    </div>
                    <div class="step-label">Dalam Proses</div>
                    <div class="step-date">
                        {{ $currentLevel >= 2 ? $report->updated_at->format('d M Y, H:i') : '-' }}
                    </div>
                </div>

                {{-- Step 3: Selesai --}}
                <div class="step {{ $currentLevel == 3 ? 'completed' : '' }}">
                    <div class="step-icon">
                        <i class="fas fa-{{ $currentLevel == 3 ? 'check' : 'flag-checkered' }}"></i>
                    </div>
                    <div class="step-label">Selesai</div>
                    <div class="step-date">
                        {{ $currentLevel == 3 ? $report->updated_at->format('d M Y, H:i') : '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KONTEN DETAIL BAWAH (2 KOLOM) --}}
    <div class="row">
        {{-- Kolom Kiri: Detail Utama Laporan --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt text-primary me-2"></i>
                        Detail Laporan #{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}
                    </h5>
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

                    <div class="mb-4">
                        <label class="text-muted small fw-bold text-uppercase">Deskripsi</label>
                        <p class="mb-0 bg-light p-3 rounded border">{{ $report->description }}</p>
                    </div>

                    @if($report->image_path)
                    <div class="mb-3">
                        <label class="text-muted small fw-bold text-uppercase mb-2">Bukti Foto</label>
                        <div>
                            <img src="{{ asset('storage/'.$report->image_path) }}" 
                                 alt="Bukti Foto Laporan" 
                                 class="img-thumbnail rounded shadow-sm" 
                                 style="max-width: 100%; height: auto; max-height: 400px; object-fit: contain;">
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: SLA & Timeline Riwayat Lengkap --}}
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

            {{-- Card Timeline (Riwayat Log) --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-history text-secondary me-2"></i>Riwayat Timeline</h5>
                </div>
                <div class="card-body">
                    @php
                        $timelineItems = $report->statusHistory->map(function($history) { 
                            return [
                                'status' => $history->status == 'completed' ? 'completed' : ($history->status == 'in_progress' ? 'active' : 'pending'), 
                                'title' => ucfirst($history->status), 
                                'date' => $history->created_at->format('d M Y, H:i'), 
                                'description' => $history->description
                            ]; 
                        })->toArray();
                    @endphp

                    <x-timeline :items="$timelineItems" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* CSS untuk Progress Tracker Horizontal */
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
</style>
@endpush