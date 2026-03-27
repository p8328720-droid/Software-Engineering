@props(['status' => 'pending', 'showLabel' => true, 'size' => 'md'])

@php
$statuses = [
    'pending' => ['color' => '#6c757d', 'bg' => '#e9ecef', 'icon' => 'clock', 'label' => 'Menunggu'],
    'verified' => ['color' => '#17a2b8', 'bg' => '#d1ecf1', 'icon' => 'check-circle', 'label' => 'Diverifikasi'],
    'in_progress' => ['color' => '#ffc107', 'bg' => '#fff3cd', 'icon' => 'spinner', 'label' => 'Diproses'],
    'completed' => ['color' => '#28a745', 'bg' => '#d4edda', 'icon' => 'check-double', 'label' => 'Selesai'],
    'rejected' => ['color' => '#dc3545', 'bg' => '#f8d7da', 'icon' => 'times-circle', 'label' => 'Ditolak'],
    'assigned' => ['color' => '#FF6B35', 'bg' => '#FFF4E6', 'icon' => 'user-check', 'label' => 'Ditugaskan'],
];
$sizes = ['sm' => 'padding:0.25rem 0.75rem;font-size:0.75rem;', 'md' => 'padding:0.5rem 1rem;font-size:0.875rem;', 'lg' => 'padding:0.75rem 1.25rem;font-size:1rem;'];
$info = $statuses[$status] ?? $statuses['pending'];
$sizeStyle = $sizes[$size] ?? $sizes['md'];
$spin = $status == 'in_progress' ? 'fa-spin' : '';
@endphp

<div class="d-inline-flex align-items-center status-badge" style="background:{{ $info['bg'] }};color:{{ $info['color'] }};border-radius:50px;{{ $sizeStyle }};font-weight:500">
    <i class="fas fa-{{ $info['icon'] }} me-1 {{ $spin }}"></i>@if($showLabel)<span>{{ $info['label'] }}</span>@endif
</div>