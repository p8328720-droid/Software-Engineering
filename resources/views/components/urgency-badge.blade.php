@props(['urgency' => 'medium', 'size' => 'md'])

@php
$urgencies = [
    'low' => ['color' => '#28a745', 'bg' => '#d4edda', 'icon' => 'arrow-down', 'label' => 'Rendah'],
    'medium' => ['color' => '#ffc107', 'bg' => '#fff3cd', 'icon' => 'minus', 'label' => 'Sedang'],
    'high' => ['color' => '#dc3545', 'bg' => '#f8d7da', 'icon' => 'arrow-up', 'label' => 'Tinggi'],
];
$sizes = ['sm' => 'padding:0.2rem 0.6rem;font-size:0.7rem;', 'md' => 'padding:0.4rem 0.8rem;font-size:0.8rem;', 'lg' => 'padding:0.6rem 1rem;font-size:0.9rem;'];
$info = $urgencies[$urgency] ?? $urgencies['medium'];
$sizeStyle = $sizes[$size] ?? $sizes['md'];
@endphp

<div class="d-inline-flex align-items-center" style="background:{{ $info['bg'] }};color:{{ $info['color'] }};border-radius:50px;{{ $sizeStyle }};font-weight:600">
    <i class="fas fa-{{ $info['icon'] }} me-1"></i><span>{{ $info['label'] }}</span>
</div>
