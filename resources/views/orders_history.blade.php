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

                        <div class="th-filter-group">
                            <a href="{{ request()->url() }}"
                                class="btn btn-sm th-filter-btn {{ !request('status') ? 'active' : '' }}">
                                All
                            </a>
                            @foreach(['pending', 'shipped', 'delivered', 'canceled'] as $status)
                                <a href="?status={{ $status }}"
                                    class="btn btn-sm th-filter-btn {{ request('status') === $status ? 'active' : '' }}">
                                    {{ ucfirst($status) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="th-content-wrapper">
                    @forelse($checkouts as $checkout)
                        <div class="th-transaction-card">
                            <div class="th-transaction-header">
                                <div class="th-meta-group">
                                    <h3 class="th-transaction-id">TRX-{{ strtoupper(substr($checkout->transaction_id, 0, 8)) }}
                                    </h3>
                                    <p class="th-transaction-date">
                                        <i class="fas fa-calendar-alt mr-2"></i>
                                        {{ $checkout->created_at->format('d F Y, H:i') }}
                                    </p>
                                </div>

                                <div class="th-status-group">
                                    <span class="th-status-badge badge-{{ $checkout->status }}">
                                        {{ strtoupper($checkout->status) }}
                                    </span>
                                    <p class="th-total-amount">
                                        Rp {{ number_format($checkout->total_amount, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <div class="th-items-container">
                                @foreach ($checkout->orders as $order)
                                    <div class="th-item-row">
                                        <div class="th-product-info">
                                            <img src="{{ asset('storage/products/' . $order->product->image) }}"
                                                class="th-product-image" alt="{{ $order->product->name }}">
                                            <div class="th-product-details">
                                                <h4 class="th-product-name">{{ $order->product->name }}</h4>
                                                <div class="d-flex align-items-center gap-2">
                                                    <p class="th-product-quantity mb-0">Qty: {{ $order->quantity }}</p>
                                                    <span class="th-order-status badge-{{ $order->order_status }}">
                                                        {{ strtoupper($order->order_status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="th-price-info">
                                            <p class="th-unit-price">
                                                Rp {{ number_format($order->price, 0, ',', '.') }}/item
                                            </p>
                                            <p class="th-total-price">
                                                Rp {{ number_format($order->price * $order->quantity, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="th-empty-state">
                            <div class="th-empty-logo">
                                <svg xmlns="http://www.w3.org/2000/svg" class="th-empty-icon" viewBox="0 0 24 24"
                                    stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                </svg>
                            </div>
                            <div class="th-empty-text">
                                <h4 class="th-empty-title">No Transactions Found</h4>
                                <p class="th-empty-subtitle">Your transaction list is currently empty</p>
                                <p class="th-empty-hint">Start shopping to see your transactions here!</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if($checkouts->hasPages())
                    <div class="th-pagination-wrapper mt-5">
                        {{ $checkouts->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection