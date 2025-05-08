@extends('layout.app')
@section('title', 'Tambah Stok Item')

@section('content')
<style>
    /* Menyesuaikan Select2 dengan Bootstrap 5 */
    .select2-container--default .select2-selection--single {
        height: calc(2.25rem + 2px); /* Sama dengan tinggi input di Bootstrap 5 */
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
    <form method="POST" action="{{ route('stok.store') }}">
        @csrf

        <!-- Nama Item -->
        <div class="row mb-3">
            
            <div class="col-md-4 form-group">
                <label for="item_id" class="form-label">Item <span class="text-danger">*</span></label>
                <select id="item_id" name="item_id"
                        class="select2 form-select  @error('item_id') is-invalid @enderror"
                        >
                    <option value="">Pilih item</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
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
            <div class="col-md-4 form-group">
                <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                <select id="supplier_id" name="supplier_id"
                        class="select2 form-select @error('supplier_id') is-invalid @enderror"
                        >
                    <option value="">Pilih supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
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

            <div class="col-md-4 form-group">
                <label for="jumlah_stok" class="form-label">Jumlah Stok <span class="text-danger">*</span></label>
                <input type="number" id="jumlah_stok" name="jumlah_stok"
                        class="form-control @error('jumlah_stok') is-invalid @enderror"
                        placeholder="Masukkan jumlah stok item"
                        value="{{ old('jumlah_stok') }}"
                        >
                @error('jumlah_stok')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

        </div>


        <div class="mt-4">
            <a href="{{ route('stok.index') }}" class="btn btn-secondary">
                Kembali
            </a>
            <button type="submit" class="btn btn-primary">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
