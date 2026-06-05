@props(['report', 'commentUrl', 'currentUserId'])

<div class="card border-0 mt-3">
    <div class="card-header bg-white d-flex align-items-center gap-2">
        <i class="fas fa-comments text-orange"></i>
        <h5 class="mb-0">Komentar & Tanggapan</h5>
        <span class="badge bg-secondary ms-auto">{{ $report->comments->count() }}</span>
    </div>
    <div class="card-body p-0">

        {{-- Area Chat --}}
        <div id="chatBox" style="height:380px; overflow-y:auto; padding:1rem; background:#f5f5f5; display:flex; flex-direction:column; gap:10px;">
            @forelse($report->comments as $comment)
                @php $isMine = $comment->user_id === $currentUserId; @endphp
                <div class="d-flex {{ $isMine ? 'justify-content-end' : 'justify-content-start' }}" id="comment-{{ $comment->id }}">
                    <div style="max-width:70%;">

                        {{-- Nama + badge role — hanya pesan orang lain --}}
                        @unless($isMine)
                            @php
                                $roleColor = match($comment->user_type) {
                                    'admin'   => 'danger',
                                    'teknisi' => 'warning',
                                    default   => 'primary',
                                };
                                $roleLabel = match($comment->user_type) {
                                    'admin'   => 'Admin',
                                    'teknisi' => 'Teknisi',
                                    default   => 'Mahasiswa',
                                };
                            @endphp
                            <div class="d-flex align-items-center gap-1 mb-1 ms-1">
                                <span class="fw-semibold" style="font-size:12px;">{{ $comment->user->name }}</span>
                                <span class="badge bg-{{ $roleColor }}" style="font-size:10px;">{{ $roleLabel }}</span>
                            </div>
                        @endunless

                        {{-- Bubble --}}
                        <div class="px-3 py-2 shadow-sm"
                            style="background: {{ $isMine ? '#FF6B35' : '#ffffff' }};
                                   color: {{ $isMine ? '#ffffff' : '#212529' }};
                                   border-radius: {{ $isMine ? '18px 18px 4px 18px' : '18px 18px 18px 4px' }};">
                            <p class="mb-0" style="font-size:14px; line-height:1.5; white-space:pre-wrap;">{{ $comment->comment }}</p>
                        </div>

                        {{-- Waktu --}}
                        <div class="mt-1 {{ $isMine ? 'text-end me-1' : 'ms-1' }}">
                            <small class="text-muted" style="font-size:11px;">{{ $comment->created_at->format('d M Y, H:i') }}</small>
                        </div>

                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5" id="noComments">
                    <i class="fas fa-comment-slash fa-2x mb-2 d-block"></i>
                    <small>Belum ada komentar</small>
                </div>
            @endforelse
        </div>

        {{-- Input --}}
        <div class="p-3 border-top bg-white d-flex gap-2 align-items-end">
            <textarea id="newComment" class="form-control" rows="1"
                placeholder="Tulis pesan..."
                style="resize:none; border-radius:20px; padding:8px 14px;"
                onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendComment();}"></textarea>
            <button class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                    style="width:40px;height:40px;"
                    onclick="sendComment()">
                <i class="fas fa-paper-plane" style="font-size:13px;"></i>
            </button>
        </div>

    </div>
</div>

@push('scripts')
<script>
(function () {
    const COMMENT_URL  = "{{ $commentUrl }}";
    const CSRF_TOKEN   = "{{ csrf_token() }}";
    const chatBox      = document.getElementById('chatBox');

    // Scroll ke pesan terbaru saat halaman load
    chatBox.scrollTop = chatBox.scrollHeight;

    window.sendComment = function () {
        const textarea = document.getElementById('newComment');
        const text = textarea.value.trim();
        if (!text) return;

        fetch(COMMENT_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            body: JSON.stringify({ comment: text })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) { alert(data.message); return; }

            const noComments = document.getElementById('noComments');
            if (noComments) noComments.remove();

            const c = data.comment;
            chatBox.insertAdjacentHTML('beforeend', `
                <div class="d-flex justify-content-end" id="comment-${c.id}">
                    <div style="max-width:70%;">
                        <div class="px-3 py-2 shadow-sm"
                             style="background:#FF6B35;color:#fff;border-radius:18px 18px 4px 18px;">
                            <p class="mb-0" style="font-size:14px;line-height:1.5;white-space:pre-wrap;">${c.comment}</p>
                        </div>
                        <div class="mt-1 text-end me-1">
                            <small class="text-muted" style="font-size:11px;">${c.created_at}</small>
                        </div>
                    </div>
                </div>
            `);

            chatBox.scrollTop = chatBox.scrollHeight;
            textarea.value = '';
        })
        .catch(() => alert('Gagal mengirim pesan.'));
    };
})();
</script>
@endpush