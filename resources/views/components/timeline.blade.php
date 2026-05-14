@props(['items' => [], 'showDates' => true])

<div class="timeline-container" style="position:relative;padding-left:30px">
    @forelse($items as $index => $item)
    @php
        $color = match($item['status'] ?? 'pending') { 'completed' => '#28a745', 'active','current' => '#FF6B35', 'warning' => '#ffc107', 'danger' => '#dc3545', default => '#6c757d' };
        $icon = match($item['status'] ?? 'pending') { 'completed' => 'check', 'active','current' => 'spinner fa-spin', 'warning' => 'exclamation-triangle', 'danger' => 'times-circle', default => 'clock' };
        $isLast = $index === count($items) - 1;
    @endphp
    <div class="timeline-item" style="position:relative;margin-bottom:{{ $isLast ? '0' : '20px' }}">
        <div class="timeline-badge" style="position:absolute;left:-30px;top:0;width:24px;height:24px;background:{{ $color }};border-radius:50%;text-align:center;line-height:24px;color:white"><i class="fas fa-{{ $icon }}" style="font-size:12px"></i></div>
        @if(!$isLast)<div class="timeline-line" style="position:absolute;left:-19px;top:24px;width:2px;height:calc(100% + 4px);background:linear-gradient(180deg,{{ $color }} 0%,#e9ecef 100%)"></div>@endif
        <div class="timeline-content" style="padding-left:10px;padding-bottom:{{ $isLast ? '0' : '8px' }}">
            <div class="d-flex justify-content-between"><h6 class="mb-0 fw-bold" style="color:{{ $color }}">{{ $item['title'] ?? 'Event' }}</h6>@if($showDates && isset($item['date']))<small class="text-muted"><i class="fas fa-calendar-alt me-1"></i>{{ $item['date'] }}</small>@endif</div>
            @if($item['description'] ?? false)<p class="mb-0 mt-1 small text-muted">{{ $item['description'] }}</p>@endif
            @if($item['user'] ?? false)<small class="text-muted d-block mt-1"><i class="fas fa-user-circle me-1"></i>{{ $item['user'] }}</small>@endif
        </div>
    </div>
    @empty
    <div class="text-center py-4"><i class="fas fa-history fa-3x text-muted mb-2 d-block"></i><p class="text-muted mb-0">Belum ada aktivitas</p></div>
    @endforelse
</div>