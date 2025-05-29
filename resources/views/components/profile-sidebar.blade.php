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
                <span class="ps-nav-text">Order list</span>
            </a>

            @php
                $pendingCount = \App\Models\Order::where('payment_status', 'pending')
                    ->whereHas('checkouts', function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->count();
            @endphp

            <a href="{{ route('orders.pending') }}"
                class="ps-nav-item {{ request()->routeIs('orders.pending') ? 'is-active' : '' }}">
                <i class="fas fa-clock ps-nav-icon"></i>
                <span class="ps-nav-text">Waiting for payment</span>
                @if ($pendingCount > 0)
                    <span class="badge bg-danger">
                        {{ $pendingCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('voucher.claim') }}"
                class="ps-nav-item {{ request()->routeIs(['voucher.claim', 'your-vouchers']) ? 'is-active' : '' }}">
                <i class="fas fa-ticket-alt ps-nav-icon"></i>
                <span class="ps-nav-text">Voucher</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="ps-nav-item logout">
                @csrf
                <button type="submit" class="ps-nav-button">
                    <i class="fas fa-sign-out-alt ps-nav-icon"></i>
                    <span class="ps-nav-text">Logout</span>
                </button>
            </form>
        </div>
    </div>
</div>
