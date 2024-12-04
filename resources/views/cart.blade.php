@extends('layouts.index')

@section('content')
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Your Cart</h2>
            <a href="{{ route('products.index') }}" class="btn btn-light">← Continue Shopping</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card shadow-sm p-4 bg-light">
            <!-- Total Price and Buy Button -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5>Total Price</h5>
                <div class="d-flex align-items-center gap-3">
                    <h4 class="text-success">
                        Rp{{ number_format($carts->sum(fn($cart) => $cart->product->price * $cart->quantity), 0, ',', '.') }}
                    </h4>
                    <button class="btn btn-success">Buy</button>
                </div>
            </div>

            @foreach ($carts as $cart)
                <div
                    class="cart-item d-flex align-items-center justify-content-between mb-4 py-3 px-3 bg-white rounded shadow-sm">
                    <div class="d-flex align-items-center gap-4">
                        <img src="{{ asset('storage/' . $cart->product->image_path) }}" alt="{{ $cart->product->name }}"
                            class="cart-item-image">
                        <div>
                            <h5>{{ $cart->product->name }}</h5>
                            <p class="mb-1">Size: {{ $cart->size ?? '-' }}</p>
                            <p class="mb-1">Color: {{ $cart->color ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <h6>Rp{{ number_format($cart->product->price, 0, ',', '.') }}</h6>
                        <div class="input-group quantity-control">
                            <button class="btn btn-outline-secondary btn-sm decrement"
                                data-id="{{ $cart->id }}">-</button>
                            <input type="text" class="form-control form-control-sm text-center"
                                value="{{ $cart->quantity }}" readonly>
                            <button class="btn btn-outline-secondary btn-sm increment"
                                data-id="{{ $cart->id }}">+</button>
                        </div>

                        <form action="{{ route('cart.destroy', $cart->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this item from your cart?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

<style>
    .cart-item {
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    .cart-item-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        max-width: 100px;
    }

    .quantity-control input {
        width: 40px;
        text-align: center;
    }

    .delete-cart-item {
        font-size: 16px;
    }
</style>
