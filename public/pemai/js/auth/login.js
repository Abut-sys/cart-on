// Toggle Password Visibility
const togglePassword = document.getElementById("togglePassword");
const passwordField = document.getElementById("password");
const eyeIcon = document.getElementById("eyeIcon");

togglePassword.addEventListener("click", function() {
    if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
    }
});

// ========== MODAL LOADER FUNCTIONS ==========
function lgnShowLoader() {
    const modal = document.getElementById('lgnloaderModal');
    modal.classList.add('active');
}

function lgnHideLoader() {
    const modal = document.getElementById('lgnloaderModal');
    modal.classList.remove('active');
}

function lgnShowLoaderWithDelay(seconds = 5) {
    lgnShowLoader();
    setTimeout(() => {
        lgnHideLoader();
    }, seconds * 1000);
}

// ========== FAILED LOGIN FUNCTIONS ==========
function lgnShowFailedModal(message) {
    const modal = document.getElementById('lgnFailedModal');
    const messageElement = document.getElementById('failedMessage');

    if (message) {
        messageElement.textContent = message;
    }

    modal.classList.add('active');
}

function lgnHideFailedModal() {
    const modal = document.getElementById('lgnFailedModal');
    modal.classList.remove('active');
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Show loader when form is submitted
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        lgnShowLoader();
    });

    // Show loader when Google login is clicked
    document.getElementById('googleLoginBtn').addEventListener('click', function(e) {
        lgnShowLoader();
    });

    // Close modal when clicking outside
    document.getElementById('lgnloaderModal').addEventListener('click', function(e) {
        if (e.target === this) {
            lgnHideLoader();
        }
    });

    // Close failed modal when clicking retry button
    document.getElementById('lgnRetryBtn').addEventListener('click', function() {
        lgnHideFailedModal();
    });

    // Close failed modal when clicking outside
    document.getElementById('lgnFailedModal').addEventListener('click', function(e) {
        if (e.target === this) {
            lgnHideFailedModal();
        }
    });
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        lgnHideLoader();
        lgnHideFailedModal();
    }
});

// Hide loader if there's an error (page reload)
window.addEventListener('load', function() {
    lgnHideLoader();
});
