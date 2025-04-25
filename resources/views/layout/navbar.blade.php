<div class="az-header">
  <div class="container">
    <div class="az-header-left">
      <a href="index.html" class="az-logo"><span></span> azia</a>
      <a href="" id="azMenuShow" class="az-header-menu-icon d-lg-none"><span></span></a>
    </div><!-- az-header-left -->
    <div class="az-header-menu">
      <div class="az-header-menu-header">
        <a href="index.html" class="az-logo"><span></span> azia</a>
        <a href="" class="close">&times;</a>
      </div><!-- az-header-menu-header -->
      <ul class="nav">
        <li class="nav-item active show">
          <a href="index.html" class="nav-link"><i class="typcn typcn-chart-area-outline"></i> Dashboard</a>
        </li>
        <li class="nav-item">
          <a href="" class="nav-link with-sub"><i class="typcn typcn-document"></i> Pages</a>
          <nav class="az-menu-sub">
            <a href="page-signin.html" class="nav-link">Sign In</a>
            <a href="page-signup.html" class="nav-link">Sign Up</a>
          </nav>
        </li>
        <li class="nav-item">
          <a href="chart-chartjs.html" class="nav-link"><i class="typcn typcn-chart-bar-outline"></i> Charts</a>
        </li>
        <li class="nav-item">
          <a href="form-elements.html" class="nav-link"><i class="typcn typcn-chart-bar-outline"></i> Forms</a>
        </li>
        <li class="nav-item">
          <a href="" class="nav-link with-sub"><i class="typcn typcn-book"></i> Components</a>
          <div class="az-menu-sub">
            <div class="container">
              <div>
                <nav class="nav">
                  <a href="elem-buttons.html" class="nav-link">Buttons</a>
                  <a href="elem-dropdown.html" class="nav-link">Dropdown</a>
                  <a href="elem-icons.html" class="nav-link">Icons</a>
                  <a href="table-basic.html" class="nav-link">Table</a>
                </nav>
              </div>
            </div><!-- container -->
          </div>
        </li>
      </ul>
    </div><!-- az-header-menu -->
    <div class="az-header-right">
      
      <div class="dropdown az-profile-menu">
        <a href="" class="az-img-user" style="text-decoration: none;"><h6>Aziana Pechon</h6></a>
        <div class="dropdown-menu">
          <div class="az-dropdown-header d-sm-none">
            <a href="" class="az-header-arrow"><i class="icon ion-md-arrow-back"></i></a>
          </div>
          <a href="" class="dropdown-item"><i class="typcn typcn-edit"></i> Edit Profile</a>
          <form action="{{ route('logout') }}" method="POST">
            @csrf
              <button type="submit" class="dropdown-item"><i class="typcn typcn-power-outline"></i> Logout</button>
          </form>
        </div><!-- dropdown-menu -->
      </div>
    </div><!-- az-header-right -->
  </div><!-- container -->
</div><!-- az-header -->
