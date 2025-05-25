@include('layout.header')  <!-- Menyertakan file header dari layout -->
@include('layout.sidebar')  <!-- Menyertakan file sidebar dari layout -->
@include('layout.navbar')  <!-- Menyertakan file navbar dari layout -->

<!-- [ Konten Utama ] mulai -->
<div class="pc-container">
  <div class="pc-content">

    <!-- [ Breadcrumb ] mulai (Komentar untuk breadcrumb yang belum aktif) -->
    {{-- 
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h5 class="m-b-10">@yield('title')</h5>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="../dashboard/index.html">Home</a></li>
              <li class="breadcrumb-item"><a href="javascript: void(0)">Other</a></li>
              <li class="breadcrumb-item" aria-current="page">Sample Page</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    --}}
    <!-- [ Breadcrumb ] selesai -->

    <!-- [ Konten Utama ] mulai -->
    <div class="row">
      <!-- [ Sample Page ] mulai -->
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header bg-primary">
            <h2 class="text-light">@yield('title')</h2>  <!-- Menampilkan judul halaman yang akan diatur di section 'title' -->
          </div>
          <div class="card-body">
            @yield('content')  <!-- Menampilkan konten dinamis dari halaman yang menggunakan layout ini -->
          </div>
        </div>
      </div>
      <!-- [ Sample Page ] selesai -->
    </div>
    <!-- [ Konten Utama ] selesai -->

  </div>
</div>
<!-- [ Konten Utama ] selesai -->

@include('layout.footer')  <!-- Menyertakan file footer dari layout -->
