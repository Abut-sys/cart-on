@extends('layouts.index')

@section('content')
<div class="container mt-5">
    <h1 class="fw-bold mb-4">Your Cart</h1>

    <!-- Cart Total Section -->
    <div class="d-flex justify-content-between align-items-center bg-light p-3 mb-4 rounded">
        <h5 class="fw-bold">Total Price</h5>
        <div>
            <span id="total-price" class="fw-bold text-success me-3">
                Rp{{ number_format($totalPrice, 0, ',', '.') }}
            </span>
            <a href="{{ route('checkout.process') }}" class="btn btn-success btn-sm">Proceed to Checkout</a>
        </div>
    </div>

    <!-- Cart Product List -->
    <div class="row">
        @forelse ($carts as $cart)
            <div class="col-12 mb-4 bg-white rounded shadow-sm p-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('storage/' . $cart->product->image_path) }}"
                         alt="{{ $cart->product->name }}"
                         class="img-thumbnail"
                         style="width: 80px; height: 80px; object-fit: cover;">

                    <div class="ms-3">
                        <h6 class="mb-1">{{ $cart->product->name }}</h6>
                        <small class="text-muted">
                            Size: {{ $cart->size ?? 'N/A' }}
                        </small>
                        <small class="text-muted">
                            Color: {{ $cart->color ?? 'N/A' }}
                        </small>

                        <p class="fw-bold mb-0 text-success">
                            Rp{{ number_format($cart->product->price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <!-- Cart Item Actions (Remove, Quantity Controls) -->
                <div class="d-flex align-items-center">
                    <form action="{{ route('cart.remove', $cart->id) }}" method="POST" class="me-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>

                    <div class="input-group input-group-sm" style="width: 120px;">
                        <form action="{{ route('cart.decrease', $cart->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm">-</button>
                        </form>

                        <input type="text" class="form-control text-center"
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

        <div class="checkout-card mb-4">
            <div class="checkout-card-header">
                <h5>Payment Summary</h5>
            </div>
            <div class="checkout-card-body">
                @foreach ($carts as $cart)
                    <p class="checkout-summary-item">Product: <span class="checkout-summary-value">{{ $cart->product->name }}</span></p>
                    <p class="checkout-summary-item">Price: <span class="checkout-summary-value">Rp{{ number_format($cart->product->price, 0, ',', '.') }}</span></p>
                    <p class="checkout-summary-item">Quantity: <span class="checkout-summary-value">{{ $cart->quantity }}</span></p>
                    <hr>
                @endforeach
            </div>
        </div>

    </div>
</div>

@endsection

<script>
    $(document).ready(function() {
        $(document).on('click', '.cart-toggle-btn', function(event) {
            event.preventDefault();
            event.stopPropagation();

            var productId = $(this).data('product-id');
            var $this = $(this);
            var $cartItem = $('#cart-item-' + productId);

            $.ajax({
                url: '{{ route('cart.add') }}',
                type: 'POST',
                data: {
                    product_id: productId,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    if (response.status === 'added') {
                        $this.removeClass('text-secondary').addClass('text-success');
                    } else if (response.status === 'removed') {
                        $cartItem.remove();
                    }
                    $('#cart-count').text(response.cartCount);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText);
                }
            });
        });
    });

</script>

<style>
    .cart-card {
        height: 100%;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        text-decoration: none;
        color: inherit;
        position: relative;
    }

    .cart-card:hover {
        text-decoration: none;
        color: inherit;
    }

    .cart-card-img-top {
        height: 150px;
        object-fit: cover;
        width: 100%;
    }

    .cart-card-body {
        padding: 15px;
    }

    .cart-card-title {
        font-size: 12px;
        font-weight: bold;
        margin-bottom: 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        text-align: left;
    }

    .cart-card-price {
        font-size: 12px;
        font-weight: bold;
        color: #99bc85;
        margin-bottom: 8px;
        text-align: left;
    }

    .cart-toggle-btn {
        position: absolute;
        bottom: 5px;
        right: 5px;
        z-index: 10;
        cursor: pointer;
    }

    .cart-btn-group {
        display: flex;
        gap: 10px;
    }

    .cart-btn-group .btn {
        font-size: 14px;
        font-weight: bold;
        margin-right: 1px;
        padding: 8px 12px;
        border-radius: 4px;
    }

    .cart-btn-group .btn-light {
        background-color: #f9f9f9;
        border: px solid #ddd;
        color: #4a7c5b;
    }

    .cart-btn-group .btn-light:hover {
        background-color: #99bc85bd;
        color: #fff;
    }

    .cart-btn-group .btn-dark {
        background-color: #99bc85;
        border: 1px solid #99bc85;
        color: #fff;
    }

    .cart-btn-group .btn-dark:hover {
        background-color: #99bc85bd;
        border-color: #99bc85bd;
        color: #fff;
    }

    /* Checkout Summary Section */
    .checkout-summary-item {
        display: flex;
        justify-content: space-between;
        font-size: 0.9rem;
        color: #333;
    }

    .checkout-summary-item span {
        font-weight: bold;
    }

    .checkout-total-price {
        font-size: 1.5rem;
        font-weight: bold;
        color: #007bff;
        text-align: right;
        margin-top: 1rem;
    }

    .checkout-form-control {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 0.9rem;
    }
</style>

