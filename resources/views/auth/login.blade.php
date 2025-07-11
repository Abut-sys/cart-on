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

        // ========== FAILED LOGIN FUNCTIONS ==========
        function lgnShowFailedModal(message) {
            const modal = document.getElementById('lgnFailedModal');
            const messageElement = document.getElementById('failedMessage');

            if (message) {
                messageElement.textContent = message;
            }

            modal.classList.add('active');
        }

        function lgnHideFailedModal() {
            const modal = document.getElementById('lgnFailedModal');
            modal.classList.remove('active');
        }

        // Close modal when clicking retry button
        document.getElementById('lgnRetryBtn').addEventListener('click', function() {
            lgnHideFailedModal();
        });

        // Close modal when clicking outside
        document.getElementById('lgnFailedModal').addEventListener('click', function(e) {
            if (e.target === this) {
                lgnHideFailedModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                lgnHideFailedModal();
            }
        });

        // Show failed modal if there are login errors
        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any())
                lgnHideLoader(); // Hide the loading modal if it's showing
                lgnShowFailedModal("{{ $errors->first() }}");
            @endif
        });

        // Modify your form submission to handle failed login attempts
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            // This would normally be handled by your backend response
            // For demonstration, we'll assume the backend returns an error
            // In a real application, you would check the response from your AJAX call
        });
    </script>
</body>

</html>
