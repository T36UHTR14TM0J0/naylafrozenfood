@extends('layout.app') <!-- Menggunakan layout.app untuk struktur halaman -->
@section('title', 'Data Stok Item') <!-- Menetapkan judul halaman -->

@section('content')
<div class="container-fluid">

    <!-- Tombol untuk menambah stok item -->
    <div class="row mb-4">
        <div class="col-md-12">
            <a href="{{ route('stok.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Stok Item
            </a>
        </div>
    </div>

    <!-- Form Filter Data -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i>Filter Data</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('stok.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Awal</label>
                        <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Pilih Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua Kategori</option>
                            <option value="masuk" {{ request('status') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                            <option value="keluar" {{ request('status') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                        </select>
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

    <!-- Tabel Data Stok Item -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="bg-primary">
                        <tr>
                            <th width="5%" class="text-white text-center">No</th>
                            <th class="text-white text-center">Nama Item</th>
                            <th width="20%" class="text-white text-center">Nama Supplier</th>
                            <th width="5%" class="text-white text-center">Jumlah</th>
                            <th width="10%" class="text-white text-center">Harga</th>
                            <th width="5%" class="text-white text-center">Status</th>
                            <th width="20%" class="text-white text-center">Tanggal</th>
                            <th width="5%" class="text-white text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productStocks as $index => $productStock)
                        <tr>
                            <td class="text-center">{{ $productStocks->firstItem() + $index }}</td>
                            <td>{{ $productStock->item->nama }}</td>
                            <td>{{ $productStock->supplier->nama ?? '-' }}</td>
                            <td class="text-center">{{ $productStock->jumlah_stok ?? '-' }}</td>
                            <td class="text-nowrap">Rp {{ number_format($productStock->harga, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $productStock->status == 'masuk' ? 'success' : 'danger' }}">
                                    {{ ucfirst($productStock->status) }}
                                </span>
                            </td>
                            <td class="text-center">{{ $productStock->created_at->locale('id')->translatedFormat('d F Y') }}</td>
                            <td class="text-center">
                                <!-- Button Hapus -->
                                <button class="btn btn-sm btn-danger" title="Hapus"
                                        onclick="confirmDelete('{{ $productStock->id }}', '{{ $productStock->nama }}')">
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
                            <td colspan="8" class="text-center py-4">Tidak ada data stok item ditemukan</td>
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
@push('scripts')

<script>
    // Fungsi untuk format tanggal ke format yang dibutuhkan (YYYY-MM-DD)
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Menambahkan 0 di depan bulan yang kurang dari 10
        const day = String(date.getDate()).padStart(2, '0'); // Menambahkan 0 di depan hari yang kurang dari 10
        return `${year}-${month}-${day}`;
    }

    // Mendapatkan elemen input tanggal
    const tanggalAwal = document.getElementById('tanggal_awal');
    const tanggalAkhir = document.getElementById('tanggal_akhir');

    // Mengatur tanggal awal dan akhir dengan default 30 hari
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date(); // Tanggal hari ini
        const thirtyDaysLater = new Date(today);
        thirtyDaysLater.setDate(today.getDate() + 30); // Menambahkan 30 hari ke tanggal hari ini

        // Menetapkan nilai default untuk tanggal awal dan akhir
        tanggalAwal.value = formatDate(today); // Tanggal awal = hari ini
        tanggalAkhir.value = formatDate(thirtyDaysLater); // Tanggal akhir = 30 hari ke depan
    });

    // Fungsi untuk memvalidasi tanggal akhir tidak lebih kecil dari tanggal awal
    tanggalAwal.addEventListener('change', function() {
        const startDate = new Date(tanggalAwal.value);
        if (startDate > new Date(tanggalAkhir.value) && tanggalAkhir.value !== '') {
            tanggalAkhir.value = '';  // Reset tanggal akhir jika tidak valid
        }
        tanggalAkhir.setAttribute('min', tanggalAwal.value); // Membatasi tanggal akhir agar tidak lebih kecil dari tanggal awal
    });

    // Fungsi untuk memvalidasi tanggal awal tidak lebih besar dari tanggal akhir
    tanggalAkhir.addEventListener('change', function() {
        const endDate = new Date(tanggalAkhir.value);
        if (endDate < new Date(tanggalAwal.value) && tanggalAwal.value !== '') {
            tanggalAwal.value = '';  // Reset tanggal awal jika tidak valid
        }
        tanggalAwal.setAttribute('max', tanggalAkhir.value); // Membatasi tanggal awal agar tidak lebih besar dari tanggal akhir
    });
</script>
@endpush
@endsection
