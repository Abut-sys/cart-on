@extends('layouts.index')

@section('content')
    <div class="container mt-5">
        <h1 class="fw-bold mb-4">Your Cart</h1>

        <div class="d-flex justify-content-between align-items-center bg-light p-3 mb-4 rounded">
            <h5 class="fw-bold">Total Price</h5>
            <div>
                <span id="cart-total-price" class="fw-bold text-success me-3">
                    Rp{{ number_format($totalPrice, 0, ',', '.') }}
                </span>
                <a href="{{ route('checkout.show', ['id' => 0, 'selected-products' => '']) }}" class="btn btn-success btn-sm"
                    id="checkout-button" disabled>
                    Proceed to Checkout
                </a>
            </div>
        </div>

        <form id="cart-form" method="POST" action="{{ route('cart.selected') }}">
            @csrf
            <div class="row">
                @foreach ($carts as $cart)
                    @php
                        $variant =
                            $cart->size || $cart->color
                                ? \App\Models\SubVariant::where('product_id', $cart->product_id)
                                    ->where('size', $cart->size)
                                    ->where('color', $cart->color)
                                    ->first()
                                : null;
                        $stock = $variant ? $variant->stock : $cart->product->stock;
                    @endphp

                    <div class="col-12 mb-4 cart-card d-flex align-items-center p-3 bg-white shadow-sm rounded"
                        data-id="{{ $cart->id }}">
                        <div class="d-flex align-items-center flex-grow-1">
                            <img src="{{ asset('storage/' . ($cart->product->images->first()->image_path ?? 'default-image.jpg')) }}"
                                alt="{{ $cart->product->name }}" class="cart-product-image rounded" />

                            <div class="ms-3">
                                <h6 class="mb-1 cart-product-name">{{ $cart->product->name }}</h6>
                                <small class="text-muted">Size: {{ $cart->size ?? 'N/A' }} | Color:
                                    {{ $cart->color ?? 'N/A' }}</small>
                                <p class="text-muted mb-1">Stock: <span class="cart-stock">{{ $stock }}</span></p>
                                <p class="fw-bold text-success mb-0">
                                    Rp{{ number_format($cart->product->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <div class="cart-details-right d-flex align-items-center gap-3 me-2">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <p class="cart-product-total mb-2 text-center">
                                    Total: Rp{{ number_format($cart->product->price * $cart->quantity, 0, ',', '.') }}
                                </p>

                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-remove" data-id="{{ $cart->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                    <div class="cart-quantity-controls">
                                        <button type="button" class="btn btn-decrease"
                                            data-id="{{ $cart->id }}">-</button>
                                        <input type="text" class="form-control cart-quantity"
                                            value="{{ $cart->quantity }}" readonly data-stock="{{ $stock }}">
                                        <button type="button" class="btn btn-increase increase-btn"
                                            data-id="{{ $cart->id }}"
                                            {{ $cart->quantity >= $stock ? 'disabled' : '' }}>+</button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check d-flex align-items-center">
                                <input class="cart-checkbox" type="checkbox" value="{{ $cart->id }}"
                                    id="cartItem{{ $cart->id }}" data-price="{{ $cart->product->price }}"
                                    data-quantity="{{ $cart->quantity }}" name="selected_products[]">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </form>
    </div>

    <!-- Tambahkan meta tag CSRF di bagian head layout -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tambahkan script di bagian bawah sebelum closing body tag -->
    <script src="{{ asset('js/cart.js') }}"></script>
@endsection
