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
                                                                            Rp{{ number_format($checkout->product->price, 0, ',', '.') }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="details-section">
                                                        <h4>Payment Information</h4>
                                                        @php
                                                            $totalBeforeDiscount = $order->checkouts->sum(
                                                                fn($c) => $c->product->price * $c->quantity,
                                                            );

                                                            $voucher = $order->checkouts->firstWhere(
                                                                'voucher_code',
                                                                '!=',
                                                                null,
                                                            )?->voucher;

                                                            $totalVoucherDiscount = 0;

                                                            if ($voucher) {
                                                                if ($voucher->type === 'percentage') {
                                                                    $totalVoucherDiscount =
                                                                        ($voucher->discount_value / 100) *
                                                                        $totalBeforeDiscount;
                                                                } else {
                                                                    $totalVoucherDiscount = $voucher->discount_value;
                                                                }
                                                            }
                                                        @endphp
                                                        <div class="payment-info">
                                                            @php
                                                                $totalProductAmount = $order->checkouts->sum(function (
                                                                    $checkout,
                                                                ) {
                                                                    return $checkout->product->price *
                                                                        $checkout->quantity;
                                                                });
                                                            @endphp
                                                            <div class="info-row">
                                                                <span>Product_Price:</span>
                                                                <span>Rp{{ number_format($totalProductAmount, 0, ',', '.') }}</span>
                                                            </div>
                                                            <div class="info-row">
                                                                <span>Shipping:</span>
                                                                <span>Rp{{ number_format($order->shipping_cost, 0, ',', '.' ?? 'Not specified') }}</span>
                                                            </div>
                                                            <div class="info-row">
                                                                <span>Voucher:</span>
                                                                <span>
                                                                    @if ($voucher)
                                                                        -Rp{{ number_format($totalVoucherDiscount, 0, ',', '.') }}
                                                                        @if ($voucher->type === 'percentage')
                                                                            ({{ $voucher->discount_value }}%)
                                                                        @endif
                                                                    @else
                                                                        Not applied
                                                                    @endif
                                                                </span>
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
    <script src="{{ asset('js/pending-payment.js') }}"></script>
@endsection
