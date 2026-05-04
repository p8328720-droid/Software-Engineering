@extends('layouts.dashboard')

@section('title', 'Kelola Users')

@section('dashboard-content')
<div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-users me-2 text-orange"></i>Kelola Users</h1>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Tambah User</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="table-light">
                        <th>#</th><th>Nama</th><th>Email</th><th>NIM</th><th>Role</th><th>Status</th><th>Tanggal Daftar</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    <tr>
                        <td>{{ $users->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $user->avatar_url }}" class="rounded-circle me-2" width="32" height="32">
                                {{ $user->name }}
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->student_id ?? '-' }}</td>
                        <td>
                            @if($user->role == 'admin')<span class="badge bg-danger">Admin</span>
                            @elseif($user->role == 'teknisi')<span class="badge bg-info">Teknisi</span>
                            @elseif($user->role == 'supervisor')<span class="badge bg-warning">Supervisor</span>
                            @else<span class="badge bg-success">Mahasiswa</span>@endif
                        </td>
                        <td>@if($user->email_verified_at)<span class="badge bg-success">Aktif</span>@else<span class="badge bg-secondary">Belum Verifikasi</span>@endif</td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                            @if($user->id != auth()->id())
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteUser({{ $user->id }})"><i class="fas fa-trash"></i></button>
                            <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: none;">@csrf @method('DELETE')</form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-5">Belum ada user</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $users->links() }}</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteUser(id) {
    Swal.fire({ title: 'Hapus User?', text: "User akan dihapus permanen!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal' }).then((result) => { if (result.isConfirmed) document.getElementById('delete-form-' + id).submit(); });
}
</script>
@endpush