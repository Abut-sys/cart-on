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
            background: url('{{ asset('image/background.png') }}') no-repeat center center fixed;
            background-size: cover;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
            overflow: hidden;
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
            border-radius: 30px 0 0 30px;
            padding: 10px 15px;
        }

        .input-group .form-control {
            border-radius: 0 30px 30px 0;
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 30px 20px;
            }

            .title {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>
    <div class="login-card">
        <img src="{{ asset('image/Logo_baru.png') }}" alt="Logo" class="logo">
        <h3 class="title">Sign In</h3>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="email" class="form-control" placeholder="Email" name="email" required>
            </div>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" placeholder="Password" name="password" required>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-login">Sign In</button>
            </div>
            <div class="mb-3">
                <a href="{{ route('google.redirect') }}" class="btn btn-danger btn-login"><i
                        class="fab fa-google me-2"></i> Sign in with Google</a>
            </div>
            <div class="text-center text-muted">
                <span>Don't have an account? <a href="{{ route('register') }}">Sign Up</a></span>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
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
</body>

</html>
