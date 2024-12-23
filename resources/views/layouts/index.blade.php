<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CartON')</title>
    <link href="{{ asset('/') }}assets/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('/') }}assets/plugin/fontawasome/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/sidebar.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/footer.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/dashboard/app.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/costumers/create.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/costumers/index.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/products/index.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/products/create.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/products/edit.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/products/show.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/categories/index.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/categories/create.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/categories/edit.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/brands/index.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/brands/create.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/brands/edit.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/brands/show.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/vouchers/index.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/vouchers/create.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/vouchers/edit.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/vouchers/claim.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/profile/edit.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/profile/admin.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/orders/index.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/home_user/home.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_green.css">
</head>

<body>
    @include('layouts.navbar')
    {{-- content --}}
    <div class="mt-2">
        <div class="container">
            @yield('content')
        </div>
    </div>

    @auth
        @unless (auth()->user()->hasRole('admin'))
            <!-- Konten hanya untuk user yang login dan bukan admin -->
            @include('layouts.footer')
        @endunless
    @endauth

    @guest
        <!-- Konten untuk guest -->
        @include('layouts.footer')
    @endguest

    {{-- Jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- end content --}}
    <script src="{{ asset('/') }}assets/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>

    {{-- sidebar --}}
    <script src="{{ asset('pemai/js/sidebar.js') }}"></script>

    @vite('resources/js/bootstrap.js')
    {{-- notif --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // loadNotifications();

            const notificationIcon = document.getElementById('notificationIcon');
            const notificationCount = document.getElementById('notificationCount');
            const notificationDropdown = document.getElementById('notificationDropdown');

            // Laravel Echo Configuration (untuk real-time notifikasi)
            // window.Echo = new Echo({
            //     broadcaster: 'pusher',
            //     key: '{{ env('PUSHER_APP_KEY') }}',
            //     cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            //     encrypted: true
            // });

            // Menampilkan atau menyembunyikan dropdown notifikasi
            function toggleNotificationDropdown() {
                notificationDropdown.classList.toggle('active');
            }

            if (notificationIcon) {
                notificationIcon.addEventListener('click', toggleNotificationDropdown);
            }

            // Fungsi untuk menandai notifikasi sebagai dibaca
            function markAsRead(notificationIds) {
                fetch("{{ route('markAsRead') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            notification_ids: notificationIds
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            loadNotifications(); // Reload notifications after marking as read
                        }
                    });
            }

            // Fungsi untuk memuat notifikasi terbaru
            function loadNotifications() {
                fetch("{{ route('getNotifications') }}")
                    .then(response => response.json())
                    .then(notifications => {
                        const unreadCount = notifications.filter(notification => !notification.read_at).length;
                        console.log('Unread notifications count:', unreadCount);

                        // Update count notifikasi di elemen HTML
                        notificationCount.textContent = unreadCount;
                        renderNotifications(notifications);
                    });
            }

            // Fungsi untuk merender notifikasi di dropdown
            function renderNotifications(notifications) {
                notificationDropdown.innerHTML = ''; // Menghapus notifikasi yang ada

                notifications.forEach(notification => {
                    const notificationItem = document.createElement('div');
                    notificationItem.classList.add('notification-item');

                    // Jika notifikasi belum dibaca, beri kelas 'unread'
                    if (!notification.read_at) {
                        notificationItem.classList.add('unread');
                    } else {
                        notificationItem.classList.add('read');
                    }

                    notificationItem.innerHTML = `
            <i class="fas fa-info-circle"></i> ${notification.data.message}
        `;

                    // Ketika notifikasi diklik, tandai sebagai dibaca
                    notificationItem.onclick = function() {
                        markAsRead([notification.id]);
                    };

                    notificationDropdown.appendChild(notificationItem);
                });

                if (notifications.length === 0) {
                    notificationDropdown.innerHTML =
                        '<div class="notification-item no-notifications">Tidak ada notifikasi baru</div>';
                }

                const seeAllLink = document.createElement('div');
                seeAllLink.classList.add('notification-item', 'see-all-item');
                seeAllLink.innerHTML =
                    `<a href="{{ route('allNotifications') }}" class="see-all-link">See All</a>`;
                notificationDropdown.appendChild(seeAllLink);
            }

            // Mendengarkan event WebSocket di channel 'admin-notifications'
            Echo.channel('admin-notifications')
                .listen('VoucherStatusChanged', (event) => {
                    console.log('Voucher status changed:', event);
                    const newNotification = document.createElement('div');
                    newNotification.classList.add('notification-item');
                    newNotification.innerHTML = `
                <i class="fas fa-info-circle"></i> Status voucher ${event.voucher.code} has changed to ${event.voucher.status}.
            `;
                    newNotification.onclick = function() {
                        markAsRead([event.voucher.id]);
                        window.location.href = `/notifications/${event.voucher.id}`;
                    };
                    notificationDropdown.prepend(newNotification);
                    notificationCount.textContent = parseInt(notificationCount.textContent) + 1;
                });

            // Load notifications when the page loads
            loadNotifications();
        });
    </script>

    {{-- midtrans --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    {{-- Dashboard --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('dashboard')

    {{-- Flatpickr --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    {{-- Select 2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    {{-- sweet alert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    @if (session('msg'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Success!',
                    text: "{{ session('msg') }}",
                    icon: 'success',
                    confirmButtonText: 'Return'
                });
            });
        </script>
    @endif
</body>

</html>
