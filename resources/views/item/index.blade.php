@extends('layout.app') <!-- Fixed layout reference (usually plural) -->

@section('title', 'Data Item')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <a href="{{ route('item.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Item
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i>Filter Data</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('item.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="nama" class="form-label">Nama Item</label>
                        <input type="text" class="form-control" id="nama" name="nama"
                               value="{{ request('nama') }}" placeholder="Filter berdasarkan nama item">
                    </div>
                    <div class="col-md-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select class="form-select" id="kategori" name="kategori_id">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i> Filter
                        </button>
                        <a href="{{ route('item.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Items Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0"> <!-- Added p-0 to remove inner padding -->
            <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <table class="table table-striped table-hover table-bordered mb-0">
                    <thead class="bg-primary">
                        <tr>
                            <th width="5%" class="text-white text-center">No</th>
                            <th width="10%" class="text-white text-center">Gambar</th>
                            <th class="text-white text-center min-width-150">Nama Item</th>
                            <th class="text-white text-center min-width-120">Harga Beli</th>
                            <th class="text-white text-center min-width-120">Harga Jual</th>
                            <th class="text-white text-center min-width-120">Kategori</th>
                            <th class="text-white text-center min-width-100">Stok</th>
                            <th class="text-white text-center min-width-100">Satuan</th>
                            <th class="text-white text-center min-width-150">Tanggal Dibuat</th>
                            <th width="5%" class="text-white text-center min-width-100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $index => $item)
                        <tr>
                            <td class="text-center">{{ $items->firstItem() + $index }}</td>
                            <td class="text-center">
                                @if($item->gambar)
                                    <img src="{{ asset('storage/items/' . $item->gambar) }}" alt="{{ $item->nama }}"
                                         class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center"
                                         style="width: 80px; height: 80px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $item->nama }}</td>
                            <td class="text-nowrap">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                            <td class="text-nowrap">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                            <td>{{ $item->kategori->nama ?? '-' }}</td>
                            <td class="text-center">{{ $item->stokTotal->total_stok ?? '-' }}</td>
                            <td>{{ $item->satuan->nama ?? '-' }}</td>
                            <td class="text-nowrap">{{ $item->created_at->locale('id')->translatedFormat('d F Y') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('item.edit', $item->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger" title="Hapus"
                                            onclick="confirmDelete('{{ $item->id }}', '{{ $item->nama }}')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('item.destroy', $item->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">Tidak ada data item ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($items->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3 px-3"> <!-- Added px-3 for padding -->
                <div class="text-muted">
                    Menampilkan {{ $items->firstItem() }} sampai {{ $items->lastItem() }} dari {{ $items->total() }} entri
                </div>
                <div>
                    {{ $items->withQueryString()->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Add this to your CSS */
    .min-width-100 { min-width: 100px; }
    .min-width-120 { min-width: 120px; }
    .min-width-150 { min-width: 150px; }
    .table-responsive {
        width: 100%;
        margin-bottom: 15px;
        overflow-y: hidden;
        -ms-overflow-style: -ms-autohiding-scrollbar;
        border: 1px solid #ddd;
    }
    .table-responsive > .table {
        margin-bottom: 0;
    }
    .table-responsive > .table > thead > tr > th,
    .table-responsive > .table > tbody > tr > td {
        white-space: nowrap;
    }
</style>

@endsection