<!-- resources/views/kategori/create.blade.php -->
@extends('layout.app')
@section('title', 'Data Users')
@section('content')
<div class="container">
    <div class="form-group mb-4">
        <div class="col-md-12">
            <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Tambah Data
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i>Filter Data</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('user.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name"
                               value="{{ request('name') }}" placeholder="Filter berdasarkan nama lengkap">
                    </div>
                    <div class="col-md-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email"
                               value="{{ request('email') }}" placeholder="Filter berdasarkan email">
                    </div>
                    <div class="col-md-4">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="owner" {{ request('role') == 'owner' ? 'selected' : '' }}>Owner </option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter"></i> Cari
                        </button>
                        <a href="{{ route('user.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-sync-alt"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-white text-center">No</th>
                            <th class="text-white text-center">Nama Lengkap</th>
                            <th class="text-white text-center">Email</th>
                            <th class="text-white text-center">Role</th>
                            <th class="text-white text-center">Tanggal Buat</th>
                            <th class="text-white text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no= 1;?>
                        @forelse ($users as $user)
                        <tr>
                            <td class="text-center"><?= $no++;?></td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'primary' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->locale('id')->translatedFormat('d F Y') }}</td>
                            <td>
                                <a href="{{ route('user.show',$user->id) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('user.edit',$user->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" title="Delete" onclick="confirmDelete('{{ $user->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $user->id }}" action="{{ route('user.destroy', $user->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Menampilkan {{ $users->firstItem() }} sampai {{ $users->lastItem() }} dari {{ $users->total() }} entri
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
