
  @include('layout.header')
  <body>
    @include('layout.navbar')

    <div class="az-content az-content-dashboard">
      <div class="container">
        <div class="az-content-body">
          <div class="az-dashboard-one-title">
            @include('layout.top_page')
          </div><!-- az-dashboard-one-title -->

          <div class="card">
            <div class="container">
              @yield('content')
            </div>
          </div>
        </div>
      </div>
      <style>
        /* CSS untuk sticky footer */
        html, body {
            height: 100%;
            margin: 0;
        }

        .az-content {
            min-height: calc(100vh - 60px); /* Sesuaikan dengan tinggi header dan footer */
            display: flex;
            flex-direction: column;
        }
        .az-footer {
            margin-top: auto; /* Memastikan footer berada di bawah */
        }
    </style>
  

    <div class="az-footer">
        <div class="container ht-100p ">
            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © bootstrapdash.com 2020</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Free <a href="https://www.bootstrapdash.com/bootstrap-admin-template/" target="_blank">Bootstrap admin templates</a> from Bootstrapdash.com</span>
        </div><!-- container -->
    </div><!-- az-footer -->


    <script src="{{ asset('lib/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('lib/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('lib/ionicons/ionicons.js')}}"></script>
    <script src="{{ asset('lib/jquery.flot/jquery.flot.js')}}"></script>
    <script src="{{ asset('lib/jquery.flot/jquery.flot.resize.js')}}"></script>
    <script src="{{ asset('lib/chart.js/Chart.bundle.min.js')}}"></script>
    <script src="{{ asset('lib/peity/jquery.peity.min.js')}}"></script>

    <script src="{{ asset('js/azia.js')}}"></script>
    <script src="{{ asset('js/chart.flot.sampledata.js')}}"></script>
    <script src="{{ asset('js/dashboard.sampledata.js')}}"></script>
    <script src="{{ asset('js/jquery.cookie.js')}}" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('js_default.js_alert')
  </body>
</html>
