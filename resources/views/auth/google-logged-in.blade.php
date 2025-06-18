<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Google Logged In</title>
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/spinner.css">
</head>

<body>
    @if ($user === null)
        <h1>User tidak ditemukan</h1>
    @else
        <h1>Nama: {{ $user->name }}</h1>
        <h2>Email: {{ $user->email }}</h2>
        <img src="{{ $avatar }}" alt="{{ $user->name }}">
    @endif

    <div id="customSpinnerLoader"
        class="d-none position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center"
        style="background: rgba(0,0,0,0.5); z-index: 9999;">
        <div class="spinner">
            <div class="outer">
                <div class="inner tl"></div>
                <div class="inner tr"></div>
                <div class="inner br"></div>
                <div class="inner bl"></div>
            </div>
        </div>
    </div>

    <script>
        function showCustomSpinner() {
            document.getElementById('customSpinnerLoader').classList.remove('d-none');
        }

        function hideCustomSpinner() {
            document.getElementById('customSpinnerLoader').classList.add('d-none');
        }

        window.addEventListener('beforeunload', function() {
            showCustomSpinner();
        });
    </script>
</body>

</html>
