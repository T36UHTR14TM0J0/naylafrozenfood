@extends('layout.app')
@section('title', 'Tambah Data Item')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('item.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Nama Item -->
        <div class="row mb-3">
            <div class="col-md-6 form-group">
                <label for="nama" class="form-label">Nama Item <span class="text-danger">*</span></label>
                <input type="text" id="nama" name="nama"
                       class="form-control @error('nama') is-invalid @enderror"
                       placeholder="Masukkan nama item"
                       value="{{ old('nama') }}"
                       required>
                @error('nama')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-6 form-group">
                <label for="harga_beli" class="form-label">Harga Beli <span class="text-danger">*</span></label>
                <input type="text" id="harga_beli_display" name="harga_beli_display"
                       class="form-control @error('harga_beli') is-invalid @enderror"
                       placeholder="Masukkan harga beli"
                       value="{{ old('harga_beli') ? 'Rp ' . number_format(old('harga_beli'), 0, ',', '.') : '' }}"
                       onkeyup="formatRupiah(this, 'harga_beli')"
                       required>
                <input type="hidden" id="harga_beli" name="harga_beli" value="{{ old('harga_beli') }}">
                @error('harga_beli')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Kategori dan Satuan -->
        <div class="row mb-3">
            <div class="col-md-6 form-group">
                <label for="harga_jual" class="form-label">Harga Jual <span class="text-danger">*</span></label>
                <input type="text" id="harga_jual_display" name="harga_jual_display"
                       class="form-control @error('harga_jual') is-invalid @enderror"
                       placeholder="Masukkan harga jual"
                       value="{{ old('harga_jual') ? 'Rp ' . number_format(old('harga_jual'), 0, ',', '.') : '' }}"
                       onkeyup="formatRupiah(this, 'harga_jual')"
                       required>
                <input type="hidden" id="harga_jual" name="harga_jual" value="{{ old('harga_jual') }}">
                @error('harga_jual')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="col-md-6 form-group">
                <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                <select id="kategori_id" name="kategori_id"
                        class="form-select @error('kategori_id') is-invalid @enderror"
                        required>
                    <option value="">Pilih Kategori</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
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
        </div>

        <!-- Satuan dan Gambar -->
        <div class="row mb-3">
            <div class="col-md-6 form-group">
                <label for="satuan_id" class="form-label">Satuan <span class="text-danger">*</span></label>
                <select id="satuan_id" name="satuan_id"
                        class="form-select @error('satuan_id') is-invalid @enderror"
                        required>
                    <option value="">Pilih Satuan</option>
                    @foreach($satuans as $satuan)
                        <option value="{{ $satuan->id }}" {{ old('satuan_id') == $satuan->id ? 'selected' : '' }}>
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
            <div class="col-md-6 form-group">
                <label for="gambar" class="form-label">Gambar Item <span class="text-danger">*</span></label>
                <input type="file" id="gambar" name="gambar"
                       class="form-control @error('gambar') is-invalid @enderror"
                       accept="image/jpeg, image/png, image/jpg"
                       required>
                <small class="text-muted">Format: JPEG, PNG, JPG (Maks. 2MB)</small>
                @error('gambar')
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
            <a href="{{ route('item.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Fungsi untuk memformat input menjadi Rupiah
    function formatRupiah(element, targetId) {
        // Dapatkan nilai input
        let value = element.value.replace(/\D/g, '');
        
        // Format ke Rupiah
        let formattedValue = '';
        if (value.length > 0) {
            formattedValue = new Intl.NumberFormat('id-ID').format(value);
        }
        
        // Update nilai display
        element.value = formattedValue;
        
        // Update nilai asli (hidden input)
        document.getElementById(targetId).value = value;
    }

    // Inisialisasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        // Handle form submission untuk memastikan nilai benar
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function() {
                // Pastikan nilai numeric sudah di-set sebelum submit
                const hargaBeliDisplay = document.getElementById('harga_beli_display');
                if (hargaBeliDisplay) {
                    const value = hargaBeliDisplay.value.replace(/\D/g, '');
                    document.getElementById('harga_beli').value = value;
                }
                
                const hargaJualDisplay = document.getElementById('harga_jual_display');
                if (hargaJualDisplay) {
                    const value = hargaJualDisplay.value.replace(/\D/g, '');
                    document.getElementById('harga_jual').value = value;
                }
            });
        }
    });
</script>
@endpush