<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/auth/otp-verify.css">
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
