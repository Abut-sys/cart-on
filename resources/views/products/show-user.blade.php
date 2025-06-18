@extends('layouts.index')

@section('content')
    <div class="container mt-5" style="position: relative;">
        <div class="row product-user-show-row">
            <div class="col-lg-6 product-user-show-col">
                <div class="text-center mb-4">
                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}"
                        class="img-fluid rounded product-user-show-main-image" id="main-image">
                </div>

                <div class="d-flex justify-content-start overflow-x-auto" style="max-width: 100%; white-space: nowrap;">
                    @foreach ($product->images as $image)
                        <img src="{{ asset('storage/' . $image->image_path) }}"
                            class="img-thumbnail mx-2 product-user-show-additional-image" alt="Product image"
                            data-full-image="{{ asset('storage/' . $image->image_path) }}"
                            style="cursor: pointer; width: 80px; height: 80px; object-fit: cover;">
                    @endforeach
                </div>
            </div>

            <div class="col-lg-6 product-user-show-col" style="margin-left: -10px;">
                <h2 class="product-user-show-title">{{ $product->name }}</h2>
                <div class="product-user-show-meta d-flex align-items-center mb-3" style="margin-left: -10px;">
                    <span class="product-user-show-sold-count">SOLD | {{ $product->sales }}</span>
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
                            <button class="product-user-show-color-option me-2 mb-2"
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
                            <button class="product-user-show-size-option me-2 mb-2"
                                data-size="{{ $size }}">{{ strtoupper($size) }}</button>
                        @endforeach
                    </div>
                </div>

                @if (session('error'))
                    <div id="error-alert-show-user" class="custom-alert-show-user custom-alert-error-show-user">
                        <strong>Error:</strong> {{ session('error') }}
                        <button type="button" class="close-btn-show-user" onclick="closeAlert()">&times;</button>
                    </div>
                @endif

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
                        {{ $product->description }}
                    </p>
                    <button id="product-user-show-btn-see-more" class="product-user-show-btn-see-more"
                        onclick="toggleDescription()">See
                        More</button>
                </div>

                <div class="product-user-show-action-buttons mt-4" style="margin-left: -10px;">
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" class="quantity-input-hidden">
                        <input type="hidden" name="color" class="color-input-hidden">
                        <input type="hidden" name="size" class="size-input-hidden">
                        <button type="submit" class="btn btn-warning product-user-show-btn-add-to-cart">Add to
                            Cart</button>
                    </form>

                    <form method="GET" action="{{ route('checkout.show', $product->id) }}">
                        <input type="hidden" name="quantity" class="quantity-input-hidden">
                        <input type="hidden" name="color" class="color-input-hidden">
                        <input type="hidden" name="size" class="size-input-hidden">
                        <button type="submit" class="btn btn-primary product-user-show-btn-buy-now">Buy Now</button>
                    </form>
                </div>
            </div>
        </div>
        @if (auth()->check())
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
            border-radius: 8px;
            border: 1px solid #f0f0f0;
        }

        .product-user-show-additional-images img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            cursor: pointer;
            border-radius: 4px;
            border: 1px solid #e0e0e0;
            transition: border-color 0.2s ease;
        }

        .product-user-show-additional-images img:hover {
            border-color: #4CAF50;
        }

        .product-user-show-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: black;
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 500px;
        }

        .product-user-show-sold-count {
            font-size: 1.0rem;
            font-weight: bold;
            color: rgb(121, 116, 116);
            text-align: right;
        }

        .product-user-show-price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #76a984;
            margin-bottom: 2rem;
        }

        .product-user-show-color-option,
        .product-user-show-size-option {
            margin-top: 10px;
            padding: 8px 16px;
            border-radius: 4px;
            border: 1px solid #e0e0e0;
            background: #fff;
            color: #000;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .product-user-show-color-option:hover,
        .product-user-show-size-option:hover {
            background: #99bc85;
            border-color: #99bc85;
            color: #fff;
            font-weight: bold;
        }

        .active {
            background: #99bc85 !important;
            border-color: #99bc85 !important;
            color: #fff !important;
            font-weight: bold;
        }

        .custom-alert-show-user {
            width: 100%;
            margin-left: -10px;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 14px;
            position: relative;
            transition: opacity 0.5s ease;
            opacity: 1;
        }

        .custom-alert-error-show-user {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }

        .close-btn-show-user {
            position: absolute;
            top: 8px;
            right: 12px;
            background: none;
            border: none;
            font-weight: bold;
            font-size: 18px;
            line-height: 1;
            color: inherit;
            cursor: pointer;
        }

        .product-user-show-description {
            font-size: 1.1rem;
            color: #555;
            margin-top: 1.5rem;
            line-height: 1.6;
        }

        .product-user-show-description-text {
            max-height: 25px;
            overflow: hidden;
            padding-right: 20px;
            margin-bottom: 10px;
            transition: max-height 0.3s ease-out;
        }

        .product-user-show-btn-see-more {
            background: none;
            border: none;
            color: #1976D2;
            padding: 0;
            font-size: 1rem;
            cursor: pointer;
            font-weight: 500;
        }

        .product-user-show-btn-see-more:hover {
            text-decoration: underline;
        }

        .product-user-show-action-buttons {
            display: flex;
            align-items: center;
            margin-top: 1.5rem;
        }

        .product-user-show-btn-add-to-cart {
            background-color: #e0c020;
            border: none;
            color: #fff;
            padding: 10px 24px;
            border-radius: 4px;
            font-weight: 500;
            transition: background-color 0.2s ease;
            margin-right: 10px
        }

        .product-user-show-btn-add-to-cart:hover {
            background-color: #e0c020;
            color: #1c1919;
        }

        .product-user-show-btn-buy-now {
            background-color: #47a0e9;
            border: none;
            color: #fff;
            padding: 10px 24px;
            border-radius: 4px;
            font-weight: 500;
            transition: background-color 0.2s ease;
        }

        .product-user-show-btn-buy-now:hover {
            background-color: #47a0e9;
            color: #1c1919;
        }

        .product-user-show-toggle-cart-btn,
        .product-user-show-toggle-wishlist-btn {
            position: absolute;
            top: 20px;
            font-size: 26px;
            background: rgba(255, 255, 255, 0.9);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.2s ease;
        }

        .product-user-show-toggle-cart-btn {
            right: 60px;
        }

        .product-user-show-toggle-wishlist-btn {
            right: 20px;
        }

        .product-user-show-toggle-cart-btn:hover,
        .product-user-show-toggle-wishlist-btn:hover {
            opacity: 0.9;
        }

        .product-user-show-toggle-cart-btn i.text-danger {
            color: #2E7D32;
        }

        .product-user-show-toggle-wishlist-btn i.text-danger {
            color: #C62828;
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

            function updateMainImage(imageUrl) {
                const mainImage = document.getElementById('main-image');
                mainImage.src = imageUrl;
            }

            document.querySelectorAll('.product-user-show-additional-image').forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    const imageUrl = this.getAttribute('data-full-image');
                    updateMainImage(imageUrl);
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
                const addToCartBtn = document.querySelector('.product-user-show-btn-add-to-cart');
                const buyNowBtn = document.querySelector('.product-user-show-btn-buy-now');

                if (selectedColor && selectedSize) {
                    const variant = subVariant.find(variant =>
                        variant.color === selectedColor && variant.size === selectedSize);

                    if (variant) {
                        if (variant.stock > 0) {
                            stockDisplay.textContent = `Stock: ${variant.stock}`;
                            quantityInput.max = variant.stock;
                            if (parseInt(quantityInput.value) > variant.stock) {
                                quantityInput.value = variant.stock;
                            }

                            addToCartBtn.disabled = false;
                            buyNowBtn.disabled = false;
                        } else {
                            stockDisplay.textContent = 'Out of Stock';
                            quantityInput.value = 1;
                            quantityInput.max = 1;

                            addToCartBtn.disabled = true;
                            buyNowBtn.disabled = true;
                        }
                    } else {
                        stockDisplay.textContent = 'Out of Stock';
                        quantityInput.value = 1;
                        quantityInput.max = 1;

                        addToCartBtn.disabled = true;
                        buyNowBtn.disabled = true;
                    }
                } else {
                    stockDisplay.textContent = 'Select Color And Size';
                    quantityInput.value = 1;
                    quantityInput.max = 1;

                    addToCartBtn.disabled = true;
                    buyNowBtn.disabled = true;
                }
            }

            function updateHiddenFields() {
                document.querySelectorAll('.color-input-hidden').forEach(input => input.value = selectedColor ||
                    '');
                document.querySelectorAll('.size-input-hidden').forEach(input => input.value = selectedSize || '');
                document.querySelectorAll('.quantity-input-hidden').forEach(input => input.value = quantityInput
                    .value);
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

        document.addEventListener("DOMContentLoaded", function() {
            const errorAlert = document.getElementById('error-alert-show-user');

            if (errorAlert) {
                setTimeout(() => {
                    errorAlert.style.opacity = '0';
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                }, 2000);
            }
        });

        function closeAlert() {
            const errorAlert = document.getElementById('error-alert-show-user');
            if (errorAlert) {
                errorAlert.style.opacity = '0';
                setTimeout(() => {
                    location.reload();
                }, 500);
            }
        }
    </script>
@endsection
