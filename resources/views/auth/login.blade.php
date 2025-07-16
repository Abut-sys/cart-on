<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/auth/login.css">
</head>

<body>
    <div class="background-circle circle1"></div>
    <div class="background-circle circle2"></div>
    <div class="background-circle circle3"></div>
    <div class="background-circle circle4"></div>
    <div class="background-circle circle5"></div>
    <div class="background-circle circle6"></div>
    <div class="background-circle circle7"></div>
    <div class="background-circle circle8"></div>
    <div class="background-circle circle9"></div>
    <div class="background-circle circle10"></div>
    <div class="background-circle circle11"></div>
    <div class="background-circle circle12"></div>

    <div class="login-card">
        <img src="{{ asset('image/Logo_baru.png') }}" alt="Logo" class="logo">
        <h3 class="title">Sign In</h3>
        <form action="{{ route('login') }}" method="POST" id="loginForm">
            @csrf
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="email" class="form-control" placeholder="Email" name="email" required>
            </div>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" placeholder="Password" name="password" id="password"
                    required>
                <span class="input-group-text" id="togglePassword">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </span>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-login">Sign In</button>
            </div>
            <div class="mb-3">
                <a href="{{ route('google.redirect') }}" class="btn btn-google" id="googleLoginBtn">
                    <img src="{{ asset('image/google-icon.png') }}" alt="Google Icon"
                        style="width: 20px; height: 20px; margin-right: 10px;">
                    Sign in with Google
                </a>
            </div>
            <div class="text-center text-muted">
                <a>Don't have an account? <a href="{{ route('register') }}"> Sign Up</a><br>
                    <a href="{{ route('forgot-password') }}">Forgot Password?</a>
            </div>
        </form>
    </div>

    <!-- Modal Loader -->
    <div id="lgnloaderModal" class="lgn-modal-overlay">
        <div class="lgn-modal-content">
            <div class="lgn-login-animation">
                <div class="lgn-person-icon"></div>
                <div class="lgn-login-dots">
                    <div class="lgn-dot"></div>
                    <div class="lgn-dot"></div>
                    <div class="lgn-dot"></div>
                </div>
            </div>

            <div class="lgn-loading-text">Sedang Login...</div>
            <div class="lgn-loading-subtext">Mohon tunggu sebentar</div>

            <div class="lgn-progress-container">
                <div class="lgn-progress-bar"></div>
            </div>
        </div>
    </div>

    <!-- Failed Login Modal -->
    <div id="lgnFailedModal" class="lgn-failed-modal">
        <div class="lgn-failed-content">
            <div class="lgn-failed-animation">
                <div class="lgn-failed-icon"></div>
            </div>
            <div class="lgn-failed-text">Login Failed!</div>
            <div class="lgn-failed-subtext" id="failedMessage">Invalid email or password</div>
            <button class="lgn-retry-btn" id="lgnRetryBtn">Try Again</button>
        </div>
    </div>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                lgnHideLoader(); // Hide the loading modal if it's showing
                lgnShowFailedModal("{{ $errors->first() }}");
                Swal.fire({
                    title: 'Error!',
                    text: "{{ $errors->first() }}",
                    icon: 'error',
                    confirmButtonText: 'Try Again'
                });
            });
        </script>
    @endif
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

    <script src="{{ asset('/') }}pemai/js/auth/login.js"></script>
</body>

</html>
