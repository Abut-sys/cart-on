document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    eyeIcon.classList.toggle('fa-eye-slash');
});

document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const eyeConfirmIcon = document.getElementById('eyeConfirmIcon');
    const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    confirmPasswordInput.setAttribute('type', type);
    eyeConfirmIcon.classList.toggle('fa-eye-slash');
});

// Sweet Alert Notifications
if (document.querySelector('[data-success-message]')) {
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Success!',
            text: document.querySelector('[data-success-message]').getAttribute('data-success-message'),
            icon: 'success',
            confirmButtonText: 'Return'
        });
    });
}

if (document.querySelector('[data-error-message]')) {
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Error!',
            text: document.querySelector('[data-error-message]').getAttribute('data-error-message'),
            icon: 'error',
            confirmButtonText: 'Try Again'
        });
    });
}
