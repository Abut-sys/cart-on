<nav class="navbar-top">
    <div class="menu-icon">
        <i class="fas fa-bars" id="menuToggle"></i>
    </div>
    <div class="search-bar">
        <input type="text" placeholder="Tap to search" class="search-input" />
        <i class="fas fa-search search-icon"></i>
    </div>
    <div class="notification-section">
        <i class="fas fa-bell notification-icon"></i>
    </div>
    <div class="user-info">
        @if (Auth::check())
            <a href="#" class="username" id="adminToggle">{{ Auth::user()->name }}</a>
            <div class="user-dropdown" id="userDropdown">
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="fas fa-user-edit"></i> Profile
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        @else
            <a href="{{ route('login') }}" class="username">Login</a>
            <a href="{{ route('register') }}" class="username">Register</a>
        @endif
    </div>
</nav>

<div class="sidebar" id="sidebar">
    <div class="logo">
        <img src="{{ asset('image/Logo_baru.png') }}" alt="Logo" />
    </div>
    <div class="nav-item">
        <a href="{{ url('/dashboard') }}" class="{{ request()->is('/dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-line icon"></i> Dashboard
        </a>
    </div>
    <div class="nav-item">
        <a href="{{ route('products.index') }}" class="{{ request()->is('products*') ? 'active' : '' }}">
            <i class="fas fa-boxes icon"></i> Products
        </a>
    </div>
    <div class="nav-dropdown-item category-container">
        <a href="#" class="category-link {{ request()->is('categories/*') ? 'active' : '' }}">
            <i class="fas fa-shapes icon"></i> Category
        </a>
        <div class="dropdown">
            <a href="{{ route('categories.index') }}" class="dropdown-item">
                <i class="fas fa-tag"></i> Product Category
            </a>
            <a href="{{ route('brands.index') }}" class="dropdown-item">
                <i class="fas fa-star"></i> Brand Category
            </a>
        </div>
    </div>
    <div class="nav-item">
        <a href="{{ route('vouchers.index') }}" class="{{ request()->is('vouchers') ? 'active' : '' }}">
            <i class="fas fa-ticket-alt icon"></i> Voucher
        </a>
    </div>
    <div class="nav-item">
        <a href="{{ url('orders') }}" class="{{ request()->is('orders') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart icon"></i> Orders
        </a>
    </div>
    <div class="nav-item">
        <a href="{{ route('costumers.index') }}" class="{{ request()->is('customers') ? 'active' : '' }}">
            <i class="fas fa-users icon"></i> Account
        </a>
    </div>
    <div class="nav-item">
        <a href="{{ route('informations.index') }}" class="{{ request()->is('settings') ? 'active' : '' }}">
            <i class="fas fa-cog icon"></i> Information Web
        </a>
    </div>

    <div class="logout-container">
        @if (Auth::check())
            <a href="{{ route('logout') }}" class="logout-link"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> Log Out
            </a>
        @endif
    </div>    
          
</div>
