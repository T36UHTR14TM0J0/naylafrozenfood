<!DOCTYPE html>
<html lang="en">
<!-- [Head] mulai -->

<head>
  <!-- Menetapkan judul halaman dengan nama aplikasi dan judul dinamis -->
  <title>{{ config('app.name') }} | @yield('title')</title>

  <!-- [Meta] Informasi penting tentang halaman -->
  <meta charset="utf-8">  <!-- Menentukan karakter set yang digunakan -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">  <!-- Pengaturan viewport untuk responsif -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge">  <!-- Menjamin kompatibilitas dengan IE -->
  <meta name="description" content="">  <!-- Deskripsi halaman, biasanya untuk SEO -->
  <meta name="keywords" content="">  <!-- Kata kunci untuk SEO -->
  <meta name="author" content="">  <!-- Penulis halaman -->

  <!-- [Favicon] Ikon favicon untuk tab browser -->
  <link rel="icon" href="{{ asset('images/favicon.svg') }}" type="image/x-icon">

  <!-- [Google Font] Menghubungkan ke Google Fonts untuk menggunakan font 'Public Sans' -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">

  <!-- [Tabler Icons] Menyertakan ikon dari Tabler Icons -->
  <link rel="stylesheet" href="{{ asset('fonts/tabler-icons.min.css') }}">

  <!-- [Feather Icons] Menyertakan ikon dari Feather Icons -->
  <link rel="stylesheet" href="{{ asset('fonts/feather.css') }}">

  <!-- [Font Awesome Icons] Menyertakan ikon dari Font Awesome -->
  <link rel="stylesheet" href="{{ asset('fonts/fontawesome.css') }}">

  <!-- [Material Icons] Menyertakan ikon dari Material Icons -->
  <link rel="stylesheet" href="{{ asset('fonts/material.css') }}">

  <!-- [Template CSS Files] Menyertakan file CSS template utama dan preset -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" id="main-style-link">
  <link rel="stylesheet" href="{{ asset('css/style-preset.css') }}">

  <!-- Menambahkan CSS Select2 untuk elemen select yang lebih interaktif -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

</head>
<!-- [Head] selesai -->

<!-- [Body] mulai -->

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">

  <!-- [Pre-loader] mulai -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [Pre-loader] selesai -->

