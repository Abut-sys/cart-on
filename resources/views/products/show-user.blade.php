@extends('layouts.index')

@section('content')
    <div class="container mt-5" style="position: relative;">
        <div class="row product-user-show-row">
            <div class="col-lg-6 product-user-show-col">
                <div class="text-center mb-4">
                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                        class="img-fluid rounded product-user-show-main-image">
                </div>
                <div class="d-flex justify-content-center">
                    @if (!empty($product->additionalImages) && is_array($product->additionalImages))
                        @foreach ($product->additionalImages as $image)
                            <img src="{{ asset('storage/' . $image) }}"
                                class="img-thumbnail mx-2 product-user-show-additional-image" alt="Product image">
                        @endforeach
                    @else
                        <p class="text-muted product-user-show-no-images">No additional images available.</p>
                    @endif
                </div>
            </div>

            <div class="col-lg-6 product-user-show-col" style="margin-left: -10px;">
                <h2 class="product-user-show-title">{{ $product->name }}</h2>
                <div class="product-user-show-meta d-flex align-items-center mb-3" style="margin-left: -10px;">
                    <span class="text-muted ms-1 product-user-show-sold-count">Sold 7RB+</span>
                </div>
                <h3 class="product-user-show-price" style="margin-left: -10px;">
                    Rp{{ number_format($product->price, 0, ',', '.') }}
                </h3>

                <div class="mb-4 product-user-show-color-section" style="margin-left: -10px;">
                    <strong>Color</strong>
                    <div class="d-flex flex-wrap mt-2 product-user-show-color-options">
                        @php
                            $colors = $product->subVariant->pluck('color')->unique();
                        @endphp
                        @foreach ($colors as $color)
                            <button class="btn btn-outline-secondary product-user-show-color-option me-2 mb-2"
                                data-color="{{ $color }}">{{ ucfirst($color) }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4 product-user-show-size-section" style="margin-left: -10px;">
                    <strong>Size</strong>
                    <div class="d-flex flex-wrap mt-2 product-user-show-size-options">
                        @php
                            $sizes = $product->subVariant->pluck('size')->unique();
                        @endphp
                        @foreach ($sizes as $size)
                            <button class="btn btn-outline-secondary product-user-show-size-option me-2 mb-2"
                                data-size="{{ $size }}">{{ strtoupper($size) }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="product-user-show-quantity-section d-flex align-items-center mb-4" style="margin-left: -10px;">
                    <strong>Quantity</strong>
                    <div class="input-group ms-3" style="width: 120px;">
                        <button class="btn btn-outline-secondary product-user-show-btn-decrease" type="button">-</button>
                        <input type="number" class="form-control text-center product-user-show-quantity-input"
                            value="1" min="1">
                        <button class="btn btn-outline-secondary product-user-show-btn-increase" type="button">+</button>
                    </div>
                    <span class="ms-3 product-user-show-stock-display">Select Color And Size</span>
                </div>

                <div class="product-user-show-description">
                    <strong>Description</strong>
                    <p id="product-user-show-description-text" class="product-user-show-description-text">
                        {{ $product->description }}</p>
                    <button id="product-user-show-btn-see-more" class="product-user-show-btn-see-more"
                        onclick="toggleDescription()">See
                        More</button>
                </div>

                <div class="product-user-show-action-buttons mt-4" style="margin-left: -10px;">
                    <form action="{{ route('checkout.show', $product->id) }}" method="GET">
                        @csrf
                        <input type="hidden" name="quantity" value="1" id="quantityInput">
                        <input type="hidden" name="color" class="hidden-color-input">
                        <input type="hidden" name="size" class="hidden-size-input">
                        <button type="submit" class="btn btn-primary product-user-show-btn-buy-now ">Buy Now</button>
                    </form>
                </div>

            </div>
        </div>
        @if (auth()->check())
        <i class="fas fa-shopping-cart product-user-show-toggle-cart-btn
    {{ in_array($product->id, $userCartIds) ? 'text-success' : 'text-secondary' }}"
            data-product-id="{{ $product->id }}">
        </i>
            <i class="fas fa-heart product-user-show-toggle-wishlist-btn
        {{ in_array($product->id, $userWishlistIds) ? 'text-danger' : 'text-secondary' }}"
                data-product-id="{{ $product->id }}">
            </i>

        @endif
    </div>

    <style>
        .product-user-show-main-image {
            width: 500px;
            height: 500px;
            object-fit: cover;
        }

        .product-user-show-additional-images img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            cursor: pointer;
        }

        .product-user-show-title {
            font-size: 1.8rem;
            font-weight: bold;
        }

        .product-user-show-price {
            font-size: 1.5rem;
            color: #28a745;
            font-weight: bold;
            margin-bottom: 2rem;
        }

        .product-user-show-color-option,
        .product-user-show-size-option {
            margin-top: 10px;
        }

        .product-user-show-color-option:hover {
            background-color: aqua;
            border-color: #38ab38;
            color: #ffc107;
        }

        .product-user-show-color-option:active {
            background-color: aqua !important;
            border-color: #38ab38 !important;
            color: #ffc107 !important;
        }

        .product-user-show-size-option:hover {
            background-color: aqua;
            border-color: #38ab38;
            color: #ffc107;
        }

        .product-user-show-size-option:active {
            background-color: #38ab38 !important;
            color: white !important;
            border-color: aquamarine !important;
        }

        .product-user-show-action-buttons {
            display: flex;
            align-items: center;
        }

        .product-user-show-description {
            font-size: 1.2rem;
            color: #555;
            margin-top: 1.5rem;
        }

        .product-user-show-description-text {
            max-height: 25px;
            overflow: hidden;
            padding-right: 20px;
            margin-bottom: 10px;
            transition: max-height 0.3s ease-out;
        }

        .product-user-show-description {
            font-size: 1.2rem;
            color: #555;
            margin-top: 1.5rem;
        }

        .product-user-show-btn-see-more {
            background: none;
            border: none;
            color: #007bff;
            padding: 0;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
        }

        .product-user-show-btn-see-more:hover {
            text-decoration: underline;
        }

        .product-user-show-btn-add-to-cart {
            background-color: #ffc107;
            border: none;
            color: #fff;
        }

        .product-user-show-btn-add-to-cart,
        .product-user-show-btn-buy-now {
            margin-right: 10px;
        }

        .product-user-show-btn-buy-now {
            margin-left: 0;
        }

        .product-user-show-btn-add-to-cart:hover {
            background-color: #e0a800;
        }

        .product-user-show-toggle-cart-btn {
            position: absolute;
            top: 20px;
            right: 60px;
            z-index: 10;
            font-size: 26px;
            background: transparent;
        }

        .product-user-show-toggle-cart-btn i.text-danger {
            color: #9cdc35;
        }

        .product-user-show-toggle-cart-btn i.text-secondary {
            color: #6c757d;
        }

        .product-user-show-toggle-wishlist-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
            font-size: 26px;
            background: transparent;
        }

        .product-user-show-toggle-wishlist-btn i.text-danger {
            color: #dc3545;
        }

        .product-user-show-toggle-wishlist-btn i.text-secondary {
            color: #6c757d;
        }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let selectedColor = null;
            let selectedSize = null;
            const subVariant = @json($product->subVariant ?? []);
            const stockDisplay = document.querySelector('.product-user-show-stock-display');
            const quantityInput = document.querySelector('.product-user-show-quantity-input');
            const btnIncrease = document.querySelector('.product-user-show-btn-increase');
            const btnDecrease = document.querySelector('.product-user-show-btn-decrease');
            const colorInput = document.querySelector('.hidden-color-input');
            const sizeInput = document.querySelector('.hidden-size-input');
            const quantityHiddenInput = document.querySelector('#quantityInput');

            $('.product-user-show-toggle-cart-btn ').on('click', function(event) {
                event.preventDefault();

                var productId = $(this).data('product-id');
                var $this = $(this);

                $.ajax({
                    url: '{{ route('cart.add') }}',
                    type: 'POST',
                    data: {
                        product_id: productId,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.status === 'added') {
                            $this.removeClass('text-secondary').addClass(
                                'text-success');
                        } else if (response.status === 'removed') {
                            $this.removeClass('text-success').addClass(
                                'text-secondary');
                        }

                        $('#for-badge-count-cart').text(response
                            .cartCount);
                    },
                    error: function(xhr, status, error) {
                        alert('Terjadi kesalahan saat memperbarui cart.');
                        console.error("AJAX error: " + status + ": " + error);
                    }
                });
            });

            $('.product-user-show-toggle-wishlist-btn ').on('click', function(event) {
                event.preventDefault();

                var productId = $(this).data('product-id');
                var $this = $(this);

                $.ajax({
                    url: '{{ route('wishlist.add') }}',
                    type: 'POST',
                    data: {
                        product_id: productId,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.status === 'added') {
                            $this.removeClass('text-secondary').addClass(
                                'text-danger');
                        } else if (response.status === 'removed') {
                            $this.removeClass('text-danger').addClass(
                                'text-secondary');
                        }

                        $('#for-badge-count-wishlist').text(response
                            .wishlistCount);
                    },
                    error: function(xhr, status, error) {
                        alert('Terjadi kesalahan saat memperbarui wishlist.');
                        console.error("AJAX error: " + status + ": " + error);
                    }
                });
            });

            document.querySelectorAll('.product-user-show-color-option').forEach(button => {
                button.addEventListener('click', function() {
                    if (selectedColor === this.dataset.color) {
                        selectedColor = null;
                        this.classList.remove('active');
                    } else {
                        selectedColor = this.dataset.color;
                        document.querySelectorAll('.product-user-show-color-option')
                            .forEach(btn =>
                                btn.classList.remove('active'));
                        this.classList.add('active');
                    }
                    updateStock();
                    updateHiddenFields();
                });
            });

            document.querySelectorAll('.product-user-show-size-option').forEach(button => {
                button.addEventListener('click', function() {
                    if (selectedSize === this.dataset.size) {
                        selectedSize = null;
                        this.classList.remove('active');
                    } else {
                        selectedSize = this.dataset.size;
                        document.querySelectorAll('.product-user-show-size-option')
                            .forEach(btn =>
                                btn.classList.remove('active'));
                        this.classList.add('active');
                    }
                    updateStock();
                    updateHiddenFields();
                });
            });

            function updateStock() {
                if (selectedColor && selectedSize) {
                    const variant = subVariant.find(variant =>
                        variant.color === selectedColor && variant.size === selectedSize);

                    if (variant) {
                        stockDisplay.textContent = `Stock: ${variant.stock}`;
                        quantityInput.max = variant.stock;
                        if (parseInt(quantityInput.value) > variant.stock) {
                            quantityInput.value = variant.stock;
                        }
                    } else {
                        stockDisplay.textContent = 'Stock: Out Of Stock';
                        quantityInput.max = 1;
                    }
                } else {
                    stockDisplay.textContent = 'Select Color And Size';
                    quantityInput.max = 1;
                }
            }

            function updateHiddenFields() {
                colorInput.value = selectedColor || '';
                sizeInput.value = selectedSize || '';
                quantityHiddenInput.value = quantityInput.value;
            }

            btnIncrease.addEventListener('click', function() {
                let currentQuantity = parseInt(quantityInput.value);
                if (currentQuantity < quantityInput.max) {
                    quantityInput.value = currentQuantity + 1;
                    updateHiddenFields();
                }
            });

            btnDecrease.addEventListener('click', function() {
                let currentQuantity = parseInt(quantityInput.value);
                if (currentQuantity > 1) {
                    quantityInput.value = currentQuantity - 1;
                    updateHiddenFields();
                }
            });

            updateStock();
            updateHiddenFields();
        });

        document.getElementById('add-to-cart-btn').addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');

            fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        product_id: productId
                    }),
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.message) {
                        alert(data.message); // Notify the user
                    }
                })
                .catch((error) => console.error('Error:', error));
        });

        function toggleDescription() {
            const descText = $('#product-user-show-description-text');
            const btn = $('#product-user-show-btn-see-more');

            if (descText.css('max-height') === '25px') {
                descText.css('max-height', '500px');
                btn.text('See Less');
            } else {
                descText.css('max-height', '25px');
                btn.text('See More');
            }
        }

        $('#product-user-show-btn-see-more').on('click', function() {
            toggleDescription();
        });
    </script>
@endsection
