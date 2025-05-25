<!DOCTYPE html>
<html lang="en">
<!-- [Head] mulai -->

<head>
  <!-- Judul Halaman -->
  <title>{{ config('app.name') }} | @yield('title')</title>

  <!-- [Meta] Informasi Halaman -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="">

  <!-- [Favicon] Ikon Favicon -->
  <link rel="icon" href="{{ asset('images/favicon.svg')}}" type="image/x-icon">

  <!-- [Google Font] Menghubungkan ke Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">

  <!-- [Tabler Icons] Menghubungkan ke Tabler Icons -->
  <link rel="stylesheet" href="{{ asset('fonts/tabler-icons.min.css')}}" >

  <!-- [Feather Icons] Menghubungkan ke Feather Icons -->
  <link rel="stylesheet" href="{{ asset('fonts/feather.css')}}" >

  <!-- [Font Awesome Icons] Menghubungkan ke Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('fonts/fontawesome.css')}}" >

  <!-- [Material Icons] Menghubungkan ke Material Icons -->
  <link rel="stylesheet" href="{{ asset('fonts/material.css')}}" >

  <!-- [Template CSS Files] Menghubungkan ke file CSS template -->
  <link rel="stylesheet" href="{{ asset('css/style.css')}}" id="main-style-link" >
  <link rel="stylesheet" href="{{ asset('css/style-preset.css')}}" >

</head>
<!-- [Head] selesai -->

<!-- [Body] mulai -->

<body>

  <!-- [Pre-loader] mulai -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [Pre-loader] selesai -->

  <!-- [Konten Utama] mulai -->
  <div class="auth-main">
    <div class="auth-wrapper v3">
      <div class="auth-form">
        <div class="card my-5">
          <!-- Judul Halaman atau Nama Aplikasi -->
          <h2 class="text-primary text-center text-uppercase mt-5">{{ config('app.name') }}</h2>
          <div class="card-body">
            <!-- Konten Dinamis dari halaman -->
            @yield('content')
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- [Konten Utama] selesai -->

  <!-- [Script] Menghubungkan file JS yang dibutuhkan -->
  <script src="{{ asset('js/plugins/popper.min.js')}}"></script>
  <script src="{{ asset('js/plugins/simplebar.min.js')}}"></script>
  <script src="{{ asset('js/plugins/bootstrap.min.js')}}"></script>
  <script src="{{ asset('js/fonts/custom-font.js')}}"></script>
  <script src="{{ asset('js/pcoded.js')}}"></script>
  <script src="{{ asset('js/plugins/feather.min.js')}}"></script>

  <!-- [SweetAlert2] Menghubungkan dengan SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Menyertakan file js alert default -->
  @include('js_default.js_alert')

  <!-- Mengubah tema layout ke 'light' -->
  <script>layout_change('light');</script>

  <!-- Mengubah pengaturan box container -->
  <script>change_box_container('false');</script>

  <!-- Mengubah pengaturan layout RTL -->
  <script>layout_rtl_change('false');</script>

  <!-- Mengubah preset tema -->
  <script>preset_change("preset-1");</script>

  <!-- Mengubah font ke 'Public-Sans' -->
  <script>font_change("Public-Sans");</script>

</body>
<!-- [Body] selesai -->

</html>
