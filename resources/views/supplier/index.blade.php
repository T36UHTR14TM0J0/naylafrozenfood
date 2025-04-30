<!-- resources/views/user/user.blade.php -->

@extends('layout.app') <!-- Perhatikan penulisan 'layouts' (biasanya plural) -->
@section('title', 'Data Supplier') <!-- Titik koma dihapus setelah string -->

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <a href="{{ route('supplier.create') }}" class="btn btn-sm btn-primary">
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
            <form method="GET" action="{{ route('supplier.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nama" class="form-label">Nama Supplier</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                   value="{{ request('nama') }}" placeholder="Filter berdasarkan nama supplier">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-filter"></i> Cari
                            </button>
                            <a href="{{ route('supplier.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-sync-alt"></i> Reset
                            </a>
                        </div>
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
                            <th class="text-white text-center">Nama Supplier</th>
                            <th class="text-white text-center">Alamat</th>
                            <th class="text-white text-center">No HP</th>
                            <th class="text-white text-center">Deskripsi</th>
                            <th class="text-white text-center">Status</th>
                            <th class="text-white text-center">Tanggal Buat</th>
                            <th class="text-white text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no= 1;?>
                        @forelse ($suppliers as $row)
                        <tr>
                            <td class="text-center"><?= $no++;?></td>
                            <td>{{ $row->nama }}</td>
                            <td>{{ $row->alamat }}</td>
                            <td>{{ $row->no_hp }}</td>
                            <td class="{{ (empty($row->desc) ? 'text-center' : '') }}">{{ (empty($row->desc) ? '-' : $row->desc) }}</td>
                            <td>{{ $row->status }}</td>
                            <td>{{ $row->created_at->translatedFormat('d F Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('supplier.edit',$row->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <button class="btn btn-sm btn-danger" title="Delete" onclick="confirmDelete('{{ $row->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $row->id }}" action="{{ route('supplier.destroy', $row->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Menampilkan {{ $suppliers->firstItem() }} sampai {{ $suppliers->lastItem() }} dari {{ $suppliers->total() }} entri
                </div>
                <div>
                    {{ $suppliers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
