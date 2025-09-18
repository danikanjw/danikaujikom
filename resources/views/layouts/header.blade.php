<header>
  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
      @php
        $role = Session::get('role');
      @endphp
      <a class="navbar-brand" href="{{ $role === 'admin' ? route('admin.dashboard') : route('home') }}">Toko Alat Kesehatan</a>
      <div class="d-flex align-items-center">
        @if(Session::has('user_id'))
          @if($role === 'admin')
            <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
          @else
            @if(request()->routeIs('profile') || request()->routeIs('checkout'))
              @if(request()->routeIs('profile'))
                <a href="{{ route('profile') }}" class="btn btn-info me-2 disabled" tabindex="-1" aria-disabled="true">Profile</a>
              @else
                <a href="{{ route('profile') }}" class="btn btn-info me-2">Profile</a>
              @endif
              <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
            @else
              @if(Session::get('role') !== 'vendor')
              <button type="button" class="btn btn-outline-primary me-2 position-relative" data-bs-toggle="modal" data-bs-target="#cartModal">
                <i class="fas fa-shopping-cart"></i> Cart
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartBadge" style="display: none;">0</span>
              </button>
              @endif
              <a href="{{ route('profile') }}" class="btn btn-info me-2">Profile</a>
              <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
            @endif
          @endif
        @else
        <a href="{{ route('vendor.apply') }}" class="btn btn-warning me-2">Ajukan Menjadi Vendor</a>
          <a href="{{ route('login') }}" class="btn btn-primary me-2">Login</a>
          <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
        @endif
      </div>
    </div>
  </nav>
</header>
