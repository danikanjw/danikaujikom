<header>
  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="#">Toko Alat Kesehatan</a>
      <div class="d-flex align-items-center">
        @if(Session::has('user_id'))
          @if(request()->routeIs('profile') || request()->routeIs('checkout'))
            @if(request()->routeIs('profile'))
              <a href="{{ route('profile') }}" class="btn btn-info me-2 disabled" tabindex="-1" aria-disabled="true">Profile</a>
            @else
              <a href="{{ route('profile') }}" class="btn btn-info me-2">Profile</a>
            @endif
            <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
          @else
            <button type="button" class="btn btn-outline-primary me-2 position-relative" data-bs-toggle="modal" data-bs-target="#cartModal">
              <i class="fas fa-shopping-cart"></i> Cart
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartBadge" style="display: none;">0</span>
            </button>
            <a href="{{ route('profile') }}" class="btn btn-info me-2">Profile</a>
            <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
          @endif
        @else
          <a href="{{ route('login') }}" class="btn btn-primary me-2">Login</a>
          <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
        @endif
      </div>
    </div>
  </nav>
</header>
