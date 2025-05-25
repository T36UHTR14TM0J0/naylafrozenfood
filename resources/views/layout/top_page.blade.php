<style>
  /* Gaya untuk bagian header dashboard */
  .az-dashboard-header {
    background-color: #f8f9fa; /* Warna latar belakang yang lebih terang */
    padding: 20px; /* Padding untuk memberikan ruang di sekitar konten */
    border-bottom: 1px solid #e0e0e0; /* Garis bawah untuk pemisahan */
    width: 100%;  /* Mengatur lebar header agar memenuhi 100% lebar container */
  }

  /* Gaya untuk judul dashboard */
  .az-dashboard-title {
    font-size: 24px; /* Ukuran font yang lebih besar untuk judul */
    font-weight: bold; /* Menebalkan teks judul */
    color: #333; /* Warna teks judul */
  }

  /* Gaya untuk teks deskripsi dashboard */
  .az-dashboard-text {
    font-size: 14px; /* Ukuran font untuk teks deskripsi */
    color: #666; /* Warna teks deskripsi */
  }
</style>

<!-- Header dashboard -->
<div class="az-dashboard-header">
  <div class="d-flex justify-content-between align-items-center">
    <!-- Bagian kiri header untuk menampilkan judul -->
    <div>
      <h2 class="az-dashboard-title">@yield('title')</h2> <!-- Menampilkan judul dinamis menggunakan @yield -->
    </div>
    {{-- 
    <div class="az-content-header-right">
      <a href="#" class="btn btn-purple">Export</a>  <!-- Tombol untuk export (opsional) -->
    </div> 
    --}}
  </div>
</div>
