@props(['report', 'technicians'])

@if(Auth::user()->role === 'admin')
    <div class="card border-0 shadow-sm mb-4 bg-light border-start border-4 border-danger">
        <div class="card-body">
            <h6 class="fw-bold mb-3 small">KONTROL ADMIN</h6>

            @if($report->status !== 'completed' && $report->status !== 'rejected')
                <button type="button" class="btn btn-primary btn-sm w-100 shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#assignTechnicianModal">
                    {{ $report->status === 'pending' ? 'Verifikasi & Tugaskan' : 'Alihkan Teknisi' }}
                </button>
            @else
                <p class="text-muted small mb-0 text-center">Laporan sudah bersifat final.</p>
            @endif
        </div>
    </div>

    {{-- MODAL PENUGASAN TEKNISI --}}
    <div class="modal fade" id="assignTechnicianModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0">
                <form action="{{ route('admin.reports.assign', $report->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header bg-primary text-white border-0">
                        <h5 class="modal-title">Penugasan Teknisi</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Teknisi</label>
                            <select class="form-select" name="assignee_id" required>
                                <option value="">-- Pilih Teknisi --</option>
                                @foreach($technicians as $technician)
                                    <option value="{{ $technician->id }}" {{ ($report->assignment?->technician_id == $technician->id) ? 'selected' : '' }}>
                                        {{ $technician->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">Catatan Instruksi</label>
                            <textarea class="form-control" name="notes" rows="3"
                                placeholder="Berikan instruksi khusus untuk teknisi..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Simpan Penugasan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
