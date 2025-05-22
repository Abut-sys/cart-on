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
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/vouchers/claimed.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/profile/edit.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/orders/index.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/home_user/home.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
        document.addEventListener('DOMContentLoaded', () => {
            const icon = document.getElementById('notificationIcon');
            const dropdown = document.getElementById('notificationDropdown');
            const countBadge = document.getElementById('notificationCount');

            if (!icon || !dropdown || !countBadge) return;

            icon.addEventListener('click', () => {
                dropdown.classList.toggle('active');
                if (dropdown.classList.contains('active')) {
                    fetchNotifications();
                }
            });

            async function fetchNotifications() {
                try {
                    const res = await fetch("{{ route('getNotifications') }}");
                    if (!res.ok) throw new Error('failed to fetch notifications');
                    const data = await res.json();
                    updateNotificationUI(data);
                } catch (error) {
                    console.error(error);
                }
            }

            // Update UI dropdown dan badge
            function updateNotificationUI(notifications) {
                const unreadNotifications = notifications.filter(n => !n.read_at);
                countBadge.textContent = unreadNotifications.length;

                dropdown.innerHTML = '';

                if (notifications.length === 0) {
                    dropdown.innerHTML =
                        '<div class="notification-item no-notifications">No notifications.</div>';
                    return;
                }

                notifications.forEach(n => {
                    const div = document.createElement('div');
                    div.classList.add('notification-item');
                    if (!n.read_at) div.classList.add('unread');
                    else div.classList.add('read');

                    div.innerHTML = `
                <i class="fas fa-info-circle"></i>
                ${n.data.message}
            `;

                    if (!n.read_at) {
                        div.style.cursor = 'pointer';
                        div.addEventListener('click', async () => {
                            await markAsRead([n.id]);
                            if (n.data.url) {
                                window.location.href = n.data.url;
                            } else {
                                fetchNotifications();
                            }
                        });
                    }

                    dropdown.appendChild(div);
                });
            }

            async function markAsRead(notificationIds) {
                try {
                    const res = await fetch("{{ route('markAsRead') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            notification_ids: notificationIds
                        })
                    });
                    if (!res.ok) throw new Error('Failed to mark notification read');
                    return await res.json();
                } catch (error) {
                    console.error(error);
                }
            }

            setInterval(fetchNotifications, 60000);

            fetchNotifications();
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
