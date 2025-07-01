<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/spinner.css">
    <style>
        body {
            background: linear-gradient(135deg, #66a3a1, #99bc85);
            background-size: 200% 200%;
            animation: gradientShift 8s infinite alternate;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
            overflow: hidden;
            position: relative;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            100% {
                background-position: 100% 50%;
            }
        }

        @keyframes floating {
            0% {
                transform: translateY(0px) translateX(0px);
            }

            50% {
                transform: translateY(-15px) translateX(10px);
            }

            100% {
                transform: translateY(0px) translateX(0px);
            }
        }

        .background-circle {
            position: absolute;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            animation: floating 10s ease-in-out infinite;
        }

        .circle1 {
            width: 150px;
            height: 150px;
            top: 5%;
            left: 5%;
            animation-delay: 0s;
        }

        .circle2 {
            width: 100px;
            height: 100px;
            top: 15%;
            left: 85%;
            animation-delay: 1s;
        }

        .circle3 {
            width: 120px;
            height: 120px;
            top: 50%;
            left: 5%;
            animation-delay: 2s;
        }

        .circle4 {
            width: 80px;
            height: 80px;
            top: 65%;
            left: 80%;
            animation-delay: 3s;
        }

        .circle5 {
            width: 180px;
            height: 180px;
            top: 90%;
            left: 10%;
            animation-delay: 4s;
        }

        .circle6 {
            width: 90px;
            height: 90px;
            top: 5%;
            left: 65%;
            animation-delay: 5s;
        }

        .circle7 {
            width: 70px;
            height: 70px;
            top: 75%;
            left: 5%;
            animation-delay: 6s;
        }

        .circle8 {
            width: 110px;
            height: 110px;
            top: 30%;
            left: 90%;
            animation-delay: 7s;
        }

        .circle9 {
            width: 130px;
            height: 130px;
            top: 85%;
            left: 70%;
            animation-delay: 8s;
        }

        .circle10 {
            width: 50px;
            height: 50px;
            top: 10%;
            left: 55%;
            animation-delay: 9s;
        }

        .circle11 {
            width: 100px;
            height: 100px;
            top: 75%;
            left: 50%;
            animation-delay: 11s;
        }

        .circle12 {
            width: 60px;
            height: 60px;
            top: 90%;
            left: 20%;
            animation-delay: 12s;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            padding: 40px 30px;
            width: 100%;
            max-width: 600px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .logo {
            width: 120px;
            height: auto;
            margin-bottom: 15px;
        }

        .title {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 20px;
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

        .btn-login {
            background: linear-gradient(135deg, #99bc85, #66a3a1);
            border: none;
            border-radius: 30px;
            padding: 10px 15px;
            color: white;
            width: 100%;
            font-weight: bold;
        }

        .btn-google {
            background-color: #e0e0e0;
            /* Ubah latar belakang menjadi abu-abu muda saat hover */
            color: #000000;
            /* Teks tetap berwarna biru saat hover */
            width: 100%;
            border-radius: 30px;
            padding: 10px 15px;
            display: flex;
            font-weight: bold;
            align-items: center;
            justify-content: center;
            /* Center the content */
        }

        .btn-google img {
            margin-right: 10px;
            /* Maintain space between icon and text */
        }

        .btn-google:hover {
            background-color: rgb(238, 238, 238);
            /* Latar belakang putih */
            border: 1px solid #4285F4;
            /* Border berwarna biru Google */
            color: #4285F4;
            /* Teks berwarna biru Google */
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #66a3a1, #99bc85);
        }

        .text-muted {
            font-size: 0.9em;
            color: #666;
        }

        .text-muted a {
            color: #99bc85;
            text-decoration: none;
        }

        .text-muted a:hover {
            text-decoration: underline;
        }

        .input-group {
            margin-bottom: 25px;
        }

        .input-group-text {
            background-color: #f7f7f7;
            border: 1px solid #99bc85;
            border-radius: 30px;
            padding: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #eyeIcon {
            font-size: 18px;
            color: #99bc85;
            cursor: pointer;
        }

        #togglePassword:hover #eyeIcon {
            color: #66a3a1;
        }

        .input-group .form-control:focus {
            border-color: #66a3a1;
            box-shadow: 0 0 5px rgba(102, 163, 161, 0.5);
        }

        .input-group .form-control {
            border-radius: 30px;
            padding: 10px 15px;
            border: 1px solid #99bc85;
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 30px 20px;
            }

            .title {
                font-size: 28px;
            }
        }

        /* ========== MODAL LOADER STYLES ========== */
        /* Modal Styles */
        .lgn-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .lgn-modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .lgn-modal-content {
            background: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transform: scale(0.7);
            transition: transform 0.3s ease;
        }

        .lgn-modal-overlay.active .lgn-modal-content {
            transform: scale(1);
        }

        /* Login Animation */
        .lgn-login-animation {
            width: 120px;
            height: 120px;
            margin: 0 auto 20px;
            position: relative;
        }

        .lgn-person-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #99bc85, #66a3a1);
            border-radius: 50%;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
            animation: lgn-bounce 2s ease-in-out infinite;
        }

        .lgn-person-icon::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 25px;
            height: 25px;
            background: white;
            border-radius: 50%;
            opacity: 0.9;
        }

        .lgn-person-icon::after {
            content: '';
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            width: 35px;
            height: 25px;
            background: white;
            border-radius: 20px 20px 0 0;
            opacity: 0.9;
        }

        .lgn-login-dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 15px;
        }

        .lgn-dot {
            width: 8px;
            height: 8px;
            background: #99bc85;
            border-radius: 50%;
            animation: lgn-pulse 1.5s ease-in-out infinite;
        }

        .lgn-dot:nth-child(1) {
            animation-delay: 0s;
        }

        .lgn-dot:nth-child(2) {
            animation-delay: 0.3s;
        }

        .lgn-dot:nth-child(3) {
            animation-delay: 0.6s;
        }

        .lgn-loading-text {
            color: #333;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .lgn-loading-subtext {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }

        /* Progress Bar */
        .lgn-progress-container {
            width: 100%;
            height: 6px;
            background: #f0f0f0;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 20px;
        }

        .lgn-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #99bc85, #66a3a1);
            border-radius: 3px;
            width: 0%;
            animation: lgn-progress 3s ease-in-out infinite;
        }

        /* Keyframe Animations */
        @keyframes lgn-bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-10px);
            }

            60% {
                transform: translateY(-5px);
            }
        }

        @keyframes lgn-pulse {

            0%,
            100% {
                transform: scale(0.8);
                opacity: 0.5;
            }

            50% {
                transform: scale(1.2);
                opacity: 1;
            }
        }

        @keyframes lgn-progress {
            0% {
                width: 0%;
            }

            50% {
                width: 70%;
            }

            100% {
                width: 100%;
            }
        }

        /* Close Button */
        .lgn-close-btn {
            background: #ff4757;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 15px;
            cursor: pointer;
            font-size: 12px;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        .lgn-close-btn:hover {
            background: #ff3742;
            transform: scale(1.05);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .lgn-modal-content {
                padding: 30px 20px;
            }

            .lgn-login-animation {
                width: 100px;
                height: 100px;
            }

            .lgn-person-icon {
                width: 70px;
                height: 70px;
            }
        }
    </style>
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

    <script>
        // Toggle Password Visibility
        const togglePassword = document.getElementById("togglePassword");
        const passwordField = document.getElementById("password");
        const eyeIcon = document.getElementById("eyeIcon");

        togglePassword.addEventListener("click", function() {
            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        });

        // ========== MODAL LOADER FUNCTIONS ==========
        function lgnShowLoader() {
            const modal = document.getElementById('lgnloaderModal');
            modal.classList.add('active');
        }

        function lgnHideLoader() {
            const modal = document.getElementById('lgnloaderModal');
            modal.classList.remove('active');
        }

        function lgnShowLoaderWithDelay(seconds = 5) {
            lgnShowLoader();
            setTimeout(() => {
                lgnHideLoader();
            }, seconds * 1000);
        }

        // Show loader when form is submitted
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            lgnShowLoader();
        });

        // Show loader when Google login is clicked
        document.getElementById('googleLoginBtn').addEventListener('click', function(e) {
            lgnShowLoader();
        });

        // Close modal when clicking outside
        document.getElementById('lgnloaderModal').addEventListener('click', function(e) {
            if (e.target === this) {
                lgnHideLoader();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                lgnHideLoader();
            }
        });

        // Hide loader if there's an error (page reload)
        window.addEventListener('load', function() {
            lgnHideLoader();
        });
    </script>
</body>

</html>
