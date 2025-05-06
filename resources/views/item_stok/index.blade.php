<!-- resources/views/user/user.blade.php -->

@extends('layout.app') <!-- Perhatikan penulisan 'layouts' (biasanya plural) -->
@section('title', 'Data Stok Item') <!-- Titik koma dihapus setelah string -->

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12 ">
            <a href="{{ route('stok.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Stok Item
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i>Filter Data</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('stok.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="nama" class="form-label">Nama Supplier</label>
                        <input type="text" class="form-control" id="nama" name="nama"
                               value="{{ request('nama') }}" placeholder="Filter berdasarkan nama supplier">
                    </div>
                    {{-- <div class="col-md-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select class="form-select" id="kategori" name="kategori_id">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i> Filter
                        </button>
                        <a href="{{ route('stok.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Items Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table  table-striped table-hover table-bordered">
                    <thead class="bg-primary">
                        <tr>
                            <th width="5%" class="text-white text-center">No</th>
                            <th class="text-white text-center">Nama Item</th>
                            <th class="text-white text-center">Nama Supplier</th>
                            <th  width="5%" class="text-white text-center">Jumlah Stok</th>
                            <th  width="5%" class="text-white text-center">Status</th>
                            <th class="text-white text-center">Tanggal</th>
                            <th width="5%" class="text-white text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productStocks as $index => $productStockstok)
                        <tr>
                            <td class="text-center">{{ $productStocks->firstItem() + $index }}</td>
                            <td>{{ $productStockstok->item->nama }}</td>
                            <td>{{ $productStockstok->supplier->nama ?? '-' }}</td>
                            <td class="text-center">{{ $productStockstok->jumlah_stok ?? '-' }}</td>
                            <td class="text-center">{{ $productStockstok->status ?? '' }}</td>
                            <td class="text-center">{{ $productStockstok->created_at->locale('id')->translatedFormat('d F Y') }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-danger" title="Hapus"
                                        onclick="confirmDelete('{{ $productStockstok->id }}', '{{ $productStockstok->nama }}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <form id="delete-form-{{ $productStockstok->id }}" action="{{ route('stok.destroy', $productStockstok->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">Tidak ada data stok item ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($productStocks->hasPages())
            <div class=" d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $productStocks->firstItem() }} sampai {{ $productStocks->lastItem() }} dari {{ $productStocks->total() }} entri
                </div>
                <div>
                    {{ $productStocks->withQueryString()->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

