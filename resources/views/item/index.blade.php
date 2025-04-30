<!-- resources/views/user/user.blade.php -->

@extends('layout.app') <!-- Perhatikan penulisan 'layouts' (biasanya plural) -->
@section('title', 'Data Item') <!-- Titik koma dihapus setelah string -->

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12 ">
            {{-- <h4 class="mb-0"><i class="fas fa-boxes me-2"></i>Data Item</h4> --}}
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
                    <div class="col-md-3">
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
        <div class="card-body">
            <div class="table-responsive">
                <table class="table  table-striped table-hover table-bordered">
                    <thead class="bg-primary">
                        <tr>
                            <th width="5%" class="text-white text-center">No</th>
                            <th width="10%" class="text-white text-center">Gambar</th>
                            <th class="text-white text-center">Nama Item</th>
                            <th class="text-white text-center">Harga</th>
                            <th class="text-white text-center">Kategori</th>
                            <th class="text-white text-center">Satuan</th>
                            <th class="text-white text-center">Tanggal Dibuat</th>
                            <th width="15%" class="text-white text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $index => $item)
                        <tr>
                            <td class="text-center">{{ $items->firstItem() + $index }}</td>
                            <td>
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
                            <td>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                            <td>{{ $item->kategori->nama ?? '-' }}</td>
                            <td>{{ $item->satuan->nama ?? '-' }}</td>
                            <td>{{ $item->created_at->translatedFormat('d F Y') }}</td>
                            <td class="text-center">
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
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">Tidak ada data item ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($items->hasPages())
            <div class=" d-flex justify-content-between align-items-center mt-3">
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

@endsection

