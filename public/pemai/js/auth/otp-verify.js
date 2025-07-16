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
