<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/auth/register.css">
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
        <h3 class="title">Sign Up</h3>
        <form action="{{ url('/register') }}" method="POST" id="registerForm">
            @csrf
            <div class="mb-3 row">
                <div class="col">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" placeholder="Email" name="email" required>
                    </div>
                </div>
                <div class="col">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="tel" class="form-control" placeholder="Phone Number" name="phone_number"
                            id="phoneNumber">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" placeholder="Name" name="name" required>
                </div>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" placeholder="Password" name="password" id="password"
                        required>
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
                    {{ __('Sign Up') }}
                </button>
            </div>
            <div class="text-center text-muted">
                <span>Already have an account?</span> <a href="{{ route('login') }}"> Sign In</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeIcon.classList.toggle('fa-eye-slash');
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const confirmPasswordInput = document.getElementById('confirmPassword');
            const eyeConfirmIcon = document.getElementById('eyeConfirmIcon');
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            eyeConfirmIcon.classList.toggle('fa-eye-slash');
        });

        // Phone number functionality
        const phoneNumberInput = document.getElementById('phoneNumber');

        phoneNumberInput.addEventListener('click', function() {
            if (this.value === '') {
                this.value = '+62 ';
                this.setSelectionRange(4, 4); // Set cursor after +62
            }
        });

        phoneNumberInput.addEventListener('focus', function() {
            if (this.value === '') {
                this.value = '+62 ';
                this.setSelectionRange(4, 4);
            }
        });

        phoneNumberInput.addEventListener('keydown', function(e) {
            // Prevent deletion of +62 prefix
            if ((e.key === 'Backspace' || e.key === 'Delete') && this.selectionStart <= 4) {
                e.preventDefault();
            }
        });

        phoneNumberInput.addEventListener('input', function() {
            // Ensure +62 prefix is always present
            if (!this.value.startsWith('+62 ')) {
                this.value = '+62 ' + this.value.replace(/^\+62\s*/, '');
            }
        });
    </script>
</body>

</html>
