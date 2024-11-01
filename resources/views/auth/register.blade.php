<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #66a3a1, #99bc85, #77d1b3);
            background-size: 300% 300%;
            animation: gradient-animation 8s ease infinite;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center; /* Center the card vertically */
            align-items: center; /* Center the card horizontally */
            font-family: 'Arial', sans-serif;
            overflow: hidden;
            position: relative;
        }

        @keyframes gradient-animation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .background-circle {
            position: absolute;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            animation: floating 10s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translateY(0px) translateX(0px); }
            50% { transform: translateY(-15px) translateX(10px); }
            100% { transform: translateY(0px) translateX(0px); }
        }

        .circle1 { width: 150px; height: 150px; top: 5%; left: 5%; animation-delay: 0s; }
        .circle2 { width: 100px; height: 100px; top: 15%; left: 85%; animation-delay: 1s; }
        .circle3 { width: 120px; height: 120px; top: 50%; left: 5%; animation-delay: 2s; }
        .circle4 { width: 80px; height: 80px; top: 65%; left: 80%; animation-delay: 3s; }
        .circle5 { width: 180px; height: 180px; top: 90%; left: 10%; animation-delay: 4s; }
        .circle6 { width: 90px; height: 90px; top: 5%; left: 65%; animation-delay: 5s; }

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
            border-radius: 30px 0 0 30px;
            padding: 8px 10px;
        }

        .input-group .form-control {
            border-radius: 0 30px 30px 0;
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
        <form action="{{ route('register') }}" method="POST" id="registerForm">
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
                        <input type="tel" class="form-control" placeholder="Phone Number" name="phone_number" required>
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
                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                </div>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" required>
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
