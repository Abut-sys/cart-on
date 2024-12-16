<nav class="navbar-top">
    @auth
        @if (Auth::user()->role == 'admin')
            <div class="menu-icon">
                <i class="fas fa-bars" id="menuToggle"></i>
            </div>
        @endif
    @endauth

    <div class="logo-container" oncontextmenu="return false;">
        <a href="/">
            <img src="{{ asset('image/Logo_baru.png') }}" alt="Logo" class="logo-user">
        </a>
    </div>

    @if (!Auth::check() || Auth::user()->role != 'admin')
        <div class="search-bar">
            <input type="text" placeholder="Tap to search" class="search-input" />
            <i class="fas fa-search search-icon"></i>
        </div>

        <div class="link-section">
            <a href="{{ route('home.index') }}" class="{{ request()->is('/') ? 'active' : '' }}">
                <i class="fas fa-home link-icon"></i>
            </a>
        </div>

        <div class="link-section">
            <a href="{{ route('products-all.index') }}" class="{{ request()->is('products-all') ? 'active' : '' }}">
                <i class="fas fa-boxes link-icon"></i>
            </a>
        </div>

        <div class="link-section">
            <a href="{{ route('cart.index') }}" class="{{ request()->is('cart') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart link-icon"></i>
                @if (Auth::check())
                    <span id="cart-count" class="badge">
                        {{ Auth::user()->carts->count() }}
                    </span>
                @else
                    <span id="cart-count" class="badge" style="display:none;"></span>
                @endif
            </a>
        </div>

        <div class="link-section">
            <a href="{{ route('wishlist.index') }}" class="{{ request()->is('wishlist') ? 'active' : '' }}">
                <i class="fas fa-heart link-icon"></i>
                @if (Auth::check())
                    <span id="wishlist-count" class="badge">
                        {{ Auth::user()->wishlists->count() }}
                    </span>
                @else
                    <span id="wishlist-count" class="badge" style="display:none;"></span>
                @endif
            </a>
        </div>
    @endif

    {{-- Notification and User Info --}}
    <div class="link-section">
        @auth
            <!-- Icon Notifikasi -->
            <i class="fas fa-bell notification-icon" id="notificationIcon" style="cursor: pointer;"></i>
            <span class="badge" id="notificationCount">{{ auth()->user()->unreadNotifications->count() }}</span>

            <!-- Dropdown Notifikasi -->
            <div class="notification-dropdown hidden" id="notificationDropdown">
                <!-- Notifikasi yang belum dibaca -->
                @foreach (auth()->user()->unreadNotifications as $notification)
                    <div class="notification-item unread" onclick="markAsRead(['{{ $notification->id }}'])">
                        <i class="fas fa-info-circle"></i>
                        {{ $notification->data['message'] }}
                    </div>
                @endforeach

                <!-- Notifikasi yang sudah dibaca -->
                @foreach (auth()->user()->readNotifications as $notification)
                    <div class="notification-item read">
                        <i class="fas fa-info-circle"></i>
                        {{ $notification->data['message'] }}
                    </div>
                @endforeach
            </div>
        @endauth
    </div>

    <div class="user-wrapper">
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
    <div class="logo-sidebar">
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
                <a href="{{ route('categories.index') }}" class="dropdown-item-">
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
            <a href="{{ route('orders.index') }}" class="{{ request()->is('orders') ? 'active' : '' }}">
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
