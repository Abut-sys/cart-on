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
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        background-color: #fff;
        border: 1px solid #eee;
        border-left: 6px solid #f5eae2;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .cart-product-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #ccc;
    }

    .cart-product-name {
        font-size: 1rem;
        font-weight: 600;
        color: #111;
    }

    .cart-product-total {
        font-size: 1rem;
        font-weight: bold;
        color: #28a745;
        margin: 0;
        text-align: center;
    }

    .cart-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: center;
        gap: 6px;
        margin-right: 4px;
    }

    .cart-quantity-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #ccc;
        border-radius: 999px;
        overflow: hidden;
        width: 110px;
        height: 34px;
        background-color: #fff;
    }

    .cart-quantity-controls .btn {
        border: none;
        background-color: transparent;
        font-size: 0.9rem;
        width: 30px;
        padding: 0;
        color: #000;
    }

    .cart-quantity-controls .form-control {
        border: none;
        text-align: center;
        font-size: 0.9rem;
        width: 40px;
        background: none;
        height: 32px;
        padding: 0;
    }

    .btn-remove {
        border: none;
        background: none;
        color: #dc3545;
        font-size: 1.2rem;
        padding: 0;
        transition: 0.2s;
    }

    .btn-remove:hover {
        color: #dc3545;
    }

    .cart-checkbox {
        width: 18px;
        height: 18px;
        border-radius: 4px;
        background-color: #fff;
        border: 2px solid #ccc;
        cursor: pointer;
        appearance: none;
        transition: all 0.2s ease;
        margin-top: 4px;
        position: relative;
    }

    .cart-checkbox:checked {
        background-color: #28a745;
        border-color: #28a745;
    }

    .cart-checkbox:checked::after {
        content: "✓";
        color: white;
        font-size: 13px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .cart-checkbox:focus {
        box-shadow: none;
        outline: none;
    }

    .cart-details-right {
        display: flex;
        align-items: center;
        gap: 12px;
    }
</style>
