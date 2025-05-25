<div class="ps-container">
    <div class="sidebar-menu">
        <!-- Profile Card -->
        <div class="ps-profile-card">
            <div class="ps-avatar-wrapper hover-scale">
                @if (optional(auth()->user()->profile)->profile_picture)
                    <img src="{{ Storage::url('profile_pictures/' . auth()->user()->profile->profile_picture) }}"
                        alt="User Avatar" class="ps-avatar-img">
                @else
                    <div class="ps-initials-avatar ps-gradient-bg">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            <div class="ps-profile-details">
                <h3 class="ps-user-name">{{ auth()->user()->name }}</h3>
                <p class="ps-user-email">{{ auth()->user()->email }}</p>
            </div>
        </div>

        <div class="ps-navigation-menu">
            <a href="{{ route('profile.edit') }}"
                class="ps-nav-item {{ request()->routeIs('profile.edit') ? 'is-active' : '' }}">
                <i class="fas fa-user-edit ps-nav-icon"></i>
                <span class="ps-nav-text">Edit Profile</span>
            </a>

            <a href="{{ route('orders.history') }}"
                class="ps-nav-item {{ request()->routeIs('orders.history') ? 'is-active' : '' }}">
                <i class="fas fa-history ps-nav-icon"></i>
                <span class="ps-nav-text">Transaction list</span>
            </a>

            <a href="#" class="ps-nav-item {{ request()->routeIs('orders.pending') ? 'is-active' : '' }}">
                <i class="fas fa-clock ps-nav-icon"></i>
                <span class="ps-nav-text">Waiting for payment</span>
            </a>

            <a href="{{ route('voucher.claim') }}"
                class="ps-nav-item {{ request()->routeIs(['voucher.claim', 'your-vouchers']) ? 'is-active' : '' }}">
                <i class="fas fa-ticket-alt ps-nav-icon"></i>
                <span class="ps-nav-text">Voucher</span>
            </a>

            <a href="#" class="ps-nav-item {{ request()->routeIs('notifications.*') ? 'is-active' : '' }}">
                <i class="fas fa-bell ps-nav-icon"></i>
                <span class="ps-nav-text">Notifikasi</span>
            </a>
        </div>
    </div>
</div>
