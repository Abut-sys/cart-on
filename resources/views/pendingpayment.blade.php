@extends('layouts.index')

@section('content')
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
                            <button class="filter-btn active" data-filter="all">
                                <span>All</span>
                                <span class="filter-count">{{ $orders->total() }}</span>
                            </button>
                            <button class="filter-btn" data-filter="pending">
                                <span>Pending</span>
                                <span class="filter-count">{{ $orders->where('payment_status', 'pending')->count() }}</span>
                            </button>
                            <button class="filter-btn" data-filter="failed">
                                <span>Failed</span>
                                <span class="filter-count">{{ $orders->where('payment_status', 'failed')->count() }}</span>
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
                                        <tr class="order-row" data-status="{{ $order->payment_status }}"
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
                                                        <span class="status-badge status-{{ $order->payment_status }}">
                                                            <span class="status-dot"></span>
                                                            {{ ucfirst($order->payment_status) }}
                                                        </span>
                                                        @if ($order->payment_status == 'pending')
                                                            <div class="countdown-timer"
                                                                data-expires="{{ $order->order_date->addHours(24)->format('Y-m-d H:i:s') }}">
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
                                                        @if ($order->payment_status == 'pending')
                                                            <form method="POST"
                                                                action="{{ route('orders.triggerPayment', $order->id) }}"
                                                                style="display:inline-block;">
                                                                @csrf
                                                                <button type="button" class="beten btn-pay btn-sm"
                                                                    title="Pay">
                                                                    <i class="fas fa-credit-card"></i>
                                                                </button>
                                                            </form>

                                                            <form method="POST"
                                                                action="{{ route('orders.cancel', $order->id) }}"
                                                                style="display:inline-block; margin-left: 5px;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="beten btn-cancel btn-sm"
                                                                    title="Cancel">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </form>
                                                        @elseif ($order->payment_status == 'failed')
                                                            <form method="POST"
                                                                action="{{ route('orders.triggerPayment', $order->id) }}"
                                                                style="display:inline-block;">
                                                                @csrf
                                                                <button type="submit" class="beten btn-retry btn-sm"
                                                                    title="Retry Payment">
                                                                    <i class="fas fa-sync-alt"></i> Retry
                                                                </button>
                                                            </form>
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

    @if (isset($snapToken))
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            document.querySelectorAll('.btn-pay').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const form = this.closest('form');
                    const url = form.action;

                    fetch(url, {
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
                                            .catch(() => alert(
                                                'Gagal update status pembayaran.'
                                            ));
                                    },
                                    onPending: function() {
                                        window.location.href =
                                            "{{ route('orders.pending') }}";
                                    },
                                    onError: function() {
                                        alert('Terjadi kesalahan pembayaran.');
                                    },
                                    onClose: function() {
                                        "{{ route('orders.pending') }}";
                                    }
                                });
                            } else if (data.error) {
                                alert(data.error);
                            } else {
                                alert('Gagal mendapatkan token pembayaran.');
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            alert('Terjadi kesalahan jaringan.');
                        });
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.order-row').forEach(row => {
                row.addEventListener('click', function(e) {
                    if (e.target.closest('.action-buttons') || e.target.closest('.btn')) return;

                    const detailsRow = this.nextElementSibling;
                    if (detailsRow && detailsRow.classList.contains('order-details-row')) {
                        detailsRow.classList.toggle('show-details');
                        this.classList.toggle('active-row');
                    }
                });
            });

            // Filter orders
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

            // Search orders by Order ID
            const searchInput = document.getElementById('orderSearch');
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                document.querySelectorAll('.order-row').forEach(row => {
                    const orderId = row.dataset.id.toLowerCase();
                    row.style.display = orderId.includes(searchTerm) ? '' : 'none';
                });
            });

            // Countdown timers
            function updateCountdowns() {
                document.querySelectorAll('.countdown-timer').forEach(timer => {
                    const expires = new Date(timer.dataset.expires).getTime();
                    const now = new Date().getTime();
                    const distance = expires - now;

                    if (distance < 0) {
                        timer.innerHTML =
                            '<i class="fas fa-exclamation-circle"></i> <span class="timer-text">Failed</span>';
                        return;
                    }

                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    timer.querySelector('.timer-text').textContent =
                        `${hours}h ${minutes}m ${seconds}s left`;
                });
            }
            updateCountdowns();
            setInterval(updateCountdowns, 1000);
        });
    </script>
@endsection
