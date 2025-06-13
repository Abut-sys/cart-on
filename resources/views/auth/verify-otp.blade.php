<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/spinner.css">
    <style>
        body {
            background: url('{{ asset('image/background.png') }}') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
            overflow: hidden;
        }

        .otp-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            padding: 20px 15px;
            width: 400px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .logo {
            width: 120px;
            height: auto;
            margin-bottom: 15px;
        }

        .title {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        .form-control {
            border-radius: 30px;
            padding: 10px 15px;
            border: 1px solid #99bc85;
        }

        .form-control:focus {
            border-color: #66a3a1;
            box-shadow: 0 0 5px rgba(102, 163, 161, 0.5);
        }

        .btn-verify {
            background: linear-gradient(135deg, #99bc85, #66a3a1);
            border: none;
            border-radius: 30px;
            padding: 10px 15px;
            color: white;
            width: 100%;
            font-weight: bold;
        }

        .btn-verify:hover {
            background: linear-gradient(135deg, #66a3a1, #99bc85);
        }
    </style>
</head>

<body>
    <div class="otp-card">
        <img src="{{ asset('image/Logo_baru.png') }}" alt="Logo" class="logo">
        <h3 class="title">Verify OTP</h3>
        <form action="{{ route('verify-otp.process') }}" method="POST" id="otpForm">
            @csrf
            <div class="mb-3">
                <input type="text" class="form-control" placeholder="Enter OTP" name="otp" required>
                <input type="hidden" name="email" value="{{ old('email', session('email')) }}">
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-verify">
                    {{ __('Verify OTP') }}
                </button>
            </div>
        </form>
        <form action="{{ route('otp.resend') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ old('email', session('email')) }}">
            <div class="mb-3">
                <button type="submit" class="btn btn-secondary">
                    {{ __('Resend OTP') }}
                </button>
            </div>
        </form>
    </div>

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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

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
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Error!',
                    text: "{{ $errors->first() }}",
                    icon: 'error',
                    confirmButtonText: 'Try Again'
                });
            });
        </script>
    @endif
</body>

</html>
