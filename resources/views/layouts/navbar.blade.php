<nav class="navbar-top">
    @auth
        @if (Auth::user()->role == 'admin')
            <div class="menu-icon">
                <i class="fas fa-bars" id="menuToggle"></i>
            </div>
        @endif
    @endauth

    @if (!Auth::check() || Auth::user()->role != 'admin')
        <div class="logo-container" oncontextmenu="return false;">
            <img src="{{ asset('image/Logo_baru.png') }}" alt="Logo" class="logo-user">
        </div>
    @endif

    <div class="search-bar">
        <input type="text" placeholder="Tap to search" class="search-input" />
        <i class="fas fa-search search-icon"></i>
    </div>

    @if (!Auth::check() || Auth::user()->role != 'admin')
        <div class="link-section">
            <i class="fas fa-home link-icon"></i>
        </div>
        <div class="link-section">
            <i class="fas fa-boxes link-icon"></i>
        </div>
        <div class="link-section">
            <i class="fas fa-shopping-cart link-icon"></i>
        </div>
        <div class="link-section">
            <i class="fas fa-info-circle link-icon"></i>
        </div>
    @endif

        <div class="notification-section">
            @auth
                <i class="fas fa-bell notification-icon"></i>
            @endauth
        </div>

    <div class="user-info">
        @auth
            <a href="#" class="username" id="adminToggle">{{ Auth::user()->name }}</a>
            <div class="user-dropdown" id="userDropdown">
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="fas fa-user-edit"></i> Profile
                </a>
                @if (Auth::user()->role != 'admin')
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Log Out
                    </a>
                @endif
            </div>
        @else
            <a href="{{ route('login') }}" class="username">Login</a>
            <a href="{{ route('register') }}" class="username">Register</a>
        @endauth
    </div>
</nav>

<div class="sidebar" id="sidebar" @if (Auth::check() && Auth::user()->role != 'admin') style="display: none;" @endif>
    <div class="logo">
        <img src="{{ asset('image/Logo_baru.png') }}" alt="Logo" />
    </div>

    @if (Auth::check() && Auth::user()->role == 'admin')
        <div class="nav-item">
            <a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
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
            <a href="{{ route('logout') }}" class="logout-link"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> Log Out
            </a>
        </div>
    @endif
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
