document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Payment Button Handling
    document.querySelectorAll('.btn-pay').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const orderId = this.dataset.id;
            const payUrl = this.dataset.url;

            if (!orderId || !payUrl) {
                alert('Order ID or URL not found');
                return;
            }

            fetch(payUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({}),
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => handlePaymentResponse(data, csrfToken))
            .catch(error => {
                console.error(error);
                alert('Network error occurred.');
            });
        });
    });

    // Cancel Form Confirmation
    document.querySelectorAll('.cancel-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to cancel this order?')) {
                e.preventDefault();
            }
        });
    });

    // Order Row Click Handling
    document.querySelectorAll('.order-row').forEach(row => {
        row.addEventListener('click', function(e) {
            if (e.target.closest('.action-buttons') || e.target.closest('button')) return;
            const detailsRow = this.nextElementSibling;
            if (detailsRow && detailsRow.classList.contains('order-details-row')) {
                detailsRow.classList.toggle('show-details');
                this.classList.toggle('active-row');
            }
        });
    });

    // Filter Buttons
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.dataset.filter;
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            document.querySelectorAll('.order-row').forEach(row => {
                if (filter === 'all') {
                    row.style.display = '';
                } else {
                    row.style.display = row.dataset.status === filter ? '' : 'none';
                }
            });
        });
    });

    // Search Functionality
    const searchInput = document.getElementById('orderSearch');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        document.querySelectorAll('.order-row').forEach(row => {
            const orderId = row.dataset.id.toLowerCase();
            row.style.display = orderId.includes(searchTerm) ? '' : 'none';
        });
    });

    // Countdown Timer
    initializeCountdownTimers();

    // Order Status Polling
    initializeOrderStatusPolling(csrfToken);
});

function handlePaymentResponse(data, csrfToken) {
    if (data.snap_token) {
        snap.pay(data.snap_token, {
            onSuccess: function(result) {
                updatePaymentStatus(result, csrfToken);
            },
            onPending: function() {
                window.location.href = "/orders/pending";
            },
            onError: function() {
                alert('Payment error occurred.');
            },
            onClose: function() {
                // Handle close event if needed
            }
        });
    } else if (data.message) {
        alert(data.message);
    } else if (data.error) {
        alert(data.error);
    } else {
        alert('Failed to get payment token.');
    }
}

function updatePaymentStatus(result, csrfToken) {
    fetch("/status/update", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            order_id: result.order_id || result.transaction_id || '',
            payment_status: result.transaction_status || 'success',
        }),
    })
    .then(res => res.json())
    .then(() => {
        window.location.href = "/orders/history";
    })
    .catch(() => {
        alert('Failed to update payment status.');
    });
}

function initializeCountdownTimers() {
    document.querySelectorAll('.countdown-timer').forEach(function(timer) {
        const expires = new Date(timer.dataset.expires).getTime();
        const textSpan = timer.querySelector('.timer-text');

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = expires - now;

            if (distance <= 0) {
                textSpan.innerText = 'Expired';
                return;
            }

            const hours = Math.floor((distance / (1000 * 60 * 60)) % 24);
            const minutes = Math.floor((distance / (1000 * 60)) % 60);
            const seconds = Math.floor((distance / 1000) % 60);

            textSpan.innerText = `${hours}j ${minutes}m ${seconds}s`;
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
}

function initializeOrderStatusPolling(csrfToken) {
    function updateOrderStatus(orderId, uniqueId) {
        fetch(`/orders/${orderId}/check-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(res => res.json())
        .then(data => {
            if (data.payment_status === 'completed') {
                const row = document.querySelector(`.order-row[data-order-id="${orderId}"]`);
                const statusBadge = row.querySelector(`#order-status-${orderId}`);
                const actionButtons = row.querySelector('.action-buttons');

                statusBadge.textContent = 'Completed';
                statusBadge.className = 'status-badge status-completed';
                actionButtons.innerHTML = `<span class="badge bg-success">Paid</span>`;
            }
        })
        .catch(err => {
            console.warn(`Error checking order ${orderId}:`, err);
        });
    }

    document.querySelectorAll('.order-row').forEach(row => {
        const orderId = row.dataset.orderId;
        const status = row.dataset.status;
        if (status === 'pending') {
            setInterval(() => updateOrderStatus(orderId), 3000);
        }
    });
}
