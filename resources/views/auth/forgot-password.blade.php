<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('image/background.png') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Arial', sans-serif;
        }

        .forgot-password-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            padding: 40px 30px;
            width: 600px;
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

        .btn-reset {
            background: linear-gradient(135deg, #99bc85, #66a3a1);
            border: none;
            border-radius: 30px;
            padding: 10px 15px;
            color: white;
            width: 100%;
            font-weight: bold;
        }

        .btn-reset:hover {
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
    </style>
</head>

<body>
    <div class="forgot-password-card">
        <img src="{{ asset('image/Logo_baru.png') }}" alt="Logo" class="logo">
        <h3 class="title">Forgot Password</h3>
        <p class="text-muted">Enter your email address and we’ll send you a link to reset your password.</p>
        <form>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" class="form-control" placeholder="Email Address" required>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-reset">Send Reset Link</button>
            </div>
            <div class="text-center text-muted">
                <a href="{{ route('login') }}">Back to Sign In</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    @if (session('msg'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Email Sent!',
                    text: "{{ session('msg') }}",
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif
</body>

</html>
