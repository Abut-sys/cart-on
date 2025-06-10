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

                    <div class="col-12 mb-4 cart-card bg-white rounded shadow-sm p-3 d-flex align-items-center justify-content-between"
                        data-id="{{ $cart->id }}">
                        {{-- detail product --}}
                        <div class="cart-product-info d-flex align-items-center">
                            <img src="{{ asset('storage/' . ($cart->product->images->first()->image_path ?? 'default-image.jpg')) }}"
                                alt="{{ $cart->product->name }}" class="img-thumbnail cart-product-image" />

                            <div class="ms-3">
                                <h6 class="mb-1 cart-product-name">{{ $cart->product->name }}</h6>
                                <small class="text-muted cart-product-size">Size: {{ $cart->size ?? 'N/A' }}</small>
                                <small class="text-muted cart-product-color">Color: {{ $cart->color ?? 'N/A' }}</small>
                                <p class="text-muted">Stock: <span class="cart-stock">{{ $stock }}</span></p>

                                <p class="fw-bold mb-0 text-success cart-product-price">
                                    Rp{{ number_format($cart->product->price, 0, ',', '.') }}
                                </p>
                                <p class="fw-bold mb-0 text-success cart-product-total">
                                    Total: Rp{{ number_format($cart->product->price * $cart->quantity, 0, ',', '.') }}
                                </p>

                                {{-- checkbox --}}
                                <div class="form-check">
                                    <input class="form-check-input cart-checkbox" type="checkbox"
                                        value="{{ $cart->id }}" id="cartItem{{ $cart->id }}"
                                        data-price="{{ $cart->product->price }}" data-quantity="{{ $cart->quantity }}"
                                        name="selected_products[]">
                                    <label class="form-check-label" for="cartItem{{ $cart->id }}"></label>
                                </div>
                            </div>
                        </div>

                        {{-- remove --}}
                        <div class="cart-actions d-flex align-items-center">
                            <button type="button" class="btn btn-outline-danger btn-sm btn-remove"
                                data-id="{{ $cart->id }}">
                                <i class="fas fa-trash"></i>
                            </button>

                            {{-- quantity --}}
                            <div class="input-group input-group-sm cart-quantity-controls" style="width: 120px;">
                                <button type="button" class="btn btn-outline-secondary btn-sm btn-decrease"
                                    data-id="{{ $cart->id }}">-</button>
                                <input type="text" class="form-control text-center cart-quantity"
                                    value="{{ $cart->quantity }}" readonly data-stock="{{ $stock }}">
                                <button type="button" class="btn btn-outline-secondary btn-sm increase-btn btn-increase"
                                    data-id="{{ $cart->id }}"
                                    {{ $cart->quantity >= $stock ? 'disabled' : '' }}>+</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function formatRupiah(value) {
                return 'Rp' + value.toLocaleString('id-ID');
            }

            function updateTotalPrice() {
                let totalPrice = 0;
                let selectedProducts = [];

                document.querySelectorAll('.cart-checkbox').forEach(function(checkbox) {
                    if (checkbox.checked) {
                        let price = parseInt(checkbox.getAttribute('data-price'));
                        let quantity = parseInt(checkbox.getAttribute('data-quantity'));
                        totalPrice += price * quantity;
                        selectedProducts.push(checkbox.value);
                    }
                });

                document.getElementById('cart-total-price').textContent = formatRupiah(totalPrice);
                // checkout
                let checkoutLink = document.getElementById('checkout-button');
                checkoutLink.href = "{{ route('checkout.show', ['id' => 0, 'selected-products' => '']) }}" +
                    selectedProducts.join(',');
                checkoutLink.disabled = selectedProducts.length === 0;
            }

            function updateIncreaseButtons() {
                document.querySelectorAll('.cart-quantity').forEach(function(input) {
                    let stock = parseInt(input.getAttribute('data-stock'));
                    let quantity = parseInt(input.value);
                    let increaseBtn = input.closest('.cart-quantity-controls').querySelector(
                        '.increase-btn');
                    increaseBtn.disabled = quantity >= stock;
                });
            }

            function updateCartUI(id, quantity, total, stock) {
                const card = document.querySelector(`.cart-card[data-id="${id}"]`);
                if (!card) return;

                const input = card.querySelector('.cart-quantity');
                const totalEl = card.querySelector('.cart-product-total');
                const increaseBtn = card.querySelector('.increase-btn');
                const checkbox = card.querySelector('.cart-checkbox');

                if (input && totalEl && increaseBtn && checkbox) {
                    input.value = quantity;
                    checkbox.setAttribute('data-quantity', quantity);
                    totalEl.textContent = `Total: ${formatRupiah(total)}`;
                    increaseBtn.disabled = quantity >= parseInt(stock);
                }

                updateTotalPrice();
            }

            function updateCartBadgeCount(count = null) {
                const badge = document.getElementById('for-badge-count-cart');

                if (!badge) return;

                if (count === null) {
                    count = document.querySelectorAll('.cart-card').length;
                }

                badge.textContent = count;
                badge.style.display = 'inline-block';
            }

            function removeCartItem(id) {
                const card = document.querySelector(`[data-id="${id}"]`)?.closest('.cart-card');
                if (card) card.remove();
                updateTotalPrice();
                updateCartBadgeCount();
            }

            document.querySelectorAll('.cart-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', updateTotalPrice);
            });

            // Increase
            document.querySelectorAll('.btn-increase').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    fetch(`/cart/increase/${id}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                updateCartUI(id, data.quantity, data.total, data.stock);
                            }
                        });
                });
            });

            // Decrease
            document.querySelectorAll('.btn-decrease').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    fetch(`/cart/decrease/${id}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                if (data.deleted) {
                                    removeCartItem(id);
                                } else {
                                    updateCartUI(id, data.quantity, data.total, data.stock);
                                }
                            }
                        });
                });
            });

            // Remove
            document.querySelectorAll('.btn-remove').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    fetch(`/cart/delete/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                removeCartItem(id);
                            }
                        });
                });
            });

            updateTotalPrice();
            updateIncreaseButtons();
            updateCartBadgeCount();
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
