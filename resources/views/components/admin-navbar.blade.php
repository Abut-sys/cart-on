<nav class="navbar-top">
    <div class="nav-left" style="display: flex; align-items: center;">
        @auth
            @if (Auth::user()->role == 'admin')
                <div class="menu-icon" style="margin-right: 10px;">
                    <i class="fas fa-bars" id="menuToggle"></i>
                </div>
            @endif
        @endauth
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
                <a href="#" id="adminToggle"
                    style="display: flex; align-items: center; text-decoration: none; color: #666;">
                    <span class="user-name" style="color: #666; font-weight: bold;">{{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down" id="dropdownArrow"
                        style="margin-left: 8px; font-size: 12px; color: #666; transition: transform 0.3s ease;"></i>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const adminToggle = document.getElementById('adminToggle');
        const userDropdown = document.getElementById('userDropdown');
        const dropdownArrow = document.getElementById('dropdownArrow');

        if (adminToggle && userDropdown && dropdownArrow) {
            adminToggle.addEventListener('click', function(e) {
                e.preventDefault();

                // Toggle dropdown visibility
                userDropdown.classList.toggle('show');

                // Rotate arrow
                if (userDropdown.classList.contains('show')) {
                    dropdownArrow.style.transform = 'rotate(180deg)';
                } else {
                    dropdownArrow.style.transform = 'rotate(0deg)';
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!adminToggle.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.remove('show');
                    dropdownArrow.style.transform = 'rotate(0deg)';
                }
            });
        }
    });
</script>
