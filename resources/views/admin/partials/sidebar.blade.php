<aside class="admin-sidebar">
    <a class="admin-brand" href="{{ route('admin.dashboard') }}">
        <span class="admin-brand-mark">A</span>
        <span>ALKESHUB</span>
    </a>
    <div class="admin-nav-label">Menu</div>
    <nav class="admin-nav" aria-label="Admin navigation">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif>
            <span class="admin-nav-icon" aria-hidden="true">⌂</span>Dashboard
        </a>
        <a href="{{ route('admin.manage_users') }}" class="{{ request()->routeIs('admin.manage_users', 'admin.update_user', 'admin.delete_user') ? 'active' : '' }}" @if(request()->routeIs('admin.manage_users', 'admin.update_user', 'admin.delete_user')) aria-current="page" @endif>
            <span class="admin-nav-icon" aria-hidden="true">♙</span>Manage Users
        </a>
        <a href="{{ route('admin.manage_orders') }}" class="{{ request()->routeIs('admin.manage_orders', 'admin.ship_order') ? 'active' : '' }}" @if(request()->routeIs('admin.manage_orders', 'admin.ship_order')) aria-current="page" @endif>
            <span class="admin-nav-icon" aria-hidden="true">▤</span>Manage Orders
        </a>
        <a href="{{ route('admin.manage_stocks') }}" class="{{ request()->routeIs('admin.manage_stocks', 'admin.receive_stock', 'admin.reject_stock') ? 'active' : '' }}" @if(request()->routeIs('admin.manage_stocks', 'admin.receive_stock', 'admin.reject_stock')) aria-current="page" @endif>
            <span class="admin-nav-icon" aria-hidden="true">▤</span>Manage Stocks
        </a>
        <a href="{{ route('admin.manage_categories') }}" class="{{ request()->routeIs('admin.manage_categories', 'admin.create_category', 'admin.update_category', 'admin.delete_category') ? 'active' : '' }}" @if(request()->routeIs('admin.manage_categories', 'admin.create_category', 'admin.update_category', 'admin.delete_category')) aria-current="page" @endif>
            <span class="admin-nav-icon" aria-hidden="true">▦</span>Manage Categories
        </a>
        <a href="{{ route('admin.manage_feedback') }}" class="{{ request()->routeIs('admin.manage_feedback', 'admin.delete_feedback') ? 'active' : '' }}" @if(request()->routeIs('admin.manage_feedback', 'admin.delete_feedback')) aria-current="page" @endif>
            <span class="admin-nav-icon" aria-hidden="true">✉</span>Manage Feedback
        </a>
        <a href="{{ route('admin.pending_vendors') }}" class="{{ request()->routeIs('admin.pending_vendors', 'admin.approve_vendor', 'admin.reject_vendor') ? 'active' : '' }}" @if(request()->routeIs('admin.pending_vendors', 'admin.approve_vendor', 'admin.reject_vendor')) aria-current="page" @endif>
            <span class="admin-nav-icon" aria-hidden="true">✓</span>Vendor Approval
        </a>
    </nav>
</aside>
