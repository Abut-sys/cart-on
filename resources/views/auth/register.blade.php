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
            background: linear-gradient(135deg, #66a3a1, #99bc85, #77d1b3);
            background-size: 300% 300%;
            animation: gradient-animation 8s ease infinite;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
            overflow: hidden;
            position: relative;
        }

        @keyframes gradient-animation {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .background-circle {
            position: absolute;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            animation: floating 10s ease-in-out infinite;
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

        .register-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            padding: 20px 15px;
            width: 600px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
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

        .btn-register {
            background: linear-gradient(135deg, #99bc85, #66a3a1);
            border: none;
            border-radius: 30px;
            padding: 10px 15px;
            color: white;
            width: 100%;
            font-weight: bold;
        }

        .btn-register:hover {
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

        .input-group-text {
            background-color: #f7f7f7;
            border: 1px solid #99bc85;
            border-radius: 30px;
            padding: 8px 10px;
            cursor: pointer;
        }

        #eyeIcon,
        #eyeConfirmIcon {
            font-size: 18px;
            color: #99bc85;
            cursor: pointer;
        }

        #eyeIcon:hover,
        #eyeConfirmIcon:hover {
            color: #66a3a1;
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
