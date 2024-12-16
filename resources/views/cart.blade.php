{{-- @extends('layouts.index')

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
                        $this.removeClass('text-secondary').addClass('text-danger');
                    } else if (response.status === 'removed') {
                        $cartItem.remove();
                    }
                    $('#wishlist-count').text(response.wishlistCount);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText);
                }
            });
        });
    });
</script>

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
</style> --}}

@extends('layouts.index')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row cart-row">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-bold">Sort by</span>
                    <a href="{{ route('cart.index') }}" class="btn btn-light ms-2">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
                <div class="cart-btn-group">
                    <a href="{{ route('cart.index', ['sort' => 'newest']) }}"
                        class="btn {{ request('sort') == 'newest' ? 'btn-dark' : 'btn-light' }}">Newest</a>
                    <a href="{{ route('cart.index', ['sort' => 'bestselling']) }}"
                        class="btn {{ request('sort') == 'bestselling' ? 'btn-dark' : 'btn-light' }}">Bestselling</a>
                    <a href="{{ route('cart.index', ['sort' => 'lowest_price']) }}"
                        class="btn {{ request('sort') == 'lowest_price' ? 'btn-dark' : 'btn-light' }}">Lowest Price</a>
                    <a href="{{ route('cart.index', ['sort' => 'highest_price']) }}"
                        class="btn {{ request('sort') == 'highest_price' ? 'btn-dark' : 'btn-light' }}">Highest Price</a>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-6 g-3 cart-product-list">
                @foreach ($carts as $cart)
                    <div class="col" id="cart-item-{{ $cart->product->id }}">
                        <a href="{{ route('products-all.show', $cart->product->id) }}" class="card cart-card">
                            <img src="{{ asset('storage/' . $cart->product->image_path) }}"
                                alt="{{ $cart->product->name }}" class="cart-card-img-top">
                            <div class="cart-card-body text-center">
                                <h6 class="cart-card-title">{{ $cart->product->name }}</h6>
                                <p class="cart-card-price">
                                    Rp{{ number_format($cart->product->price, 0, ',', '.') }}
                                </p>
                            </div>
                            <i class="fas fa-shopping-cart cart-toggle-btn
                                {{ in_array($cart->product->id, $userCartIds) ? 'text-success' : 'text-secondary' }}"
                                data-product-id="{{ $cart->product->id }}"></i>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $carts->appends(['sort' => request('sort')])->links() }}
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
            var $wishlistItem = $('#cart-item-' + productId);

            $.ajax({
                url: '{{ route('cart.add') }}',
                type: 'POST',
                data: {
                    product_id: productId,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    if (response.status === 'added') {
                        $this.removeClass('text-secondary').addClass('text-danger');
                    } else if (response.status === 'removed') {
                        $wishlistItem.remove();
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
</style>


