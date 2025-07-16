document.addEventListener('DOMContentLoaded', function () {
    initializeStarRatings();
    initializeModalResets();
});

function initializeStarRatings() {
    const starRatings = document.querySelectorAll('.star-rating');

    starRatings.forEach(rating => {
        const stars = rating.querySelectorAll('.star-icon');
        const hiddenInput = rating.parentElement.querySelector('.rating-input');

        stars.forEach((star, index) => {
            // Click event handler
            star.addEventListener('click', () => {
                updateRating(stars, hiddenInput, index + 1);
            });

            // Hover event handler
            star.addEventListener('mouseenter', () => {
                updateStarsOnHover(stars, index + 1);
            });
        });

        // Mouse leave event handler for rating container
        rating.addEventListener('mouseleave', () => {
            resetStarsToRating(stars, hiddenInput);
        });
    });
}

function initializeModalResets() {
    document.querySelectorAll('[id^="ratingModal"]').forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function () {
            resetModal(modal);
        });
    });
}

function updateRating(stars, hiddenInput, ratingValue) {
    hiddenInput.value = ratingValue;

    stars.forEach((s, i) => {
        if (i < ratingValue) {
            s.style.color = '#ffc107';
            s.classList.add('active');
        } else {
            s.style.color = '#ddd';
            s.classList.remove('active');
        }
    });
}

function updateStarsOnHover(stars, hoverValue) {
    stars.forEach((s, i) => {
        s.style.color = i < hoverValue ? '#ffc107' : '#ddd';
    });
}

function resetStarsToRating(stars, hiddenInput) {
    const currentRating = parseInt(hiddenInput.value) || 0;
    stars.forEach((s, i) => {
        s.style.color = i < currentRating ? '#ffc107' : '#ddd';
    });
}

function resetModal(modal) {
    // Reset star ratings
    const starRatings = modal.querySelectorAll('.star-rating');
    starRatings.forEach(rating => {
        const stars = rating.querySelectorAll('.star-icon');
        const hiddenInput = rating.parentElement.querySelector('.rating-input');

        hiddenInput.value = 0;
        stars.forEach(star => {
            star.style.color = '#ddd';
            star.classList.remove('active');
        });
    });

    // Reset textareas
    const textareas = modal.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.value = '';
    });
}
