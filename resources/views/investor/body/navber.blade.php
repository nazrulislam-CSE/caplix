  <nav class="navbar navbar-expand-lg navbar-custom shadow-sm fixed-top">
      <div class="container-fluid">
          <!-- Sidebar Toggle for all devices -->
          <button class="sidebar-toggle" id="sidebarToggle">
              <i class="bi bi-list"></i>
          </button>

          <!-- Brand -->
          <a class="navbar-brand d-none d-md-block" href="#">
              <i class="fas fa-chart-line me-2"></i> CapliX Investor
          </a>

          <!-- Right side profile -->
          <div class="ms-auto dropdown">
              <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                  id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                       <img src="{{ Auth::user()->photo
                      ? asset('upload/investor/' . Auth::user()->photo)
                      : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png' }}"
                      class="rounded-circle profile-img me-2" alt="Investor"
                      width="100">


                  <span class="text-muted fw-bold d-none d-md-inline">{{ Auth::user()->name ?? '' }}</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown">
                  <li>
                      <a class="dropdown-item" href="{{ route('investor.profile') }}">
                          <i class="fas fa-user me-2"></i> Profile View
                      </a>
                  </li>
                    <li>
                      <a class="dropdown-item" href="{{ route('investor.password.change') }}">
                          <i class="fas fa-lock me-2"></i> Password Change
                      </a>
                  </li>
                  <li>
                      <hr class="dropdown-divider">
                  </li>
                  <li>
                      <a class="dropdown-item text-danger" href="{{ route('investor.logout') }}">
                          <i class="fas fa-sign-out-alt me-2"></i> Logout
                      </a>
                  </li>
              </ul>
          </div>
      </div>
  </nav>
