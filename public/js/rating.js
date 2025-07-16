document.addEventListener('DOMContentLoaded', function () {
    const ratingInputs = document.querySelectorAll('input[name="rating"]');
    const ratingText = document.getElementById('ratingText');
    const commentTextarea = document.querySelector('textarea[name="comment"]');
    const charCount = document.getElementById('charCount');
    const submitBtn = document.getElementById('submitBtn');

    const ratingMessages = {
        1: "😞 Sangat Tidak Puas",
        2: "😐 Tidak Puas",
        3: "😊 Cukup Puas",
        4: "😃 Puas",
        5: "🤩 Sangat Puas"
    };

    // Initialize pada page load jika ada old input
    const checkedRating = document.querySelector('input[name="rating"]:checked');
    if (checkedRating) {
        const value = parseInt(checkedRating.value);
        updateStarDisplay(value);
        ratingText.textContent = ratingMessages[value];
        ratingText.style.color = value <= 2 ? '#dc3545' : value <= 3 ? '#ffc107' : '#28a745';
        submitBtn.disabled = false;
    }

    // Handle star rating
    ratingInputs.forEach((input, index) => {
        const starWrapper = input.parentElement;

        // Handle click events
        input.addEventListener('change', function() {
            const value = parseInt(this.value);
            updateStarDisplay(value);
            ratingText.textContent = ratingMessages[value];
            ratingText.style.color = value <= 2 ? '#dc3545' : value <= 3 ? '#ffc107' : '#28a745';
            submitBtn.disabled = false;
            submitBtn.classList.add('btn-pulse');

            // Add sparkle effect to selected star
            createSparkles(this.nextElementSibling);
        });

        // Handle hover preview
        starWrapper.addEventListener('mouseenter', function() {
            if (!document.querySelector('input[name="rating"]:checked')) {
                const hoverValue = parseInt(input.value);
                updateHoverPreview(hoverValue);
            }
        });
    });

    // Remove hover preview when leaving star rating area
    document.querySelector('.star-rating').addEventListener('mouseleave', function() {
        document.querySelectorAll('.star-wrapper').forEach(wrapper => {
            wrapper.classList.remove('hover-preview');
        });
    });

    // Character counter
    if (commentTextarea) {
        commentTextarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length;
            charCount.style.color = length > 450 ? '#dc3545' : '#6c757d';

            if (length > 500) {
                this.value = this.value.substring(0, 500);
                charCount.textContent = 500;
            }
        });
    }

    // Helper functions
    function updateStarDisplay(value) {
        document.querySelectorAll('.star-wrapper').forEach(wrapper => {
            wrapper.classList.remove('filled');
        });

        for (let i = 0; i < value; i++) {
            document.querySelectorAll('.star-wrapper')[i].classList.add('filled');
        }
    }

    function updateHoverPreview(value) {
        document.querySelectorAll('.star-wrapper').forEach(wrapper => {
            wrapper.classList.remove('hover-preview');
        });

        for (let i = 0; i < value; i++) {
            document.querySelectorAll('.star-wrapper')[i].classList.add('hover-preview');
        }
    }

    // Sparkle effect function
    function createSparkles(element) {
        for (let i = 0; i < 6; i++) {
            setTimeout(() => {
                const sparkle = document.createElement('div');
                sparkle.innerHTML = '✨';
                sparkle.style.cssText = `
                    position: absolute;
                    font-size: 12px;
                    pointer-events: none;
                    animation: sparkleFloat 1s ease-out forwards;
                    left: ${Math.random() * 100}%;
                    top: ${Math.random() * 100}%;
                `;
                element.appendChild(sparkle);

                setTimeout(() => sparkle.remove(), 1000);
            }, i * 100);
        }
    }

    // Add sparkle animation CSS
    const sparkleStyle = document.createElement('style');
    sparkleStyle.textContent = `
        @keyframes sparkleFloat {
            0% { transform: translateY(0) scale(0); opacity: 1; }
            100% { transform: translateY(-30px) scale(1); opacity: 0; }
        }
        .btn-pulse {
            animation: btnPulse 0.6s ease-out;
        }
        @keyframes btnPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    `;
    document.head.appendChild(sparkleStyle);

    // Modal timing logic
    async function checkUserRating() {
        try {
            const response = await fetch('/rating/check', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    information_id: document.querySelector('input[name="information_id"]').value
                })
            });

            if (response.ok) {
                const data = await response.json();
                return data.hasRated;
            }
            return false;
        } catch (error) {
            console.error('Error checking rating:', error);
            return false;
        }
    }

    function showRatingModal() {
        const ratingModal = new bootstrap.Modal(document.getElementById('ratingModal'), {
            backdrop: 'static',
            keyboard: false
        });
        ratingModal.show();

        // Add entrance animation
        const modalDialog = document.querySelector('#ratingModal .modal-dialog');
        modalDialog.style.animation = 'modalSlideIn 0.5s ease-out';
    }

    // Add modal entrance animation
    const modalStyle = document.createElement('style');
    modalStyle.textContent = `
        @keyframes modalSlideIn {
            0% { transform: translateY(-50px) scale(0.8); opacity: 0; }
            100% { transform: translateY(0) scale(1); opacity: 1; }
        }
    `;
    document.head.appendChild(modalStyle);

    // Check for login time and show modal
    const loginTime = document.querySelector('meta[name="login-time"]')?.content;
    if (loginTime) {
        checkUserRating().then(hasRated => {
            if (hasRated) {
                return;
            }

            const now = new Date();
            const loginDate = new Date(loginTime);
            const diffMs = now - loginDate;
            const diffMinutes = diffMs / 1000 / 60;

            if (diffMinutes >= 2) {
                showRatingModal();
            } else {
                const waitMs = (2 - diffMinutes) * 60 * 1000;
                setTimeout(() => {
                    showRatingModal();
                }, waitMs);
            }
        });
    }

    // Show modal if there are errors
    if (document.querySelector('.alert-danger')) {
        showRatingModal();
    }
});
