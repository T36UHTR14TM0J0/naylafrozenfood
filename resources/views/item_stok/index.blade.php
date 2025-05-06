@extends('layout.app') <!-- Memperbaiki referensi layout (plural 'layouts') -->
@section('title', 'Data Stok Item')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#selectItemsModal">
                <i class="fas fa-list me-1"></i> Pilih Item
            </button>
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
                <table class="table table-striped table-hover table-bordered">
                    <thead class="bg-primary">
                        <tr>
                            <th width="5%" class="text-white text-center">No</th>
                            <th class="text-white text-center">Nama Item</th>
                            <th class="text-white text-center">Nama Supplier</th>
                            <th class="text-white text-center">Jumlah Stok</th>
                            <th class="text-white text-center">Tanggal Diterima</th>
                            <th class="text-white text-center">Tanggal Dibuat</th>
                            <th width="15%" class="text-white text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productStocks as $index => $productStock)
                        <tr>
                            <td class="text-center">{{ $productStocks->firstItem() + $index }}</td>
                            <td>{{ $productStock->item->nama }}</td>
                            <td>{{ $productStock->supplier->nama ?? '-' }}</td>
                            <td>{{ $productStock->jumlah_stok ?? '-' }}</td>
                            <td>{{ $productStock->tanggal_diterima->translatedFormat('d F Y') }}</td>
                            <td>{{ $productStock->created_at->translatedFormat('d F Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('stok.edit', $productStock->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" title="Hapus"
                                        onclick="confirmDelete('{{ $productStock->id }}', '{{ $productStock->item->nama }}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <form id="delete-form-{{ $productStock->id }}" action="{{ route('stok.destroy', $productStock->id) }}" method="POST" class="d-none">
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
            <div class="d-flex justify-content-between align-items-center mt-3">
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

<!-- Modal Pilih Items -->
<div class="modal fade" id="selectItemsModal" tabindex="-1" aria-labelledby="selectItemsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-light" id="selectItemsModalLabel">Pilih Item</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Filter untuk pencarian items -->
                <div class="row mb-3">
                    <div class="col-md-4 mb-2">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="itemSearch" class="form-control" placeholder="Cari item...">
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <select class="form-select" id="categoryFilter">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Tabel items -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Nama Item</th>
                                <th>Kategori</th>
                                <th>Stok Tersedia</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="itemsTableBody">
                            @foreach($items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->kategori->nama ?? '-' }}</td>
                                <td>{{ $item->jumlah_stok ?? 0 }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary select-item" 
                                            data-id="{{ $item->id }}"
                                            data-name="{{ $item->nama }}"
                                            data-stock="{{ $item->jumlah_stok }}">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="confirmSelection">Tambah Stok</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Variabel untuk menyimpan item yang dipilih
        let selectedItems = [];
        
        // Filter pencarian
        $('#itemSearch').on('keyup', function() {
            const value = $(this).val().toLowerCase();
            $('#itemsTableBody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        
        // Filter kategori
        $('#categoryFilter').on('change', function() {
            const categoryId = $(this).val();
            if (categoryId === '') {
                $('#itemsTableBody tr').show();
            } else {
                $('#itemsTableBody tr').each(function() {
                    const rowCategory = $(this).find('td:eq(2)').text();
                    const categoryName = $('#categoryFilter option[value="'+categoryId+'"]').text();
                    $(this).toggle(rowCategory === categoryName);
                });
            }
        });
        
        // Memilih item
        $(document).on('click', '.select-item', function() {
            const itemId = $(this).data('id');
            const itemName = $(this).data('name');
            const itemStock = $(this).data('stock');
            
            // Cek apakah item sudah dipilih
            const existingIndex = selectedItems.findIndex(item => item.id === itemId);
            
            if (existingIndex >= 0) {
                // Hapus jika sudah dipilih
                selectedItems.splice(existingIndex, 1);
                $(this).removeClass('btn-success').addClass('btn-primary')
                    .html('<i class="fas fa-check"></i>');
            } else {
                // Tambahkan jika belum dipilih
                selectedItems.push({
                    id: itemId,
                    name: itemName,
                    stock: itemStock
                });
                $(this).removeClass('btn-primary').addClass('btn-success')
                    .html('<i class="fas fa-check-circle"></i>');
            }
        });
        
        // Konfirmasi pilihan
        $('#confirmSelection').on('click', function() {
            if (selectedItems.length === 0) {
                alert('Pilih setidaknya satu item');
                return;
            }
            
            // Redirect ke halaman tambah stok dengan parameter items
            const itemIds = selectedItems.map(item => item.id).join(',');
            window.location.href = "{{ route('stok.create') }}?items=" + itemIds;
        });
        
        // Reset saat modal ditutup
        $('#selectItemsModal').on('hidden.bs.modal', function() {
            selectedItems = [];
            $('.select-item').removeClass('btn-success').addClass('btn-primary')
                .html('<i class="fas fa-check"></i>');
        });
    });

    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Stok Item?',
            text: `Anda yakin ingin menghapus stok item "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush