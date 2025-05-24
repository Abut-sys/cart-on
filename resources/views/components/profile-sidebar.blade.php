<div class="ps-container">
    <div class="sidebar-menu">
        <!-- Profile Card -->
        <div class="ps-profile-card">
            <div class="ps-avatar-wrapper hover-scale">
                @if (optional($user->profile)->profile_picture)
                    <img src="{{ Storage::url('profile_pictures/' . $user->profile->profile_picture) }}" alt="User Avatar"
                        class="ps-avatar-img">
                @else
                    <div class="ps-initials-avatar ps-gradient-bg">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            <div class="ps-profile-details">
                <h3 class="ps-user-name">{{ $user->name }}</h3>
                <p class="ps-user-email">{{ $user->email }}</p>
            </div>
        </div>

        <div class="ps-navigation-menu">
            <a href="{{ route('profile.edit') }}"
                class="ps-nav-item {{ request()->routeIs('profile.edit') ? 'is-active' : '' }}">
                <i class="fas fa-user-edit ps-nav-icon"></i>
                <span class="ps-nav-text">Edit Profile</span>
            </a>

            <a href="#" class="ps-nav-item {{ request()->routeIs('orders.history') ? 'is-active' : '' }}">
                <i class="fas fa-history ps-nav-icon"></i>
                <span class="ps-nav-text">Transaction list</span>
            </a>

            <a href="#" class="ps-nav-item {{ request()->routeIs('orders.pending') ? 'is-active' : '' }}">
                <i class="fas fa-clock ps-nav-icon"></i>
                <span class="ps-n   av-text">Waiting for payment</span>
                <span class="ps-badge">3</span>
            </a>

            <a href="{{ route('voucher.claim') }}"
                class="ps-nav-item {{ request()->routeIs(['voucher.claim', 'your-vouchers']) ? 'is-active' : '' }}">
                <i class="fas fa-ticket-alt ps-nav-icon"></i>
                <span class="ps-nav-text">Voucher</span>
            </a>

            <a href="#" class="ps-nav-item {{ request()->routeIs('notifications.*') ? 'is-active' : '' }}">
                <i class="fas fa-bell ps-nav-icon"></i>
                <span class="ps-nav-text">Notifikasi</span>
                <span class="ps-badge">5</span>
            </a>
        </div>
    </div>
</div>
