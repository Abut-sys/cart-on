@extends('layouts.index')

@section('content')
    <div class="payment-container">
        <!-- Profile Sidebar - Left -->
        <div class="payment-sidebar">
            @include('components.profile-sidebar')
        </div>

        <!-- Main Content - Right -->
        <div class="payment-main">
            <div class="payment-card">
                <div class="card-header">
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
                            <a href="{{ route('products.index') }}" class="btn btn-primary btn-icon">
                                <i class="fas fa-shopping-bag"></i>
                                <span>Continue Shopping</span>
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="order-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Shipping</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr class="order-row" data-status="{{ $order->payment_status }}" data-id="{{ $order->unique_order_id }}">
                                            <td>
                                                <div class="order-cell">
                                                    <span class="order-id">#{{ $order->unique_order_id }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="order-cell">
                                                    <div class="date-group">
                                                        <span class="order-date">{{ $order->order_date->format('d M Y') }}</span>
                                                        <small class="order-time">{{ $order->order_date->format('H:i') }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="order-cell">
                                                    <span class="order-amount">Rp{{ number_format($order->amount, 0, ',', '.') }}</span>
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
                                                        @if($order->payment_status == 'pending')
                                                        <div class="countdown-timer" data-expires="{{ $order->order_date->addHours(24)->format('Y-m-d H:i:s') }}">
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
                                                            <div class="btn-group">
                                                                <a href="{{ route('checkout.process', ['order' => $order->id]) }}" class="btn btn-pay btn-sm">
                                                                    <i class="fas fa-credit-card"></i>
                                                                </a>
                                                                <form action="{{ route('orders.cancel', $order->id) }}"
                                                                    method="POST" class="action-form">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-cancel btn-sm">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @elseif($order->payment_status == 'failed')
                                                            <button class="btn btn-retry btn-sm">
                                                                <i class="fas fa-sync-alt"></i> Retry
                                                            </button>
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
                                                            @foreach($order->checkouts as $checkout)
                                                            <div class="product-item">
                                                                <img src="{{ $checkout->product->image_url }}" alt="{{ $checkout->product->name }}" class="product-image">
                                                                <div class="product-info">
                                                                    <h5>{{ $checkout->product->name }}</h5>
                                                                    <p>Qty: {{ $checkout->quantity }} × Rp{{ number_format($checkout->amount, 0, ',', '.') }}</p>
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
                                                                <span class="total-amount">Rp{{ number_format($order->amount, 0, ',', '.') }}</span>
                                                            </div>
                                                            <div class="info-row">
                                                                <span>Payment Deadline:</span>
                                                                <span class="deadline">{{ $order->order_date->addHours(24)->format('d M Y H:i') }}</span>
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
@endsection

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle order details
            document.querySelectorAll('.order-row').forEach(row => {
                row.addEventListener('click', function(e) {
                    // Don't toggle if clicking on action buttons
                    if (e.target.closest('.action-buttons') || e.target.closest('.btn')) {
                        return;
                    }

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

                    // Update active button
                    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    // Filter rows
                    document.querySelectorAll('.order-row').forEach(row => {
                        if (filter === 'all') {
                            row.style.display = '';
                        } else {
                            row.style.display = row.dataset.status === filter ? '' : 'none';
                        }
                    });
                });
            });

            // Search functionality
            const searchInput = document.getElementById('orderSearch');
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();

                document.querySelectorAll('.order-row').forEach(row => {
                    const orderId = row.dataset.id.toLowerCase();
                    if (orderId.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Countdown timers
            function updateCountdowns() {
                document.querySelectorAll('.countdown-timer').forEach(timer => {
                    const expires = new Date(timer.dataset.expires).getTime();
                    const now = new Date().getTime();
                    const distance = expires - now;

                    if (distance < 0) {
                        timer.innerHTML = '<i class="fas fa-exclamation-circle"></i> <span class="timer-text">Expired</span>';
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

            // Confirm cancel
            document.querySelectorAll('.action-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('Are you sure you want to cancel this order?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
