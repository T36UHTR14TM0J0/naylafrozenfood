@extends('layout.app')
@section('title', 'Edit Stok Item')

@section('content')

<style>
    /* Menyesuaikan Select2 dengan Bootstrap 5 */
    .select2-container--default .select2-selection--single {
        height: calc(2.25rem + 2px);
        padding: 0.375rem 0.75rem;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        font-size: 1rem;
        line-height: 1.5;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.5;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(2.25rem + 2px);
        right: 10px;
        top: 50%;
        margin-top: -10px;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
        height: calc(2.25rem + 2px);
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
    }

    /* Fokus dengan border biru seperti di Bootstrap 5 */
    .select2-container--default .select2-selection--single:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
    }

    .select2-container--default .select2-results__option {
        font-size: 1rem;
    }
</style>

<div class="container">
    <form method="POST" action="{{ route('stok.update', $stok->id) }}">
        @csrf
        @method('PUT')

        <!-- Form input untuk memilih item -->
        <div class="row mb-3">
            <div class="col-md-4 form-group">
                <label for="item_id" class="form-label">Item <span class="text-danger">*</span></label>
                <select id="item_id" name="item_id" class="select2 form-select @error('item_id') is-invalid @enderror">
                    <option value="">Pilih item</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" {{ old('item_id', $stok->item_id) == $item->id ? 'selected' : '' }}>
                            {{ $item->nama }}
                        </option>
                    @endforeach
                </select>
                @error('item_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Form input untuk memilih supplier -->
            <div class="col-md-4 form-group">
                <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                <select id="supplier_id" name="supplier_id" class="select2 form-select @error('supplier_id') is-invalid @enderror">
                    <option value="">Pilih supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $stok->supplier_id) == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->nama }}
                        </option>
                    @endforeach
                </select>
                @error('supplier_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Form input untuk jumlah stok -->
            <div class="col-md-4 form-group">
                <label for="jumlah_stok" class="form-label">Jumlah Stok <span class="text-danger">*</span></label>
                <input type="number" id="jumlah_stok" name="jumlah_stok" class="form-control @error('jumlah_stok') is-invalid @enderror"
                       placeholder="Masukkan jumlah stok item" value="{{ old('jumlah_stok', $stok->jumlah_stok) }}">
                @error('jumlah_stok')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Form input untuk harga -->
            <div class="col-md-4 form-group">
                <label for="harga" class="form-label">Harga <span class="text-danger">*</span></label>
                <input type="text" id="harga_display" name="harga_display" class="form-control @error('harga') is-invalid @enderror"
                       placeholder="Masukkan harga" 
                       value="{{ old('harga', $stok->harga) ? 'Rp ' . number_format(old('harga', $stok->harga), 0, ',', '.') : '' }}"
                       onkeyup="formatRupiah(this, 'harga_hidden')">
                <input type="hidden" id="harga_hidden" name="harga" value="{{ old('harga', $stok->harga) }}">
                @error('harga')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

        </div>

        <!-- Tombol Kembali dan Simpan -->
        <div class="mt-4">
            <a href="{{ route('stok.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    // Fungsi untuk memformat input menjadi Rupiah
    function formatRupiah(element, targetId) {
        let value = element.value.replace(/\D/g, ''); // Menghapus semua karakter non-numerik
        
        let formattedValue = '';
        if (value.length > 0) {
            formattedValue = 'Rp ' + new Intl.NumberFormat('id-ID').format(value); // Format dengan ID
        }
        
        element.value = formattedValue; // Update nilai display
        document.getElementById(targetId).value = value; // Update nilai asli pada hidden input
    }

    // Menangani form submit untuk memastikan nilai harga yang benar
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function() {
                const hargaBeliDisplay = document.getElementById('harga_display');
                if (hargaBeliDisplay) {
                    const value = hargaBeliDisplay.value.replace(/\D/g, ''); // Ambil nilai numerik
                    document.getElementById('harga_hidden').value = value; // Update hidden input
                }
            });
        }
    });
</script>
@endpush