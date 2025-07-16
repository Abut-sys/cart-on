<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/auth/set-password.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="background-circle circle1"></div>
    <div class="background-circle circle2"></div>
    <div class="background-circle circle3"></div>
    <div class="background-circle circle4"></div>
    <div class="background-circle circle5"></div>
    <div class="background-circle circle6"></div>

    <div class="register-card">
        <img src="{{ asset('image/Logo_baru.png') }}" alt="Logo" class="logo">
        <h3 class="title">Set Your Password</h3>
        <form action="{{ route('password.store') }}" method="POST" id="setPasswordForm">
            @csrf
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" placeholder="New Password" name="password"
                        id="password" required>
                    <span class="input-group-text" id="togglePassword">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" placeholder="Confirm Password"
                        name="password_confirmation" id="confirmPassword" required>
                    <span class="input-group-text" id="toggleConfirmPassword">
                        <i class="fas fa-eye" id="eyeConfirmIcon"></i>
                    </span>
                </div>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-register">
                    {{ __('Set Password') }}
                </button>
            </div>
            <div class="text-center text-muted">
                <span>Already have an account?</span> <a href="{{ route('login') }}"> Sign In</a>
            </div>
        </form>
    </div>



    @if (session('msg'))
        <div data-success-message="{{ session('msg') }}" style="display: none;"></div>
    @endif

    @if ($errors->any())
        <div data-error-message="{{ $errors->first() }}" style="display: none;"></div>
    @endif

    <script src="{{ asset('/') }}pemai/js/auth/set-password.js"></script>
</body>

</html>
