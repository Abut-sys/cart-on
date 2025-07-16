class WishlistHandler {
    constructor(csrfToken) {
        this.csrfToken = csrfToken;
        this.initialize();
    }

    initialize() {
        $(document).on('click', '.home-product-newest-wishlist-icon', (event) => {
            this.handleWishlistClick(event);
        });
    }

    handleWishlistClick(event) {
        event.preventDefault();
        const $button = $(event.currentTarget);
        const productId = $button.data('product-id');

        $.ajax({
            url: '/wishlist/add',
            type: 'POST',
            data: {
                product_id: productId,
                _token: this.csrfToken
            },
            success: (response) => this.handleResponse(response, $button),
            error: (xhr, status, error) => {
                console.error("AJAX error:", error);
            }
        });
    }

    handleResponse(response, $button) {
        if (response.status === 'added') {
            $button.removeClass('text-secondary').addClass('text-danger');
        } else if (response.status === 'removed') {
            $button.removeClass('text-danger').addClass('text-secondary');
        }
        $('#for-badge-count-wishlist').text(response.wishlistCount);
    }
}
