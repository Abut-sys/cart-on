@extends('layouts.index')

@section('title', 'Transaction History')

@section('dongol')
    <div class="th-bg-container">
        <div class="payment-sidebar">
            @include('components.profile-sidebar')
        </div>
        <div class="th-bg-card">
            <div class="th-bg-body">
                <div class="th-header mb-5">
                    <h2 class="th-title">Transaction History</h2>
                    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3">
                        <p class="th-subtitle text-muted mb-0">List of all your transactions</p>

                        <div class="th-filter-group">
                            <button type="button" class="th-filter-btn filter-active" data-status="all">All</button>
                            @foreach (['pending', 'packaged', 'shipped', 'delivered', 'canceled'] as $status)
                                <button type="button" class="th-filter-btn" data-status="{{ $status }}">
                                    {{ ucfirst($status) }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="th-content-wrapper">
                    @forelse ($orders as $order)
                        <div class="th-transaction-card" data-order-status="{{ $order->order_status }}"
                            data-payment-status="{{ $order->payment_status }}">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="tracking-resi-wrapper d-flex align-items-center gap-2">
                                    @if (in_array($order->order_status, ['delivered', 'completed']))
                                        @php
                                            $hasReview = $order->reviewProducts->count() >= $order->checkouts->count(); // semua produk diorder sudah direview
                                        @endphp

                                        @if (!$order->is_confirmed)
                                            <form action="{{ route('orders.confirm', $order->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success">Konfirmasi Pesanan</button>
                                            </form>
                                        @else
                                            @if ($hasReview)
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach ($order->reviewProducts as $review)
                                                        <div class="d-flex align-items-center gap-1">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"
                                                                    style="color: gold;"></i>
                                                            @endfor
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#ratingModal{{ $order->id }}">
                                                    Rating Produk
                                                </button>
                                            @endif
                                        @endif

                                    @endif

                                    @if ($order->tracking_url)
                                        <a href="{{ $order->tracking_url }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary btn-track" style="display: none;">
                                            <i class="fas fa-truck"></i> Lacak Pesanan
                                        </a>
                                    @endif

                                    @if ($order->tracking_number)
                                        <span class="tracking-number text-muted resi-info"
                                            style="font-size: 0.9rem; display: none;">
                                            Resi: {{ $order->tracking_number }}
                                        </span>
                                    @endif
                                </div>

                                <div class="th-status-badges d-flex align-items-center gap-2">
                                    <span class="th-status-badge badge-payment-{{ $order->payment_status }}">
                                        <i class="fas fa-credit-card mr-1"></i>
                                        {{ strtoupper($order->payment_status) }}
                                    </span>
                                    <span class="th-status-badge badge-order-{{ $order->order_status }}">
                                        <i class="fas fa-truck mr-1"></i>
                                        {{ strtoupper($order->order_status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="th-transaction-header">
                                <div class="th-meta-group">
                                    <h3 class="th-transaction-id">{{ $order->unique_order_id }}</h3>
                                    <p class="th-transaction-date">
                                        <i class="fas fa-calendar-alt mr-2"></i>
                                        {{ $order->order_date->format('d F Y, H:i') }}
                                    </p>
                                </div>
                                <div class="th-status-group">
                                    @php
                                        $totalBeforeDiscount = $order->checkouts->sum(
                                            fn($c) => $c->product->price * $c->quantity,
                                        );

                                        $voucher = $order->checkouts->firstWhere('voucher_code', '!=', null)?->voucher;

                                        $totalVoucherDiscount = 0;

                                        if ($voucher) {
                                            if ($voucher->type === 'percentage') {
                                                $totalVoucherDiscount =
                                                    ($voucher->discount_value / 100) * $totalBeforeDiscount;
                                            } else {
                                                $totalVoucherDiscount = $voucher->discount_value;
                                            }
                                        }
                                    @endphp
                                    <p class="th-total-amount">
                                        Total: Rp {{ number_format($order->amount, 0, ',', '.') }}
                                    </p>
                                    <p class="th-shipping-cost text-muted">
                                        Shipping: Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                                    </p>
                                    <p class="th-voucher-cost text-muted">
                                        <span>Voucher: </span>
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
                                    </p>
                                </div>
                            </div>

                            <div class="th-items-container">
                                @foreach ($order->checkouts as $checkout)
                                    @php
                                        $product = $checkout->product;
                                        $mainImage =
                                            $product && $product->images->count()
                                                ? $product->images->first()->image_path
                                                : 'product_images/default.png';
                                        $productName = $product->name ?? 'Produk Tidak Tersedia';
                                        $productPrice = $product->price ?? 0;
                                        $brandName = $product ? optional($product->brand)->name : 'No Brand';
                                        $categoryName = $product
                                            ? optional($product->subCategory)->name
                                            : 'No Category';
                                    @endphp

                                    <div class="th-item-card">
                                        <div class="th-product-card">
                                            <div class="th-product-image-container">
                                                <img src="{{ asset('storage/' . ltrim($mainImage, '/')) }}"
                                                    alt="{{ $productName }}" class="th-product-image">
                                                @unless ($product)
                                                    <div class="th-product-deleted-badge">
                                                        <i class="fas fa-trash-alt"></i> DELETED
                                                    </div>
                                                @endunless
                                            </div>

                                            <div class="th-product-info">
                                                <h4 class="th-product-name">{{ $productName }}</h4>
                                                <div class="th-product-meta">
                                                    <span class="th-product-category"><i class="fas fa-tag"></i>
                                                        {{ $categoryName }}</span>
                                                    <span class="th-product-brand"><i class="fas fa-copyright"></i>
                                                        {{ $brandName }}</span>
                                                </div>
                                                @if ($product && $product->description)
                                                    <p class="th-product-description">
                                                        {{ Str::limit($product->description, 100) }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="th-order-details">
                                            <div class="th-price-info">
                                                <p class="th-unit-price">Rp {{ number_format($productPrice, 0, ',', '.') }}
                                                </p>
                                                <p class="th-product-quantity">x{{ $checkout->quantity }}</p>
                                            </div>
                                            <div class="th-total-price">
                                                Rp {{ number_format($checkout->quantity * $productPrice, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="th-empty-state">
                            <div class="th-empty-logo">
                                <svg xmlns="http://www.w3.org/2000/svg" class="th-empty-icon" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                </svg>
                            </div>
                            <div class="th-empty-text">
                                <h4 class="th-empty-title">No Transactions Found</h4>
                                <p class="th-empty-subtitle">Your transaction list is currently empty</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if ($orders->hasPages())
                    <div class="th-pagination-wrapper mt-5">
                        {{ $orders->links('components.pagination') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Rating Modals --}}
    @foreach ($orders as $order)
        @if ($order->is_confirmed)
            @include('components.ratingPorductListOrder')
        @endif
    @endforeach

    <script src="{{ asset('js/orders-history.js') }}"></script>
@endsection
