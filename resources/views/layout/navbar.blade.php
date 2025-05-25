<!-- [Header Topbar] mulai -->
<header class="pc-header">
  <div class="header-wrapper">
    <!-- [Mobile Media Block] mulai -->
    <div class="me-auto pc-mob-drp">
      <ul class="list-unstyled">
        <!-- Menu collapse Icon (untuk menu sidebar pada tampilan mobile) -->
        <li class="pc-h-item pc-sidebar-collapse">
          <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
            <i class="ti ti-menu-2"></i>  <!-- Ikon menu untuk membuka/menutup sidebar -->
          </a>
        </li>
        <li class="pc-h-item pc-sidebar-popup">
          <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
            <i class="ti ti-menu-2"></i>  <!-- Ikon menu untuk popup sidebar pada tampilan mobile -->
          </a>
        </li>
      </ul>
    </div>
    <!-- [Mobile Media Block] selesai -->

    <!-- Bagian kanan untuk menu profil pengguna -->
    <div class="ms-auto">
      <ul class="list-unstyled">
        <li class="dropdown pc-h-item header-user-profile">
          <!-- Dropdown untuk menampilkan profil pengguna -->
          <a
            class="pc-head-link dropdown-toggle arrow-none me-0"
            data-bs-toggle="dropdown"
            href="#"
            role="button"
            aria-haspopup="false"
            data-bs-auto-close="outside"
            aria-expanded="false"
          >
            <!-- Menampilkan nama pengguna saat ini -->
            <span>{{ auth()->user()->name }}</span>
          </a>

          <!-- Menu dropdown untuk profil pengguna -->
          <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
            <div class="dropdown-header">
              <div class="d-flex mb-1">
                <div class="flex-grow-1 ms-3">
                  <h6 class="mb-1">{{ auth()->user()->name }}</h6>  <!-- Menampilkan nama pengguna -->
                  <span>{{ auth()->user()->role }}</span>  <!-- Menampilkan peran (role) pengguna -->
                </div>
              </div>
            </div>
            <!-- Tab menu untuk profil -->
            <ul class="nav drp-tabs nav-fill nav-tabs" id="mydrpTab" role="tablist">
              <li class="nav-item" role="presentation">
                <!-- Menu tab untuk membuka profil -->
                <button
                  class="nav-link active"
                  id="drp-t1"
                  data-bs-toggle="tab"
                  data-bs-target="#drp-tab-1"
                  type="button"
                  role="tab"
                  aria-controls="drp-tab-1"
                  aria-selected="true"
                >
                  <i class="ti ti-user"></i> Profile
                </button>
              </li>
            </ul>
            <!-- Konten tab profil -->
            <div class="tab-content" id="mysrpTabContent">
              <div class="tab-pane fade show active" id="drp-tab-1" role="tabpanel" aria-labelledby="drp-t1" tabindex="0">
                <!-- Menu untuk mengedit profil -->
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                  <i class="ti ti-edit-circle"></i>
                  <span>Edit Profile</span>
                </a>
                <!-- Form logout -->
                <form action="{{ route('logout') }}" method="POST">
                  @csrf  <!-- Menyertakan token CSRF untuk keamanan -->
                  <button type="submit" class="dropdown-item">
                    <i class="ti ti-power"></i> Logout
                  </button>
                </form>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</header>
<!-- [Header] selesai -->
