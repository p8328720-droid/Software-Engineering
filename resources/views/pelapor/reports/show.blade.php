@extends('layouts.dashboard')

@section('title', 'Detail Laporan')

@section('dashboard-content')
<div class="container-fluid px-0">

    {{-- PROGRESS TRACKER --}}
    <x-report-progress :status="$report->status" />

    {{-- KONTEN DETAIL BAWAH (2 KOLOM) --}}
    <div class="row">
        {{-- Kolom Kiri: Detail Utama Laporan --}}
        <div class="col-md-8">
            <x-report-info-card :report="$report" />
        </div>

        {{-- Kolom Kanan: SLA & Timeline Riwayat Lengkap --}}
        <div class="col-md-4">
            <x-report-status-card :report="$report" />
            <x-report-audit-log :logs="$report->statusHistory" />
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .progress-tracker { display: flex; justify-content: space-between; margin: 20px 0; position: relative; }
    .progress-tracker::before { content: ''; position: absolute; top: 15px; left: 10%; right: 10%; height: 3px; background: #e0e0e0; z-index: 1; }
    .step { text-align: center; position: relative; z-index: 2; flex: 1; }
    .step.completed .step-label { color: #28a745; font-weight: bold; }
    .step.active .step-label { color: #FF6B35; font-weight: bold; }
    .step-label { font-weight: 600; font-size: 14px; color: #333; margin-bottom: 5px; }
    .step-date { font-size: 12px; color: #6c757d; }
</style>
@endpush