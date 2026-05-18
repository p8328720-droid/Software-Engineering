@props(['status' => 'pending', 'showLabel' => true, 'size' => 'md'])

@php
$statuses = [
    'pending' => ['color' => '#41464b', 'bg' => '#e2e3e5', 'label' => 'Menunggu'],
    'verified' => ['color' => '#055160', 'bg' => '#cff4fc', 'label' => 'Diverifikasi'],
    'in_progress' => ['color' => '#664d03', 'bg' => '#fff3cd', 'label' => 'Diproses'],
    'completed' => ['color' => '#0f5132', 'bg' => '#d1e7dd', 'label' => 'Selesai'],
    'rejected' => ['color' => '#842029', 'bg' => '#f8d7da', 'label' => 'Ditolak'],
    'assigned' => ['color' => '#993d1f', 'bg' => '#ffdfd4', 'label' => 'Ditugaskan'],
];
$sizes = ['sm' => 'padding:0.25rem 0.75rem;font-size:0.75rem;', 'md' => 'padding:0.5rem 1rem;font-size:0.875rem;', 'lg' => 'padding:0.75rem 1.25rem;font-size:1rem;'];
$info = $statuses[$status] ?? $statuses['pending'];
$sizeStyle = $sizes[$size] ?? $sizes['md'];
@endphp

<div class="d-inline-flex align-items-center status-badge" style="background:{{ $info['bg'] }};color:{{ $info['color'] }};border-radius:50px;{{ $sizeStyle }};font-weight:700; text-transform: uppercase; letter-spacing: 0.5px;">
    @if($showLabel)<span>{{ $info['label'] }}</span>@endif
</div>