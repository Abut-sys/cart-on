document.addEventListener("DOMContentLoaded", function() {
    function formatRupiah(value) {
        return 'Rp' + value.toLocaleString('id-ID');
    }

    function updateTotalPrice() {
        let totalPrice = 0;
        let selectedProducts = [];

        document.querySelectorAll('.cart-checkbox').forEach(function(checkbox) {
            if (checkbox.checked) {
                let price = parseInt(checkbox.getAttribute('data-price'));
                let quantity = parseInt(checkbox.getAttribute('data-quantity'));
                totalPrice += price * quantity;
                selectedProducts.push(checkbox.value);
            }
        });

        document.getElementById('cart-total-price').textContent = formatRupiah(totalPrice);
        let checkoutLink = document.getElementById('checkout-button');
        checkoutLink.href = "/checkout/show/0/" + selectedProducts.join(',');
        checkoutLink.disabled = selectedProducts.length === 0;
    }

    function updateIncreaseButtons() {
        document.querySelectorAll('.cart-quantity').forEach(function(input) {
            let stock = parseInt(input.getAttribute('data-stock'));
            let quantity = parseInt(input.value);
            let increaseBtn = input.closest('.cart-quantity-controls').querySelector('.increase-btn');
            increaseBtn.disabled = quantity >= stock;
        });
    }

    function updateCartUI(id, quantity, total, stock) {
        const card = document.querySelector(`.cart-card[data-id="${id}"]`);
        if (!card) return;

        const input = card.querySelector('.cart-quantity');
        const totalEl = card.querySelector('.cart-product-total');
        const increaseBtn = card.querySelector('.increase-btn');
        const checkbox = card.querySelector('.cart-checkbox');

        if (input && totalEl && increaseBtn && checkbox) {
            input.value = quantity;
            checkbox.setAttribute('data-quantity', quantity);
            totalEl.textContent = `Total: ${formatRupiah(total)}`;
            increaseBtn.disabled = quantity >= parseInt(stock);
        }

        updateTotalPrice();
    }

    function updateCartBadgeCount(count = null) {
        const badge = document.getElementById('for-badge-count-cart');

        if (!badge) return;

        if (count === null) {
            count = document.querySelectorAll('.cart-card').length;
        }

        badge.textContent = count;
        badge.style.display = 'inline-block';
    }

    function removeCartItem(id) {
        const card = document.querySelector(`[data-id="${id}"]`)?.closest('.cart-card');
        if (card) card.remove();
        updateTotalPrice();
        updateCartBadgeCount();
    }

    // Event Listeners
    document.querySelectorAll('.cart-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', updateTotalPrice);
    });

    // Increase
    document.querySelectorAll('.btn-increase').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch(`/cart/increase/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        updateCartUI(id, data.quantity, data.total, data.stock);
                    }
                });
        });
    });

    // Decrease
    document.querySelectorAll('.btn-decrease').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch(`/cart/decrease/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (data.deleted) {
                            removeCartItem(id);
                        } else {
                            updateCartUI(id, data.quantity, data.total, data.stock);
                        }
                    }
                });
        });
    });

    // Remove
    document.querySelectorAll('.btn-remove').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch(`/cart/delete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        removeCartItem(id);
                    }
                });
        });
    });

    // Initialize
    updateTotalPrice();
    updateIncreaseButtons();
    updateCartBadgeCount();
});
