{{-- orders_history.blade.php --}}
@extends('layouts.index')

@section('title', 'Transaction History')

@section('content')
    <div class="th-bg-container">
        <div>
            @include('components.profile-sidebar')
        </div>
        <div class="th-bg-card">
            <div class="th-bg-body">
                <div class="th-header mb-5">
                    <h2 class="th-title">Transaction History</h2>
                    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3">
                        <p class="th-subtitle text-muted mb-0">List of all your transactions</p>

                        {{-- Filter Group --}}
                        <div class="th-filter-group">
                            <button type="button" class="th-filter-btn filter-active" data-status="all">All</button>
                            @foreach (['pending', 'shipped', 'delivered', 'canceled'] as $status)
                                <button type="button" class="th-filter-btn" data-status="{{ $status }}">
                                    {{ ucfirst($status) }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="th-content-wrapper">
                    @forelse($checkouts as $checkout)
                        <div class="th-transaction-card"
                            data-order-status="{{ $checkout->orders->first()->order_status ?? 'pending' }}"
                            data-payment-status="{{ $checkout->orders->first()->payment_status ?? 'pending' }}">
                            <div class="th-transaction-header">
                                <div class="th-meta-group">
                                    <h3 class="th-transaction-id">TRX-{{ strtoupper(substr($checkout->id, 0, 8)) }}</h3>
                                    <p class="th-transaction-date">
                                        <i class="fas fa-calendar-alt mr-2"></i>
                                        {{ $checkout->created_at->format('d F Y, H:i') }}
                                    </p>
                                </div>

                                <div class="th-status-group">
                                    <div class="th-status-badges">
                                        <span class="th-status-badge badge-payment-{{ $checkout->orders->first()->payment_status ?? 'pending' }}">
                                            <i class="fas fa-credit-card mr-1"></i>
                                            {{ strtoupper($checkout->orders->first()->payment_status ?? 'pending') }}
                                        </span>
                                        <span class="th-status-badge badge-order-{{ $checkout->orders->first()->order_status ?? 'pending' }}">
                                            <i class="fas fa-truck mr-1"></i>
                                            {{ strtoupper($checkout->orders->first()->order_status ?? 'pending') }}
                                        </span>
                                    </div>
                                    <p class="th-total-amount">
                                        Total: Rp {{ number_format($checkout->amount, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <div class="th-items-container">
                                @foreach ($checkout->orders as $order)
                                    @php
                                        $product = $order->product;
                                        $mainImage = $product
                                            ? optional($product->images->first())->image_path ?? 'default.png'
                                            : 'default.png';
                                        $productName = $product->name ?? 'Produk Tidak Tersedia';
                                        $productPrice = $product->price ?? 0;
                                        $brandName = $product ? optional($product->brand)->name : 'No Brand';
                                        $categoryName = $product ? optional($product->subCategory)->name : 'No Category';
                                    @endphp

                                    <div class="th-item-card">
                                        <div class="th-product-card">
                                            <div class="th-product-image-container">
                                                <img src="{{ asset('storage/products/' . $mainImage) }}"
                                                    class="th-product-image" alt="{{ $productName }}">
                                                @unless ($product)
                                                    <div class="th-product-deleted-badge">
                                                        <i class="fas fa-trash-alt"></i> DELETED
                                                    </div>
                                                @endunless
                                            </div>

                                            <div class="th-product-info">
                                                <h4 class="th-product-name">
                                                    {{ $productName }}
                                                </h4>

                                                <div class="th-product-meta">
                                                    <span class="th-product-category">
                                                        <i class="fas fa-tag"></i> {{ $categoryName }}
                                                    </span>
                                                    <span class="th-product-brand">
                                                        <i class="fas fa-copyright"></i> {{ $brandName }}
                                                    </span>
                                                </div>

                                                @if ($product && $product->description)
                                                    <p class="th-product-description">
                                                        {{ Str::limit($product->description, 100) }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="th-order-details">
                                            <div class="th-price-info">
                                                <p class="th-unit-price">
                                                    Rp {{ number_format($productPrice, 0, ',', '.') }}
                                                </p>
                                                <p class="th-product-quantity">
                                                    x{{ $order->quantity }}
                                                </p>
                                            </div>
                                            <div class="th-total-price">
                                                Rp {{ number_format($order->quantity * $productPrice, 0, ',', '.') }}
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

                @if ($checkouts->hasPages())
                    <div class="th-pagination-wrapper mt-5">
                        {{ $checkouts->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.th-filter-btn');
            const transactionCards = document.querySelectorAll('.th-transaction-card');

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const status = this.dataset.status;

                    // Update active class
                    filterButtons.forEach(btn => btn.classList.remove('filter-active'));
                    this.classList.add('filter-active');

                    // Filter logic
                    transactionCards.forEach(card => {
                        const orderStatus = card.dataset.orderStatus;

                        if (status === 'all') {
                            card.style.display = 'block';
                        } else {
                            card.style.display = orderStatus === status ? 'block' : 'none';
                        }
                    });
                });
            });
        });
    </script>
@endsection
