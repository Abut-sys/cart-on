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
                <a href="{{ route('checkout.process') }}" class="btn btn-success btn-sm">Proceed to Checkout</a>
            </div>
        </div>

        <div class="row">
            @forelse ($carts as $cart)
                <div
                    class="col-12 mb-4 cart-card bg-white rounded shadow-sm p-3 d-flex align-items-center justify-content-between">
                    <div class="cart-product-info d-flex align-items-center">
                        <img src="{{ asset('storage/' . $cart->product->image_path) }}" alt="{{ $cart->product->name }}"
                            class="img-thumbnail cart-product-image" />

                        <div class="ms-3">
                            <h6 class="mb-1 cart-product-name">{{ $cart->product->name }}</h6>
                            <small class="text-muted cart-product-size">
                                Size: {{ $cart->size ?? 'N/A' }}
                            </small>
                            <small class="text-muted cart-product-color">
                                Color: {{ $cart->color ?? 'N/A' }}
                            </small>

                            <p class="fw-bold mb-0 text-success cart-product-price">
                                Rp{{ number_format($cart->product->price, 0, ',', '.') }}
                            </p>
                            <p class="fw-bold mb-0 text-success cart-product-total">
                                Total: Rp{{ number_format($cart->product->price * $cart->quantity, 0, ',', '.') }}
                            </p>

                            <div class="form-check">
                                <input class="form-check-input cart-checkbox" type="checkbox" value="{{ $cart->id }}"
                                    id="cartItem{{ $cart->id }}" data-price="{{ $cart->product->price }}"
                                    data-quantity="{{ $cart->quantity }}">
                                <label class="form-check-label" for="cartItem{{ $cart->id }}"></label>
                            </div>
                        </div>
                    </div>

                    <div class="cart-actions d-flex align-items-center">
                        <form action="{{ route('cart.remove', $cart->id) }}" method="POST" class="me-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>

                        <div class="input-group input-group-sm cart-quantity-controls" style="width: 120px;">
                            <form action="{{ route('cart.decrease', $cart->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary btn-sm">-</button>
                            </form>

                            <input type="text" class="form-control text-center cart-quantity"
                                value="{{ $cart->quantity }}" readonly>

                            <form action="{{ route('cart.increase', $cart->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary btn-sm">+</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <h4 class="text-muted">Your cart is empty!</h4>
                    <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Continue Shopping</a>
                </div>
            @endforelse
        </div>

        <div class="cart-checkout-card mb-4">
            <div class="cart-checkout-card-header">
                <h5>Payment Summary</h5>
            </div>
            <div class="cart-checkout-card-body">
                @foreach ($carts as $cart)
                    <p class="cart-checkout-summary-item">Product: <span
                            class="cart-checkout-summary-value">{{ $cart->product->name }}</span></p>
                    <p class="cart-checkout-summary-item">Price: <span
                            class="cart-checkout-summary-value">Rp{{ number_format($cart->product->price, 0, ',', '.') }}</span>
                    </p>
                    <p class="cart-checkout-summary-item">Quantity: <span
                            class="cart-checkout-summary-value">{{ $cart->quantity }}</span></p>
                    <p class="cart-checkout-summary-item">Total: <span
                            class="cart-checkout-summary-value">Rp{{ number_format($cart->product->price * $cart->quantity, 0, ',', '.') }}</span>
                    </p>
                    <hr>
                @endforeach
            </div>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function updateTotalPrice() {
                let totalPrice = 0;

                document.querySelectorAll('.cart-checkbox').forEach(function(checkbox) {
                    if (checkbox.checked) {
                        let price = parseInt(checkbox.getAttribute('data-price'));
                        let quantity = parseInt(checkbox.getAttribute('data-quantity'));
                        totalPrice += price * quantity;
                    }
                });

                document.getElementById('cart-total-price').textContent = 'Rp' + totalPrice.toLocaleString();
            }

            document.querySelectorAll('.cart-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', updateTotalPrice);
            });

            updateTotalPrice();
        });
    </script>
@endsection

<style>
    .cart-card {
        height: 100%;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        text-decoration: none;
        color: inherit;
        position: relative;
    }

    .cart-card:hover {
        text-decoration: none;
        color: inherit;
    }

    .cart-product-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
    }

    .cart-product-info {
        display: flex;
        flex-direction: row;
        align-items: center;
    }

    .cart-product-name {
        font-weight: bold;
    }

    .cart-product-price,
    .cart-product-total {
        color: #28a745;
        font-weight: bold;
    }

    .cart-actions {
        display: flex;
        align-items: center;
    }

    .cart-quantity-controls {
        display: flex;
        gap: 5px;
    }

    .cart-checkout-card {
        display: flex;
        justify-content: flex-end;
        flex-direction: column;
    }

    .cart-checkout-card-header {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 4px;
    }

    .cart-checkout-card-body {
        padding: 15px;
        background-color: #fff;
        border-radius: 4px;
    }

    .cart-checkout-summary-item {
        display: flex;
        justify-content: space-between;
        font-size: 0.9rem;
        color: #333;
    }

    .cart-checkout-summary-item span {
        font-weight: bold;
    }

    .cart-total-price {
        font-size: 1.5rem;
        font-weight: bold;
        color: #007bff;
        text-align: right;
        margin-top: 1rem;
    }
</style>
