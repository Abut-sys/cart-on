<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background: url('image/background.png') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
            overflow: hidden;
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
    <div class="register-card">
        <img src="{{ asset('image/Logo_baru.png') }}" alt="Logo" class="logo">
        <h3 class="title">Sign Up</h3>
        <form>
            <div class="mb-3 row">
                <div class="col">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" placeholder="Email" required>
                    </div>
                </div>
                <div class="col">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="tel" class="form-control" placeholder="Phone Number" required>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" placeholder="Name" required>
                </div>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" placeholder="Password" required>
                </div>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" placeholder="Confirm Password" required>
                </div>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-register">Sign Up</button>
            </div>
            <div class="text-center text-muted">
                <a>Already have an account?<a href="{{ route('login') }}"> Sign In</a></a>
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
