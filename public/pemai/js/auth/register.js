// Password toggle functionality
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

// Phone number functionality
const phoneNumberInput = document.getElementById('phoneNumber');

phoneNumberInput.addEventListener('click', function() {
    if (this.value === '') {
        this.value = '+62 ';
        this.setSelectionRange(4, 4);
    }
});

phoneNumberInput.addEventListener('focus', function() {
    if (this.value === '') {
        this.value = '+62 ';
        this.setSelectionRange(4, 4);
    }
});

phoneNumberInput.addEventListener('keydown', function(e) {
    if ((e.key === 'Backspace' || e.key === 'Delete') && this.selectionStart <= 4) {
        e.preventDefault();
    }
});

phoneNumberInput.addEventListener('input', function() {
    if (!this.value.startsWith('+62 ')) {
        this.value = '+62 ' + this.value.replace(/^\+62\s*/, '');
    }
});
