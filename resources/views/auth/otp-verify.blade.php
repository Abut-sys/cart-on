<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Include your styling here */
        .reset-password-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            padding: 40px 30px;
            width: 600px;
            text-align: center;
            margin: auto;
            margin-top: 50px; /* Add margin for spacing */
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
        .btn-otp {
            background: linear-gradient(135deg, #99bc85, #66a3a1);
            border: none;
            border-radius: 30px;
            padding: 10px 15px;
            color: white;
            width: 100%;
            font-weight: bold;
        }
        .btn-otp:hover {
            background: linear-gradient(135deg, #66a3a1, #99bc85);
        }
    </style>
</head>
<body>
    <div class="reset-password-card">
        <img src="{{ asset('image/Logo_baru.png') }}" alt="Logo" class="logo">
        <h3 class="title">Verify OTP</h3>
        <form action="{{ route('otp.verify') }}" method="POST">
            @csrf
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-key"></i></span>
                <input type="text" class="form-control" placeholder="Enter OTP" name="otp" required>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-otp">Submit OTP</button>
            </div>
        </form>
    </div>

    @if (session('msg'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'OTP Sent!',
                    text: "{{ session('msg') }}",
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif
</body>
</html>
