@extends('layouts.index')

@section('dongol')
    <div class="payment-container">
        <div class="payment-sidebar">
            @include('components.profile-sidebar')
        </div>

        <div class="payment-main">
            <div class="payment-card">
                <div class="pey-card-header">
                    <div class="header-content">
                        <div class="title-wrapper">
                            <i class="fas fa-clock nav-icon pulse"></i>
                            <h2 class="card-title">Orders Waiting for Payment</h2>
                            <span class="badge badge-pill">{{ $orders->total() }}</span>
                        </div>
                        <p class="subtitle">Manage your pending transactions</p>
                    </div>
                    <div class="header-actions">
                        <div class="filter-group">
                            @php
                                $pendingCount = $orders
                                    ->where('payment_status', \App\Enums\PaymentStatusEnum::Pending)
                                    ->count();
                                $failedCount = $orders
                                    ->where('payment_status', \App\Enums\PaymentStatusEnum::Failed)
                                    ->count();
                            @endphp

                            <button class="filter-btn active" data-filter="all">
                                <span>All</span>
                                <span class="filter-count">{{ $orders->total() }}</span>
                            </button>
                            <button class="filter-btn" data-filter="pending">
                                <span>Pending</span>
                                <span class="filter-count">{{ $pendingCount }}</span>
                            </button>
                            <button class="filter-btn" data-filter="failed">
                                <span>Failed</span>
                                <span class="filter-count">{{ $failedCount }}</span>
                            </button>
                        </div>
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search orders..." id="orderSearch">
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if ($orders->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h3>No pending payments</h3>
                            <p>All your orders are processed. Start shopping to see new orders here.</p>
                            <a href="{{ route('products.index') }}" class="beten btn-primary btn-icon">
                                <i class="fas fa-shopping-bag"></i>
                                <span>Continue Shopping</span>
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="order-table">
                                <thead>
                                    <tr>
                                        <th>Resi</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Shipping</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr class="order-row" data-order-id="{{ $order->id }}"
                                            data-status="{{ strtolower($order->payment_status) }}"
                                            data-id="{{ $order->unique_order_id }}">
                                            <td>
                                                <div class="order-cell">
                                                    <span class="order-id">#{{ $order->unique_order_id }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="order-cell">
                                                    <div class="date-group">
                                                        <span
                                                            class="order-date">{{ $order->order_date->format('d M Y') }}</span>
                                                        <small
                                                            class="order-time">{{ $order->order_date->format('H:i') }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="order-cell">
                                                    <span
                                                        class="order-amount">Rp{{ number_format($order->amount, 0, ',', '.') }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="order-cell">
                                                    <span class="shipping-method">
                                                        <i class="fas fa-truck"></i>
                                                        {{ $order->shipping_service }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="order-cell">
                                                    <div class="status-wrapper">
                                                        {{-- Status Badge --}}
                                                        <span id="order-status-{{ $order->id }}"
                                                            class="status-badge status-{{ strtolower($order->payment_status) }}">
                                                            <span class="status-dot"></span>
                                                            {{ ucfirst($order->payment_status) }}
                                                        </span>

                                                        {{-- Countdown Timer --}}
                                                        @if ($order->payment_status == \App\Enums\PaymentStatusEnum::Pending)
                                                            <div class="countdown-timer" id="countdown-{{ $order->id }}"
                                                                data-expires="{{ \Carbon\Carbon::parse($order->order_date)->addHours(24)->format('Y-m-d H:i:s') }}">
                                                                <i class="fas fa-hourglass-half"></i>
                                                                <span class="timer-text"></span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="order-cell">
                                                    <div class="action-buttons">
                                                        @if ($order->payment_status == \App\Enums\PaymentStatusEnum::Pending)
                                                            {{-- Tombol Pay Now --}}
                                                            <button class="beten btn-pay btn-sm"
                                                                data-id="{{ $order->id }}"
                                                                data-url="{{ route('orders.triggerPayment', ['order' => $order->id]) }}">
                                                                <i class="fas fa-credit-card"></i>
                                                            </button>

                                                            {{-- Tombol Cancel --}}
                                                            <form method="POST"
                                                                action="{{ route('orders.cancel', $order->id) }}"
                                                                class="cancel-form"
                                                                style="display:inline-block; margin-left: 5px;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="beten btn-cancel btn-sm"
                                                                    title="Cancel">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </form>
                                                        @elseif ($order->payment_status == \App\Enums\PaymentStatusEnum::Failed)
                                                            <a href="{{ route('products-all.index') }}"
                                                                class="beten btn-retry btn-sm" title="Retry Payment">
                                                                <i class="fas fa-sync-alt"></i> Shopping
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="order-details-row">
                                            <td colspan="6">
                                                <div class="order-details">
                                                    <div class="details-section">
                                                        <h4>Order Details</h4>
                                                        <div class="products-list">
                                                            @foreach ($order->checkouts as $checkout)
                                                                <div class="product-item">
                                                                    @php
                                                                        $imagePath = optional(
                                                                            $checkout->product->images->first(),
                                                                        )->image_path;
                                                                        $imageUrl = $imagePath
                                                                            ? asset('storage/' . ltrim($imagePath, '/'))
                                                                            : asset('images/default.png');
                                                                    @endphp
                                                                    <img src="{{ $imageUrl }}"
                                                                        alt="{{ $checkout->product->name }}"
                                                                        class="product-image">
                                                                    <div class="product-info">
                                                                        <h5>{{ $checkout->product->name }}</h5>
                                                                        <p>Qty: {{ $checkout->quantity }} ×
                                                                            Rp{{ number_format($checkout->amount, 0, ',', '.') }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="details-section">
                                                        <h4>Payment Information</h4>
                                                        <div class="payment-info">
                                                            <div class="info-row">
                                                                <span>Payment Method:</span>
                                                                <span>{{ $order->payment_method ?? 'Not specified' }}</span>
                                                            </div>
                                                            <div class="info-row">
                                                                <span>Total Amount:</span>
                                                                <span
                                                                    class="total-amount">Rp{{ number_format($order->amount, 0, ',', '.') }}</span>
                                                            </div>
                                                            <div class="info-row">
                                                                <span>Payment Deadline:</span>
                                                                <span
                                                                    class="deadline">{{ $order->order_date->addHours(24)->format('d M Y H:i') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            document.querySelectorAll('.btn-pay').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    showCustomSpinner();

                    const orderId = this.dataset.id;
                    const payUrl = this.dataset.url;

                    if (!orderId || !payUrl) {
                        alert('Order ID or URL not found');
                        hideCustomSpinner();
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
                        .then(data => {
                            if (data.snap_token) {
                                snap.pay(data.snap_token, {
                                    onSuccess: function(result) {
                                        fetch("{{ route('status.update') }}", {
                                                method: 'POST',
                                                headers: {
                                                    'X-CSRF-TOKEN': csrfToken,
                                                    'Accept': 'application/json',
                                                    'Content-Type': 'application/json',
                                                },
                                                body: JSON.stringify({
                                                    order_id: result
                                                        .order_id ||
                                                        result
                                                        .transaction_id ||
                                                        '',
                                                    payment_status: result
                                                        .transaction_status ||
                                                        'success',
                                                }),
                                            })
                                            .then(res => res.json())
                                            .then(resp => {
                                                window.location.href =
                                                    "{{ route('orders.history') }}";
                                            })
                                            .catch(() => {
                                                alert(
                                                    'Failed to update payment status.'
                                                    );
                                                hideCustomSpinner();
                                            });
                                    },
                                    onPending: function() {
                                        window.location.href =
                                            "{{ route('orders.pending') }}";
                                        hideCustomSpinner();
                                    },
                                    onError: function() {
                                        alert('Payment error occurred.');
                                        hideCustomSpinner();
                                    },
                                    onClose: function() {
                                        hideCustomSpinner();
                                    }
                                });
                            } else if (data.message) {
                                alert(data.message);
                                hideCustomSpinner();
                            } else if (data.error) {
                                alert(data.error);
                                hideCustomSpinner();
                            } else {
                                alert('Failed to get payment token.');
                                hideCustomSpinner();
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            alert('Network error occurred.');
                            hideCustomSpinner();
                        });
                });
            });

            document.querySelectorAll('.cancel-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('Are you sure you want to cancel this order?')) {
                        e.preventDefault();
                    }
                });
            });

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

            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const filter = this.dataset.filter;
                    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove(
                        'active'));
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

            const searchInput = document.getElementById('orderSearch');
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                document.querySelectorAll('.order-row').forEach(row => {
                    const orderId = row.dataset.id.toLowerCase();
                    row.style.display = orderId.includes(searchTerm) ? '' : 'none';
                });
            });

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

            document.addEventListener('DOMContentLoaded', () => {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
                                const row = document.querySelector(
                                    `.order-row[data-order-id="${orderId}"]`);
                                const statusBadge = row.querySelector(`#order-status-${orderId}`);
                                const actionButtons = row.querySelector('.action-buttons');

                                // Ubah status badge
                                statusBadge.textContent = 'Completed';
                                statusBadge.className = 'status-badge status-completed';

                                // Hapus tombol aksi
                                actionButtons.innerHTML = `<span class="badge bg-success">Paid</span>`;
                            }
                        })
                        .catch(err => {
                            console.warn(`Error checking order ${orderId}:`, err);
                        });
                }

                function startPolling() {
                    document.querySelectorAll('.order-row').forEach(row => {
                        const orderId = row.dataset.orderId;
                        const status = row.dataset.status;
                        const uniqueId = row.dataset.id;

                        if (status === 'pending') {
                            setInterval(() => {
                                updateOrderStatus(orderId, uniqueId);
                            }, 3000); // 3 detik
                        }
                    });
                }

                startPolling();
            });
        });
    </script>
@endsection
