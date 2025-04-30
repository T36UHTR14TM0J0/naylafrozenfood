<!-- resources/views/user/user.blade.php -->

@extends('layout.app') <!-- Perhatikan penulisan 'layouts' (biasanya plural) -->
@section('title', 'Data Satuan') <!-- Titik koma dihapus setelah string -->

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <a href="{{ route('satuan.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Tambah Data
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('satuan.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nama" class="form-label">Nama Satuan</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                   value="{{ request('nama') }}" placeholder="Filter by nama">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-filter"></i> Cari
                            </button>
                            <a href="{{ route('satuan.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-sync-alt"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-white text-center">No</th>
                            <th class="text-white text-center">Nama Satuan</th>
                            <th class="text-white text-center">Tanggal Buat</th>
                            <th class="text-white text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no= 1;?>
                        @forelse ($satuan as $row)
                        <tr>
                            <td class="text-center"><?= $no++;?></td>
                            <td>{{ $row->nama }}</td>
                            <td>{{ $row->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('satuan.edit',$row->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <button class="btn btn-sm btn-danger" title="Delete" onclick="confirmDelete('{{ $row->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $row->id }}" action="{{ route('satuan.destroy', $row->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Showing {{ $satuan->firstItem() }} to {{ $satuan->lastItem() }} of {{ $satuan->total() }} entries
                </div>
                <div>
                    {{ $satuan->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
