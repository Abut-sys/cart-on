// Function to toggle the forms
function showResetPasswordForm() {
    document.getElementById('otp-form').style.display = 'none';
    document.getElementById('reset-password-form').style.display = 'block';
}

// Handle OTP success message
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('[data-otp-message]')) {
        Swal.fire({
            title: 'OTP Sent!',
            text: document.querySelector('[data-otp-message]').dataset.otpMessage,
            icon: 'success',
            confirmButtonText: 'OK'
        });
    }

    // Check OTP verification and show reset form
    if (document.querySelector('[data-otp-verified]')) {
        showResetPasswordForm();
    }
});
