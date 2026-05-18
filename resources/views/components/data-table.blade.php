@props(['thead', 'title' => null])

<div class="card border-0 shadow-sm">
    @if($title || isset($header))
        <div class="card-header bg-white py-3 border-0">
            @if(isset($header))
                {{ $header }}
            @else
                <h6 class="mb-0 fw-bold small-caps">{{ $title }}</h6>
            @endif
        </div>
    @endif
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        {{ $thead }}
                    </tr>
                </thead>
                <tbody>
                    {{ $slot }}
                </tbody>
            </table>
        </div>
    </div>
</div>
