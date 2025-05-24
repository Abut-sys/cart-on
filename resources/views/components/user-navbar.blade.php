<nav class="navbar-top">
    <div class="logo-container" oncontextmenu="return false;">
        <a href="{{ url('/') }}">
            <img src="{{ asset('image/Logo_baru.png') }}" alt="Logo" class="logo-user">
        </a>
    </div>

    @if (!Auth::check() || Auth::user()->role != 'admin')
        <form method="GET" action="{{ route('products-all.index') }}" class="search-bar" id="search-form">
            <div class="search-input-wrapper">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search for products"
                    class="search-input" id="search-input">
                <i class="fas fa-search search-icon"></i>
            </div>
        </form>


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
            <a href="{{ route('cart.index') }}">
                <i class="fas fa-shopping-cart link-icon {{ request()->is('cart') ? 'active' : '' }}"></i>
                <span id="for-badge-count-cart" class="badge {{ Auth::check() ? '' : 'bg-danger' }}"
                    style="{{ Auth::check() ? '' : 'display:none;' }}">
                    {{ Auth::check() ? Auth::user()->carts->count() : '' }}
                </span>
            </a>
        </div>


        <div class="link-section">
            <a href="{{ route('wishlist.index') }}" class="{{ request()->is('wishlist') ? 'active' : '' }}">
                <i class="fas fa-heart link-icon"></i>
                <span id="for-badge-count-wishlist" class="badge" style="{{ Auth::check() ? '' : 'display:none;' }}">
                    {{ Auth::check() ? Auth::user()->wishlists->count() : '' }}
                </span>
            </a>
        </div>
        </div>
    @endif

    <div class="link-section">
        @auth
            <i class="fas fa-bell notification-icon" id="notificationIcon" style="cursor: pointer;"></i>
            <span class="badge" id="notificationCount">{{ auth()->user()->unreadNotifications->count() }}</span>

            <div class="notification-dropdown hidden" id="notificationDropdown">
                @foreach (auth()->user()->unreadNotifications as $notification)
                    <div class="notification-item unread" onclick="markAsRead(['{{ $notification->id }}'])">
                        <i class="fas fa-info-circle"></i>
                        {{ $notification->data['message'] }}
                    </div>
                @endforeach

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
                    <a class="dropdown-item" href="{{ route('voucher.claim') }}">
                        <i class="fas fa-gift"></i> Claim Voucher
                    </a>
                @endif
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

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
