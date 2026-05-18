@extends('layouts.dashboard')

@section('title', 'Kelola Users')

@section('dashboard-content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between flex-wrap align-items-center mb-4">
        <h4 class="fw-bold"><i class="fas fa-users text-orange me-2"></i>Kelola Users</h4>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary fw-bold shadow-sm">Tambah User</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <x-data-table title="Daftar User">
                <x-slot:thead>
                    <th class="ps-3 py-3 small-caps">#</th>
                    <th class="py-3 small-caps">Nama</th>
                    <th class="py-3 small-caps">Email</th>
                    <th class="py-3 small-caps">NIM</th>
                    <th class="py-3 small-caps">Role</th>
                    <th class="py-3 small-caps">Status</th>
                    <th class="py-3 small-caps">Tgl Daftar</th>
                    <th class="py-3 small-caps text-center">Aksi</th>
                </x-slot:thead>
                <tbody>
                    @forelse($users as $index => $user)
                    <tr>
                        <td class="ps-3 small">{{ $users->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $user->avatar_url }}" class="rounded-circle me-2" width="32" height="32">
                                <span class="fw-bold small">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="small">{{ $user->email }}</td>
                        <td class="small">{{ $user->student_id ?? '-' }}</td>
                        <td>
                            @if($user->role == 'admin')<span class="badge bg-danger" style="font-size: 10px;">ADMIN</span>
                            @elseif($user->role == 'teknisi')<span class="badge bg-info" style="font-size: 10px;">TEKNISI</span>
                            @elseif($user->role == 'supervisor')<span class="badge bg-warning" style="font-size: 10px;">SUPERVISOR</span>
                            @else<span class="badge bg-success" style="font-size: 10px;">PELAPOR</span>@endif
                        </td>
                        <td>@if($user->email_verified_at)<span class="badge bg-success" style="font-size: 10px;">AKTIF</span>@else<span class="badge bg-secondary" style="font-size: 10px;">PENDING</span>@endif</td>
                        <td class="small">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-light border p-1 px-2">Edit</a>
                            @if($user->id != auth()->id())
                            <button type="button" class="btn btn-sm btn-light border p-1 px-2 text-danger" onclick="deleteUser({{ $user->id }})">Hapus</button>
                            <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: none;">@csrf @method('DELETE')</form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 small text-muted">Belum ada user</td></tr>
                    @endforelse
                </tbody>
            </x-data-table>
        <div class="mt-3">{{ $users->links() }}</div>
</div>
@endsection

@push('scripts')
<script>
function deleteUser(id) {
    Swal.fire({ title: 'Hapus User?', text: "User akan dihapus permanen!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal' }).then((result) => { if (result.isConfirmed) document.getElementById('delete-form-' + id).submit(); });
}
</script>
@endpush