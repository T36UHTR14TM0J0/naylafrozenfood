

<!DOCTYPE html>
<html lang="en">
  <head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    
    <!-- Meta -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ config('app.name') }}</title>

    <!-- vendor css -->
    <link href="{{ asset('lib/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
    <link href="{{ asset('lib/ionicons/css/ionicons.min.css')}}" rel="stylesheet">
    <link href="{{ asset('lib/typicons.font/typicons.css')}}" rel="stylesheet">

    <!-- azia CSS -->
    <link rel="stylesheet" href="{{ asset('css/azia.css')}}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

  </head>
  <body class="az-body">

    @yield('content')

    <script src="{{ asset('lib/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('lib/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('lib/ionicons/ionicons.js')}}"></script>
    <script src="{{ asset('js/cookie.js')}}" type="text/javascript"></script>

    <script src="{{ asset('js/azia.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- @include('js_default.js_alert') --}}
  </body>
</html>
