@extends('layouts.dashboard')

@section('title', 'Detail Laporan')

@section('dashboard-content')
    <div class="container-fluid px-0">

        {{-- PROGRESS TRACKER --}}
        @if(Auth::user()->role === 'pelapor' || Auth::user()->role === 'teknisi')
            <x-report-progress :status="$report->status" />
        @endif

        <div class="row">
            {{-- KOLOM KIRI: DETAIL LAPORAN --}}
            <div class="col-md-8">
                <x-report-info-card :report="$report" />
            </div>

            {{-- KOLOM KANAN: STATUS, RIWAYAT & AKSI --}}
            <div class="col-md-4">
                <x-report-status-card :report="$report" />
                <x-report-audit-log :logs="$report->auditLogs" />
                <x-report-admin-actions :report="$report" :technicians="$technicians" />
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