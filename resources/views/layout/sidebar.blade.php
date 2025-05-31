<!-- [Sidebar Menu] mulai -->
<nav class="pc-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header">
      <!-- Logo dan nama aplikasi -->
      <a href="{{ route('dashboard') }}" class="b-brand text-primary">
        <!-- Ganti logo di sini jika diperlukan -->
        {{-- <img src="../assets/images/logo-dark.svg" class="img-fluid logo-lg" alt="logo"> --}}
        <h3 class="text-title text-primary">{{ config('app.name') }}</h3>
      </a>
    </div>
    <div class="navbar-content">
      <!-- Daftar menu sidebar -->
      <ul class="pc-navbar">
        
        <!-- Menu Dashboard -->
        <li class="pc-item">
          <a href="{{ route('dashboard') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
            <span class="pc-mtext">Dashboard</span>
          </a>
        </li>

        <!-- Menu Transaksi hanya untuk Admin -->
        {{-- @if (auth()->user()->isAdmin()) --}}
        <li class="pc-item">
          <a href="{{ route('transaksi.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-receipt"></i></span>
            <span class="pc-mtext">Transaksi</span>
          </a>
        </li>
        {{-- @endif --}}
        
        <!-- Caption Master Data -->
        <li class="pc-item pc-caption">
            <label>Master Data :</label>
        </li>

        <!-- Menu Suplier hanya untuk Owner -->
        @if (auth()->user()->isOwner())
        <li class="pc-item">
          <a href="{{ route('supplier.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-truck-delivery"></i></span>
            <span class="pc-mtext">Suplier</span>
          </a>
        </li>
        @endif

        <!-- Menu Kategori Item hanya untuk Admin -->
        {{-- @if (auth()->user()->isAdmin()) --}}
        <li class="pc-item">
          <a href="{{ route('kategori.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-tag"></i></span>
            <span class="pc-mtext">Kategori Item</span>
          </a>
        </li>

        <!-- Menu Satuan Item hanya untuk Admin -->
        <li class="pc-item">
          <a href="{{ route('satuan.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-database"></i></span>
            <span class="pc-mtext">Satuan Item</span>
          </a>
        </li>

        <!-- Menu Item hanya untuk Admin -->
        <li class="pc-item">
          <a href="{{ route('item.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-package"></i></span>
            <span class="pc-mtext">Item</span>
          </a>
        </li>

        <!-- Menu Kelola Stok hanya untuk Admin -->
        <li class="pc-item">
          <a href="{{ route('stok.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-box-multiple"></i></span>
            <span class="pc-mtext">Kelola Stok</span>
          </a>
        </li>
        {{-- @endif --}}

        <!-- Caption untuk laporan -->
        <li class="pc-item pc-caption">
          <label>Laporan :</label>
        </li>

        <!-- Menu Laporan Transaksi -->
        <li class="pc-item">
          <a href="{{ route('report.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-chart-bar"></i></span>
            <span class="pc-mtext">Laporan Transaksi</span>
          </a>
        </li>
        <li class="pc-item">
          <a href="#" class="pc-link">
            <span class="pc-micon"><i class="fas fa-money-bill-wave"></i></span>
            <span class="pc-mtext">Laporan Keuangan</span>
          </a>
        </li>

        <!-- Menu Manajemen Akun hanya untuk Owner -->
        @if (auth()->user()->isOwner())
        <li class="pc-item pc-caption">
          <label>Manajemen Akun :</label>
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
<!-- [Sidebar Menu] selesai -->
