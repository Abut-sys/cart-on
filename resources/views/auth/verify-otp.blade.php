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
    <link rel="stylesheet" href="{{ asset('/') }}pemai/css/auth/verify-otp.css">
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
    <script src="{{ asset('/') }}pemai/js/auth/verify-otp.js"></script>
</body>

</html>
