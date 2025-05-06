@extends('layout.app')
@section('title', 'Tambah Stok Item')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('stok.store') }}">
        @csrf

        <!-- Nama Item -->
        <div class="row mb-3">
            <div class="col-md-4 form-group">
                <label for="nama" class="form-label">Nama Item <span class="text-danger">*</span></label>
                <input type="text" id="nama" name="nama"
                        class="form-control @error('nama') is-invalid @enderror"
                        placeholder="Masukkan nama item"
                        value="{{ $item['nama'] }}" readonly
                        >
                @error('nama')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-4 form-group">
                <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                <select id="supplier_id" name="supplier_id"
                        class="form-select @error('supplier_id') is-invalid @enderror"
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
            <button type="submit" class="btn btn-primary">
                Simpan
            </button>
            <a href="{{ route('stok.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection
