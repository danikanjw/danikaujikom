@php
    $headerHomeRoute = Session::get('role') === 'admin'
        ? route('admin.dashboard')
        : (Session::get('role') === 'vendor' ? route('vendor.dashboard') : route('product'));
@endphp

<header class="alkeshub-header">
    @if(request()->routeIs('admin.*'))
        <div class="d-flex align-items-center gap-3">
            <img class="alkeshub-logo" src="{{ asset('Alkes Hub Logo.png') }}" alt="Alkes Hub">
            <h1 class="alkeshub-header-title">AlkesHub Admin Page</h1>
        </div>
    @else
        <a class="alkeshub-header-brand" href="{{ $headerHomeRoute }}">
            <img class="alkeshub-logo" src="{{ asset('Alkes Hub Logo.png') }}" alt="">
            <span>ALKESHUB</span>
        </a>
    @endif
    <div class="dropdown">
        <button class="btn alkeshub-header-profile dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="alkeshub-header-avatar" aria-hidden="true">{{ strtoupper(substr(Session::get('username', 'A'), 0, 1)) }}</span>
            <span class="alkeshub-header-name">{{ Session::get('username', 'Admin') }}</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
            <li><a class="dropdown-item" href="{{ route('profile') }}">Edit Profil</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="{{ route('logout') }}">Logout</a></li>
        </ul>
    </div>
</header>
