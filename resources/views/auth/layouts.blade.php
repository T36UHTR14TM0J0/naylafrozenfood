<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
  <title>{{ config('app.name') }} | @yield('title')</title>
  <!-- [Meta] -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="">

  <!-- [Favicon] icon -->
  <link rel="icon" href="{{ asset('images/favicon.svg')}}" type="image/x-icon"> <!-- [Google Font] Family -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
  <!-- [Tabler Icons] https://tablericons.com -->
  <link rel="stylesheet" href="{{ asset('fonts/tabler-icons.min.css')}}" >
  <!-- [Feather Icons] https://feathericons.com -->
  <link rel="stylesheet" href="{{ asset('fonts/feather.css')}}" >
  <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
  <link rel="stylesheet" href="{{ asset('fonts/fontawesome.css')}}" >
  <!-- [Material Icons] https://fonts.google.com/icons -->
  <link rel="stylesheet" href="{{ asset('fonts/material.css')}}" >
  <!-- [Template CSS Files] -->
  <link rel="stylesheet" href="{{ asset('css/style.css')}}" id="main-style-link" >
  <link rel="stylesheet" href="{{ asset('css/style-preset.css')}}" >

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body>
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->

  <div class="auth-main">
    <div class="auth-wrapper v3">
      <div class="auth-form">
        <div class="card my-5">
          <h2 class="text-primary text-center text-uppercase mt-5">{{ config('app.name') }}</h2>
          <div class="card-body">
            @yield('content')
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- [ Main Content ] end -->
  <!-- Required Js -->
  <script src="{{ asset('js/plugins/popper.min.js')}}"></script>
  <script src="{{ asset('js/plugins/simplebar.min.js')}}"></script>
  <script src="{{ asset('js/plugins/bootstrap.min.js')}}"></script>
  <script src="{{ asset('js/fonts/custom-font.js')}}"></script>
  <script src="{{ asset('js/pcoded.js')}}"></script>
  <script src="{{ asset('js/plugins/feather.min.js')}}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @include('js_default.js_alert')

  
  
  
  
  <script>layout_change('light');</script>
  
  
  
  
  <script>change_box_container('false');</script>
  
  
  
  <script>layout_rtl_change('false');</script>
  
  
  <script>preset_change("preset-1");</script>
  
  
  <script>font_change("Public-Sans");</script>
  
    
 
</body>
<!-- [Body] end -->

</html>