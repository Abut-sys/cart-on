    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'CartON')</title>
        <link href="{{ asset('/') }}assets/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('/') }}assets/plugin/fontawasome/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('/') }}pemai/css/sidebar.css">
        <link rel="stylesheet" href="{{ asset('/') }}pemai/css/dashboard/app.css">
        <link rel="stylesheet" href="{{ asset('/') }}pemai/css/costumers/create.css">
        <link rel="stylesheet" href="{{ asset('/') }}pemai/css/costumers/index.css">
        <link rel="stylesheet" href="{{ asset('/') }}pemai/css/products/index.css">
        <link rel="stylesheet" href="{{ asset('/') }}pemai/css/products/create.css">
        <link rel="stylesheet" href="{{ asset('/') }}pemai/css/products/edit.css">
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
        <link rel="stylesheet" href="{{ asset('/') }}pemai/css/profile/edit.css">
    </head>

    <body>
        @include('layouts.navbar')
        {{-- content --}}
        <div class="mt-2">
            <div class="container">
                @yield('content')
            </div>
        </div>
        {{-- end content --}}
        <script src="{{ asset('/') }}assets/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
        </script>

        {{-- sidebar --}}
        <script src="{{ asset('pemai/js/sidebar.js') }}"></script>


        {{-- Dashboard --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @yield('dashboard')


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
