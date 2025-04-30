@extends('layout.app')
@section('title', 'Edit Data Item')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('item.update', $item->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Nama Item -->
        <div class="row mb-3">
            <div class="col-md-6 form-group">
                <label for="nama" class="form-label">Nama Item <span class="text-danger">*</span></label>
                <input type="text" id="nama" name="nama"
                        class="form-control @error('nama') is-invalid @enderror"
                        placeholder="Masukkan nama item"
                        value="{{ old('nama', $item->nama) }}"
                        required>
                @error('nama')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-6 form-group">
                <label for="harga_jual" class="form-label">Harga Jual <span class="text-danger">*</span></label>
                <input type="number" id="harga_jual" name="harga_jual"
                        class="form-control @error('harga_jual') is-invalid @enderror"
                        placeholder="Masukkan harga jual"
                        value="{{ old('harga_jual', $item->harga_jual) }}"
                        min="0"
                        required>
                @error('harga_jual')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Kategori dan Satuan -->
        <div class="row mb-3">
            <div class="col-md-6 form-group">
                <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                <select id="kategori_id" name="kategori_id"
                        class="form-select @error('kategori_id') is-invalid @enderror"
                        required>
                    <option value="">Pilih Kategori</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('kategori_id', $item->kategori_id) == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama }}
                        </option>
                    @endforeach
                </select>
                @error('kategori_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-6 form-group">
                <label for="satuan_id" class="form-label">Satuan <span class="text-danger">*</span></label>
                <select id="satuan_id" name="satuan_id"
                        class="form-select @error('satuan_id') is-invalid @enderror"
                        required>
                    <option value="">Pilih Satuan</option>
                    @foreach($satuans as $satuan)
                        <option value="{{ $satuan->id }}" {{ old('satuan_id', $item->satuan_id) == $satuan->id ? 'selected' : '' }}>
                            {{ $satuan->nama }}
                        </option>
                    @endforeach
                </select>
                @error('satuan_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Gambar -->
        <div class="mb-3">
            <label for="gambar" class="form-label">Gambar Item</label>

            <!-- Tampilkan gambar saat ini jika ada -->
            @if($item->gambar)
            <div class="mb-2">
                <img src="{{ asset('storage/items/'.$item->gambar) }}" alt="Gambar Item" class="img-thumbnail" style="max-height: 150px;">
            </div>
            @endif

            <input type="file" id="gambar" name="gambar"
                    class="form-control @error('gambar') is-invalid @enderror"
                    accept="image/jpeg, image/png, image/jpg">
            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar. Format: JPEG, PNG, JPG (Maks. 2MB)</small>
            @error('gambar')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
            Simpan Perubahan
            </button>
            <a href="{{ route('item.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection
