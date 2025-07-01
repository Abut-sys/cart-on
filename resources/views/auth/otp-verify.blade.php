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
        :root {
            --primary: #66a3a1;
            --secondary: #99bc85;
            --accent: #ff7e5f;
            --dark: #2c3e50;
            --light: #f8f9fa;
        }

        body {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            background-size: 200% 200%;
            animation: gradientShift 8s infinite alternate;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', 'Segoe UI', sans-serif;
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
            background: radial-gradient(circle, rgba(255,255,255,0.25) 0%, rgba(255,255,255,0.1) 70%);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.1);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
            animation: floating 10s ease-in-out infinite;
            z-index: 0;
        }

        .circle1 {
            width: 180px;
            height: 180px;
            top: 5%;
            left: 5%;
            animation-delay: 0s;
        }

        .circle2 {
            width: 120px;
            height: 120px;
            top: 15%;
            left: 85%;
            animation-delay: 1s;
        }

        .circle3 {
            width: 150px;
            height: 150px;
            top: 50%;
            left: 5%;
            animation-delay: 2s;
        }

        .circle4 {
            width: 100px;
            height: 100px;
            top: 65%;
            left: 80%;
            animation-delay: 3s;
        }

        .circle5 {
            width: 200px;
            height: 200px;
            top: 90%;
            left: 10%;
            animation-delay: 4s;
        }

        .circle6 {
            width: 110px;
            height: 110px;
            top: 5%;
            left: 65%;
            animation-delay: 5s;
        }

        .circle7 {
            width: 90px;
            height: 90px;
            top: 75%;
            left: 5%;
            animation-delay: 6s;
        }

        .circle8 {
            width: 130px;
            height: 130px;
            top: 30%;
            left: 90%;
            animation-delay: 7s;
        }

        .circle9 {
            width: 160px;
            height: 160px;
            top: 85%;
            left: 70%;
            animation-delay: 8s;
        }

        .circle10 {
            width: 70px;
            height: 70px;
            top: 10%;
            left: 55%;
            animation-delay: 9s;
        }

        .circle11 {
            width: 120px;
            height: 120px;
            top: 75%;
            left: 50%;
            animation-delay: 11s;
        }

        .circle12 {
            width: 80px;
            height: 80px;
            top: 90%;
            left: 20%;
            animation-delay: 12s;
        }

        .reset-password-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            padding: 40px 35px;
            width: 600px;
            text-align: center;
            position: relative;
            z-index: 10;
            border: 1px solid rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            overflow: hidden;
        }

        .reset-password-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(102, 163, 161, 0.1) 0%, transparent 70%);
            z-index: -1;
        }

        .logo {
            width: 140px;
            height: auto;
            margin-bottom: 15px;
            filter: drop-shadow(0 5px 10px rgba(0, 0, 0, 0.1));
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .title {
            font-size: 38px;
            font-weight: 700;
            margin-bottom: 25px;
            color: var(--dark);
            position: relative;
            display: inline-block;
        }

        .title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 25%;
            width: 50%;
            height: 4px;
            background: linear-gradient(to right, var(--primary), var(--accent));
            border-radius: 2px;
        }

        .form-control {
            border-radius: 30px;
            padding: 12px 20px;
            border: 2px solid #dce7d5;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(102, 163, 161, 0.2);
        }

        .btn-otp {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border: none;
            border-radius: 30px;
            padding: 12px 20px;
            color: white;
            width: 100%;
            font-weight: 600;
            font-size: 18px;
            letter-spacing: 0.5px;
            margin-top: 20px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(102, 163, 161, 0.4);
        }

        .btn-otp:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(102, 163, 161, 0.6);
        }

        .btn-otp:active {
            transform: translateY(1px);
        }

        .btn-otp::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(30deg);
            transition: all 0.5s ease;
        }

        .btn-otp:hover::after {
            left: 120%;
        }

        /* OTP Input Boxes */
        .otp-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .otp-input {
            width: 60px;
            height: 70px;
            text-align: center;
            font-size: 32px;
            font-weight: 700;
            border-radius: 15px;
            border: 2px solid #dce7d5;
            background: #f8f9fa;
            color: var(--dark);
            transition: all 0.3s ease;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .otp-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(255, 126, 95, 0.2), inset 0 2px 5px rgba(0, 0, 0, 0.05);
            outline: none;
            transform: translateY(-3px);
        }

        .otp-input.filled {
            border-color: var(--primary);
            background: rgba(102, 163, 161, 0.1);
            animation: pulse 0.5s;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .hidden-otp {
            display: none;
        }

        .resend-container {
            margin-top: 20px;
            font-size: 16px;
            color: #666;
        }

        .resend-link {
            color: var(--accent);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .resend-link:hover {
            color: var(--primary);
            text-decoration: underline;
        }

        .timer {
            display: inline-block;
            min-width: 60px;
            font-weight: 700;
            color: var(--accent);
        }

        .instruction {
            color: #666;
            margin-bottom: 25px;
            font-size: 16px;
            line-height: 1.6;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            margin-top: 25px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: var(--accent);
            transform: translateX(-5px);
        }

        .back-link i {
            margin-right: 8px;
            transition: transform 0.3s ease;
        }

        .back-link:hover i {
            transform: translateX(-3px);
        }

        .floating-icon {
            position: absolute;
            font-size: 24px;
            color: rgba(255, 255, 255, 0.3);
            animation: floating 8s ease-in-out infinite;
            z-index: 5;
        }

        .icon1 { top: 10%; left: 15%; animation-delay: 0s; }
        .icon2 { top: 20%; left: 85%; animation-delay: 2s; }
        .icon3 { top: 75%; left: 10%; animation-delay: 4s; }
        .icon4 { top: 80%; left: 80%; animation-delay: 6s; }
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

    <div class="floating-icon icon1"><i class="fas fa-lock"></i></div>
    <div class="floating-icon icon2"><i class="fas fa-shield-alt"></i></div>
    <div class="floating-icon icon3"><i class="fas fa-key"></i></div>
    <div class="floating-icon icon4"><i class="fas fa-user-lock"></i></div>

    <div class="reset-password-card">
        <img src="{{ asset('image/Logo_baru.png') }}" alt="Logo" class="logo">
        <h3 class="title">Verify OTP</h3>

        <p class="instruction">We've sent a 6-digit verification code to your email. Please enter it below to verify your identity.</p>

        <form action="{{ route('otp.verify') }}" method="POST">
            @csrf
            <div class="otp-container">
                <input type="text" class="otp-input" maxlength="1" data-index="1" autofocus>
                <input type="text" class="otp-input" maxlength="1" data-index="2">
                <input type="text" class="otp-input" maxlength="1" data-index="3">
                <input type="text" class="otp-input" maxlength="1" data-index="4">
                <input type="text" class="otp-input" maxlength="1" data-index="5">
                <input type="text" class="otp-input" maxlength="1" data-index="6">
            </div>
            <input type="text" name="otp" id="fullOtp" class="hidden-otp" required>

            <div class="mb-3">
                <button type="submit" class="btn btn-otp">
                    <i class="fas fa-paper-plane me-2"></i>Verify OTP
                </button>
            </div>
        </form>

        <div class="resend-container">
            <p>Didn't receive the code?
                <span class="resend-link" id="resendBtn">Resend OTP</span>
                <span id="timerText">(<span class="timer" id="timer">60</span>s)</span>
            </p>
        </div>

        <a href="{{ route('login') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Login
        </a>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('.otp-input');
            const fullOtpInput = document.getElementById('fullOtp');
            const resendBtn = document.getElementById('resendBtn');
            const timerElement = document.getElementById('timer');
            const timerText = document.getElementById('timerText');

            // Initialize timer
            let timeLeft = 60;
            let timerInterval;

            // Start the timer
            function startTimer() {
                timerInterval = setInterval(() => {
                    timeLeft--;
                    timerElement.textContent = timeLeft;

                    if (timeLeft <= 0) {
                        clearInterval(timerInterval);
                        timerText.style.display = 'none';
                        resendBtn.style.display = 'inline';
                    }
                }, 1000);
            }

            // Function to handle OTP input
            function handleOtpInput(e) {
                const currentInput = e.target;
                const currentIndex = parseInt(currentInput.getAttribute('data-index'));
                const inputValue = e.data || currentInput.value;

                // Only allow numbers
                if (inputValue && !/^\d+$/.test(inputValue)) {
                    currentInput.value = '';
                    return;
                }

                // Add filled class for styling
                currentInput.classList.toggle('filled', currentInput.value.length > 0);

                // If input has value, move to next field
                if (currentInput.value.length === 1) {
                    if (currentIndex < otpInputs.length) {
                        otpInputs[currentIndex].focus();
                    }
                }

                // Update the hidden full OTP field
                updateFullOtp();
            }

            // Function to handle backspace
            function handleBackspace(e) {
                const currentInput = e.target;
                const currentIndex = parseInt(currentInput.getAttribute('data-index'));

                if (e.key === 'Backspace' && currentInput.value.length === 0) {
                    if (currentIndex > 1) {
                        otpInputs[currentIndex - 2].focus();
                    }
                }

                // Update the filled class
                currentInput.classList.toggle('filled', currentInput.value.length > 0);

                // Update the hidden full OTP field
                updateFullOtp();
            }

            // Function to update the full OTP value
            function updateFullOtp() {
                let otp = '';
                otpInputs.forEach(input => {
                    otp += input.value;
                });
                fullOtpInput.value = otp;
            }

            // Resend OTP functionality
            resendBtn.addEventListener('click', function() {
                // Show loading spinner
                document.getElementById('').classList.remove('d-none');

                // Simulate API call
                setTimeout(() => {
                    document.getElementById('').classList.add('d-none');

                    // Show success message
                    Swal.fire({
                        title: 'OTP Resent!',
                        text: 'A new verification code has been sent to your email.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });

                    // Reset timer
                    clearInterval(timerInterval);
                    timeLeft = 60;
                    timerElement.textContent = timeLeft;
                    timerText.style.display = 'inline';
                    resendBtn.style.display = 'none';
                    startTimer();
                }, 1500);
            });

            // Add event listeners
            otpInputs.forEach(input => {
                input.addEventListener('input', handleOtpInput);
                input.addEventListener('keydown', handleBackspace);
            });

            // Auto-focus first input on page load
            otpInputs[0].focus();

            // Start the timer
            startTimer();
        });
    </script>
</body>

</html>
