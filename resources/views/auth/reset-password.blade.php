<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/auth/reset-password.css">
</head>

<body>
    <div class="reset-password-card">
        <img src="{{ asset('image/Logo_baru.png') }}" alt="Logo" class="logo">
        <h3 class="title">Reset Password</h3>
        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ session('reset_token') }}">

            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" placeholder="New Password" name="password" required>
            </div>

            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation"
                    required>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-reset">Reset Password</button>
            </div>
        </form>
    </div>

    @if (session('msg'))
        <div data-otp-message="{{ session('msg') }}" style="display: none;"></div>
    @endif

    @if (session('otp_verified'))
        <div data-otp-verified style="display: none;"></div>
    @endif

    <script src="{{ asset('/') }}pemai/js/auth/reset-password.js"></script>
</body>

</html>
