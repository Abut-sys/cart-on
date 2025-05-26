<nav class="navbar-top">
    <div class="nav-left" style="display: flex; align-items: center;">
        @auth
            @if (Auth::user()->role == 'admin')
                <div class="menu-icon" style="margin-right: 10px;">
                    <i class="fas fa-bars" id="menuToggle"></i>
                </div>
            @endif
        @endauth
        <div class="logo-container" oncontextmenu="return false;">
            <a href="{{ url('/dashboard') }}">
                <img src="{{ asset('image/Logo_baru.png') }}" alt="Logo" class="logo-user">
            </a>
        </div>
    </div>

    <div class="nav-right" style="display: flex; align-items: center;">
        <div class="notification-wrapper" style="position: relative; margin-right: 20px;">
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

        <div class="user-wrapper" style="position: relative;">
            @auth
                <a href="#" id="adminToggle">
                <img src="{{ Storage::url('profile_pictures/' . Auth::user()->profile->profile_picture) }}"
                    alt="User Avatar" class="user-pp">
            </a>
                <div class="user-dropdown" id="userDropdown">
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="fas fa-user-edit"></i> Profile
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>
