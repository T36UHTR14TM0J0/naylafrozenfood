<!-- resources/views/kategori/index.blade.php -->
@extends('layout.app')
@section('title', 'Data Kategori')
@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <a href="{{ route('kategori.create') }}" class="btn btn-sm btn-primary">
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
            <form method="GET" action="{{ route('kategori.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nama" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                   value="{{ request('nama') }}" placeholder="Filter berdasarkan nama kategori">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-filter"></i> Cari
                            </button>
                            <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary">
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
                            <th class="text-white text-center">Nama Kategori</th>
                            <th class="text-white text-center">Deskripsi</th>
                            <th class="text-white text-center">Tanggal Buat</th>
                            <th class="text-white text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no= 1;?>
                        @forelse ($kategori as $row)
                        <tr>
                            <td class="text-center"><?= $no++;?></td>
                            <td>{{ $row->nama }}</td>
                            <td class="{{ (empty($row->desc) ? 'text-center' : '') }}">{{ (empty($row->desc) ? '-' : $row->desc) }}</td>
                            <td>{{ $row->created_at->locale('id')->translatedFormat('d F Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('kategori.edit',$row->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <button class="btn btn-sm btn-danger" title="Delete" onclick="confirmDelete('{{ $row->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $row->id }}" action="{{ route('kategori.destroy', $row->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Menampilkan {{ $kategori->firstItem() }} sampai {{ $kategori->lastItem() }} dari {{ $kategori->total() }} entri
                </div>
                <div>
                    {{ $kategori->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
