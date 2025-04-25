<style>
  .az-dashboard-header {
    background-color: #f8f9fa; /* Warna latar belakang yang lebih terang */
    padding: 20px; /* Padding untuk memberikan ruang di sekitar konten */
    border-bottom: 1px solid #e0e0e0; /* Garis bawah untuk pemisahan */
    width: 100%;
}

.az-dashboard-title {
    font-size: 24px; /* Ukuran font yang lebih besar untuk judul */
    font-weight: bold; /* Menebalkan teks judul */
    color: #333; /* Warna teks judul */
}

.az-dashboard-text {
    font-size: 14px; /* Ukuran font untuk teks deskripsi */
    color: #666; /* Warna teks deskripsi */
}
</style>

<div class="az-dashboard-header">
  <div class="d-flex justify-content-between align-items-center">
      <div>
          <h2 class="az-dashboard-title">@yield('title')</h2>
          <p class="az-dashboard-text">Your web analytics dashboard template.</p>
      </div>
      <div class="az-content-header-right">
          <a href="#" class="btn btn-purple">Export</a>
      </div>
  </div>
</div>