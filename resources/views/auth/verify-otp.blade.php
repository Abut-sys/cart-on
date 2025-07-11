<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: url('{{ asset('image/background.png') }}') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
            overflow: hidden;
        }

        .otp-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 25px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
            padding: 30px 25px;
            width: 420px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            backdrop-filter: blur(10px);
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

        .btn-verify {
            background: linear-gradient(135deg, #99bc85, #66a3a1);
            border: none;
            border-radius: 30px;
            padding: 12px 20px;
            color: white;
            width: 100%;
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .btn-verify:hover {
            background: linear-gradient(135deg, #66a3a1, #99bc85);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 163, 161, 0.3);
        }

        .btn-verify:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* New styles for OTP inputs */
        .otp-inputs {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            width: 100%;
        }

        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border-radius: 12px;
            border: 2px solid #e0e0e0;
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .otp-input:focus {
            border-color: #66a3a1;
            box-shadow: 0 0 10px rgba(102, 163, 161, 0.3);
            outline: none;
            transform: scale(1.05);
        }

        .otp-input.error {
            border-color: #dc3545;
            animation: shake 0.5s;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        /* Redesigned Resend OTP section */
        .resend-section {
            width: 100%;
            position: relative;
        }

        .resend-info {
            margin-bottom: 15px;
            padding: 12px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 15px;
            border-left: 4px solid #99bc85;
        }

        .resend-info p {
            margin: 0;
            color: #666;
            font-size: 14px;
            line-height: 1.4;
        }

        .resend-info .highlight {
            color: #99bc85;
            font-weight: bold;
        }

        .btn-resend {
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            border: 2px solid #99bc85;
            border-radius: 20px;
            padding: 12px 20px;
            color: #99bc85;
            width: 100%;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(153, 188, 133, 0.2);
        }

        .btn-resend::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-resend:hover:not(:disabled)::before {
            left: 100%;
        }

        .btn-resend:hover:not(:disabled) {
            background: linear-gradient(135deg, #99bc85, #66a3a1);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(153, 188, 133, 0.4);
            border-color: #66a3a1;
        }

        .btn-resend:active:not(:disabled) {
            transform: translateY(-1px);
        }

        .btn-resend:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            border-color: #ddd;
            color: #999;
            background: linear-gradient(135deg, #f5f5f5, #eeeeee);
            box-shadow: none;
            transform: none;
        }

        .btn-resend .btn-content {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
        }

        .btn-resend i {
            margin-right: 8px;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .btn-resend:hover:not(:disabled) i {
            transform: rotate(360deg);
        }

        .countdown-wrapper {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(153, 188, 133, 0.1);
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            color: #99bc85;
        }

        .countdown-wrapper.disabled {
            background: rgba(153, 153, 153, 0.1);
            color: #999;
        }

        /* Animation for countdown */
        @keyframes pulse {
            0% {
                transform: translateY(-50%) scale(1);
            }

            50% {
                transform: translateY(-50%) scale(1.1);
            }

            100% {
                transform: translateY(-50%) scale(1);
            }
        }

        .countdown-wrapper.active {
            animation: pulse 2s infinite;
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .otp-card {
                width: 90%;
                padding: 25px 20px;
            }

            .otp-input {
                width: 45px;
                height: 45px;
                font-size: 20px;
                margin: 0 3px;
            }
        }
    </style>
</head>

<body>
    <div class="otp-card">
        <img src="{{ asset('image/Logo_baru.png') }}" alt="Logo" class="logo">
        <h3 class="title">Verify OTP</h3>
        <form action="{{ route('verify-otp.process') }}" method="POST" id="otpForm">
            @csrf
            <div class="otp-inputs">
                <input type="text" class="otp-input" data-index="0" maxlength="1" pattern="[0-9]" required>
                <input type="text" class="otp-input" data-index="1" maxlength="1" pattern="[0-9]" required>
                <input type="text" class="otp-input" data-index="2" maxlength="1" pattern="[0-9]" required>
                <input type="text" class="otp-input" data-index="3" maxlength="1" pattern="[0-9]" required>
                <input type="text" class="otp-input" data-index="4" maxlength="1" pattern="[0-9]" required>
                <input type="text" class="otp-input" data-index="5" maxlength="1" pattern="[0-9]" required>
            </div>
            <!-- Hidden input for combined OTP -->
            <input type="hidden" name="otp" id="combinedOtp">
            <input type="hidden" name="email" value="{{ old('email', session('verification_email')) }}">
            <input type="hidden" name="phone_number" value="{{ old('phone_number', session('verification_phone')) }}">
            <div class="mb-3">
                <button type="submit" class="btn btn-verify" id="verifyButton">
                    {{ __('Verify OTP') }}
                </button>
            </div>
        </form>

        <div class="resend-section">
            <div class="resend-info">
                <p>Didn't receive the code? Check your <span class="highlight">spam folder</span> or request a new one
                    below.</p>
            </div>
            <form action="{{ route('otp.resend') }}" method="POST" id="resendForm">
                @csrf
                <input type="hidden" name="email" value="{{ old('email', session('verification_email')) }}">
                <input type="hidden" name="phone_number"
                    value="{{ old('phone_number', session('verification_phone')) }}">
                <div class="mb-3">
                    <button type="submit" class="btn btn-resend" id="resendButton" disabled>
                        <div class="btn-content">
                            <i class="fas fa-paper-plane"></i>
                            <span id="resendText">Resend OTP Code</span>
                        </div>
                        <div class="countdown-wrapper active disabled" id="countdownWrapper">
                            <span id="countdown">60s</span>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('.otp-input');
            const combinedOtpInput = document.getElementById('combinedOtp');
            const otpForm = document.getElementById('otpForm');
            const verifyButton = document.getElementById('verifyButton');

            // Function to combine OTP values
            function combineOtpValues() {
                let combinedOtp = '';
                otpInputs.forEach(input => {
                    combinedOtp += input.value;
                });
                combinedOtpInput.value = combinedOtp;

                // Enable/disable verify button based on completion
                verifyButton.disabled = combinedOtp.length !== 6;

                return combinedOtp;
            }

            // Function to clear error states
            function clearErrors() {
                otpInputs.forEach(input => {
                    input.classList.remove('error');
                });
            }

            // Function to show error states
            function showErrors() {
                otpInputs.forEach(input => {
                    input.classList.add('error');
                });
            }

            otpInputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    // Clear errors when user starts typing
                    clearErrors();

                    // Only allow numbers
                    this.value = this.value.replace(/[^0-9]/g, '');

                    if (this.value.length === 1) {
                        if (index < otpInputs.length - 1) {
                            otpInputs[index + 1].focus();
                        }
                    }

                    // Update combined OTP
                    combineOtpValues();
                });

                // Allow backspace to move to previous input
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                        otpInputs[index - 1].focus();
                        otpInputs[index - 1].value = '';
                        combineOtpValues();
                    }
                });

                // Handle paste event
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const paste = (e.clipboardData || window.clipboardData).getData('text');
                    const numbers = paste.replace(/[^0-9]/g, '');

                    clearErrors();

                    for (let i = 0; i < numbers.length && (index + i) < otpInputs.length; i++) {
                        otpInputs[index + i].value = numbers[i];
                    }

                    // Focus on the next empty input or the last one
                    const nextEmptyIndex = Math.min(index + numbers.length, otpInputs.length - 1);
                    otpInputs[nextEmptyIndex].focus();

                    combineOtpValues();
                });
            });

            // Form submission handler
            otpForm.addEventListener('submit', function(e) {
                const combinedOtp = combineOtpValues();

                if (combinedOtp.length !== 6) {
                    e.preventDefault();
                    showErrors();
                    Swal.fire({
                        title: 'Incomplete OTP',
                        text: 'Please enter all 6 digits of the OTP code.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                // Show loading state
                verifyButton.disabled = true;
                verifyButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';
            });

            // Enhanced countdown timer for resend OTP button
            let timeLeft = 60;
            const resendButton = document.getElementById('resendButton');
            const countdownElement = document.getElementById('countdown');
            const countdownWrapper = document.getElementById('countdownWrapper');
            const resendTextElement = document.getElementById('resendText');

            function updateCountdown() {
                countdownElement.textContent = `${timeLeft}s`;
                timeLeft--;

                if (timeLeft < 0) {
                    clearInterval(countdownInterval);
                    resendButton.disabled = false;
                    countdownWrapper.style.display = 'none';
                    countdownWrapper.classList.remove('active');
                    countdownWrapper.classList.add('disabled');
                    resendTextElement.textContent = 'Resend OTP Code';

                    // Add a subtle notification that resend is available
                    resendButton.style.borderColor = '#28a745';
                    resendButton.style.color = '#28a745';
                    setTimeout(() => {
                        resendButton.style.borderColor = '#99bc85';
                        resendButton.style.color = '#99bc85';
                    }, 2000);
                }

                // Change color when time is running low
                if (timeLeft <= 10) {
                    countdownWrapper.style.background = 'rgba(220, 53, 69, 0.1)';
                    countdownWrapper.style.color = '#dc3545';
                }
            }

            let countdownInterval = setInterval(updateCountdown, 1000);

            // Handle resend button click
            resendButton.addEventListener('click', function(e) {
                if (!this.disabled) {
                    e.preventDefault();

                    // Show loading state
                    const originalContent = this.innerHTML;
                    this.innerHTML =
                        '<div class="btn-content"><i class="fas fa-spinner fa-spin"></i><span>Sending...</span></div>';
                    this.disabled = true;

                    // Simulate API call delay
                    setTimeout(() => {
                        // Reset the countdown
                        timeLeft = 60;
                        countdownWrapper.style.display = 'block';
                        countdownWrapper.classList.add('active');
                        countdownWrapper.classList.remove('disabled');
                        countdownWrapper.style.background = 'rgba(153, 188, 133, 0.1)';
                        countdownWrapper.style.color = '#99bc85';
                        countdownElement.textContent = `${timeLeft}s`;
                        resendTextElement.textContent = 'Resend OTP Code';

                        // Restore original button content
                        this.innerHTML = originalContent;

                        // Clear OTP inputs
                        otpInputs.forEach(input => {
                            input.value = '';
                            input.classList.remove('error');
                        });
                        otpInputs[0].focus();
                        combineOtpValues();

                        countdownInterval = setInterval(updateCountdown, 1000);

                        // Submit the form
                        document.getElementById('resendForm').submit();
                    }, 1000);
                }
            });

            // Initialize
            combineOtpValues();
        });
    </script>
</body>

</html>
