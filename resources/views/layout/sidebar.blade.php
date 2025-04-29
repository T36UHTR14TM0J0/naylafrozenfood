<!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header">
      <a href="{{ route('dashboard') }}" class="b-brand text-primary">
        <!-- ========   Change your logo from here   ============ -->
        {{-- <img src="../assets/images/logo-dark.svg" class="img-fluid logo-lg" alt="logo"> --}}
        <h3 class="text-title text-primary">{{ config('app.name') }}</h3>
      </a>
    </div>
    <div class="navbar-content">
      <ul class="pc-navbar">
        <li class="pc-item">
          <a href="{{ route('dashboard') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
            <span class="pc-mtext">Dashboard</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-receipt"></i></span>
            <span class="pc-mtext">Transaksi</span>
          </a>
        </li>

        <li class="pc-item pc-caption">
          <label>Master Data :</label>
          {{-- <i class="ti ti-dashboard"></i> --}}
        </li>

        <li class="pc-item">
          <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-truck-delivery"></i></span>
            <span class="pc-mtext">Suplier</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-tag"></i></span>
            <span class="pc-mtext">Kategori Item</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-package"></i></span>
            <span class="pc-mtext">Item</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-box-multiple"></i></span>
            <span class="pc-mtext">Kelola Stok</span>
          </a>
        </li>

        <li class="pc-item pc-caption">
          <label>Laporan :</label>
          {{-- <i class="ti ti-dashboard"></i> --}}
        </li>

        <li class="pc-item">
          <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-chart-bar"></i></span>
            <span class="pc-mtext">Laporan Transaksi</span>
          </a>
        </li>

        @if (auth()->user()->isOwner())
        <li class="pc-item pc-caption">
          <label>Manajemen Akun :</label>
          {{-- <i class="ti ti-dashboard"></i> --}}
        </li>
        <li class="pc-item">
          <a href="{{ route('user.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-users"></i></span>
            <span class="pc-mtext">Kelola Akun</span>
          </a>
        </li>
        @endif

      </ul>
    </div>
  </div>
</nav>
<!-- [ Sidebar Menu ] end -->
