@extends('layouts.index')

@section('content')
    <div class="container my-5">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/products">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="card product-card shadow-sm">
            <div class="row g-0">
                <!-- Product Images Column -->
                <div class="col-lg-6">
                    <div class="product-gallery p-4">
                        <div class="main-image-container mb-3">
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                 alt="{{ $product->name }}"
                                 class="main-image img-fluid rounded-3">
                        </div>
                        <div class="thumbnail-scroller d-flex flex-nowrap overflow-auto pb-2">
                            @foreach ($product->images as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                    class="thumbnail-img me-2 rounded-2"
                                    alt="Product thumbnail"
                                    data-full-image="{{ asset('storage/' . $image->image_path) }}">
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Product Details Column -->
                <div class="col-lg-6">
                    <div class="product-details p-4">
                        <!-- Product Header with Wishlist -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h1 class="product-title mb-2">{{ $product->name }}</h1>
                                <div class="d-flex align-items-center mb-3">
                                    <span class="badge bg-success me-2">Bestseller</span>
                                    <span class="sold-count text-muted">
                                        <i class="fas fa-check-circle me-1"></i>{{ $product->sales }} sold
                                    </span>
                                </div>
                            </div>
                            @if (auth()->check())
                                <button class="btn btn-outline-danger wishlist-btn p-2"
                                        data-product-id="{{ $product->id }}">
                                    <i class="fas fa-heart {{ in_array($product->id, $userWishlistIds) ? 'text-danger' : 'text-secondary' }}"></i>
                                </button>
                            @endif
                        </div>

                        <!-- Price Section -->
                        <div class="price-section mb-4">
                            <div class="current-price">
                                Rp{{ number_format($product->price, 0, ',', '.') }}
                            </div>
                            @if($product->discount > 0)
                                <div class="original-price text-muted">
                                    <del>Rp{{ number_format($product->price + $product->discount, 0, ',', '.') }}</del>
                                    <span class="discount-badge bg-danger ms-2">
                                        {{ round(($product->discount / ($product->price + $product->discount))) * 100 }}% OFF
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Variant Selection -->
                        <div class="variant-selection mb-4">
                            <!-- Color Selection -->
                            <div class="mb-3">
                                <h6 class="section-title">Color <span class="text-danger">*</span></h6>
                                <div class="color-options d-flex flex-wrap">
                                    @php $colors = $product->subVariant->pluck('color')->unique(); @endphp
                                    @foreach ($colors as $color)
                                        <button class="color-option btn btn-outline-secondary me-2 mb-2"
                                                data-color="{{ $color }}">
                                            <span class="color-dot me-1" style="background-color: {{ $color }}"></span>
                                            {{ ucfirst($color) }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Size Selection -->
                            <div class="mb-4">
                                <h6 class="section-title">Size <span class="text-danger">*</span></h6>
                                <div class="size-options d-flex flex-wrap">
                                    @php $sizes = $product->subVariant->pluck('size')->unique(); @endphp
                                    @foreach ($sizes as $size)
                                        <button class="size-option btn btn-outline-secondary me-2 mb-2"
                                                data-size="{{ $size }}">
                                            {{ strtoupper($size) }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Stock & Quantity -->
                        <div class="stock-quantity mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <h6 class="section-title mb-0 me-3">Quantity</h6>
                                <div class="quantity-selector input-group" style="width: 140px;">
                                    <button class="btn btn-outline-secondary quantity-decrease" type="button">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" class="form-control text-center quantity-input" value="1" min="1">
                                    <button class="btn btn-outline-secondary quantity-increase" type="button">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="stock-status">
                                <i class="fas fa-box-open me-2"></i>
                                <span class="stock-text">Please select color and size</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons mb-4">
                            <form action="{{ route('cart.add') }}" method="POST" class="d-inline-block me-2">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" class="quantity-input-hidden">
                                <input type="hidden" name="color" class="color-input-hidden">
                                <input type="hidden" name="size" class="size-input-hidden">
                                <button type="submit" class="btn btn-warning btn-lg add-to-cart-btn">
                                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                </button>
                            </form>

                            <form method="GET" action="{{ route('checkout.show', $product->id) }}" class="d-inline-block">
                                <input type="hidden" name="quantity" class="quantity-input-hidden">
                                <input type="hidden" name="color" class="color-input-hidden">
                                <input type="hidden" name="size" class="size-input-hidden">
                                <button type="submit" class="btn btn-primary btn-lg buy-now-btn">
                                    <i class="fas fa-bolt me-2"></i>Buy Now
                                </button>
                            </form>
                        </div>

                        <!-- Product Description with Expand/Collapse -->
                        <div class="product-description mb-4">
                            <div class="accordion" id="descriptionAccordion">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button px-0 bg-transparent shadow-none" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                aria-expanded="true" aria-controls="collapseOne">
                                            <h6 class="section-title mb-0">Product Description</h6>
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                         aria-labelledby="headingOne" data-bs-parent="#descriptionAccordion">
                                        <div class="accordion-body px-0 pt-2">
                                            {{ $product->description }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Highlights -->
                        <div class="product-highlights">
                            <h6 class="section-title mb-3">Highlights</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Premium Quality Materials</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Free Shipping on Orders Over Rp500.000</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> 30-Day Return Policy</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Alert (if any) -->
        @if (session('error'))
            <div id="error-alert" class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                <strong>Error:</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <style>
        /* Main Card Container */
        .product-card {
            border-radius: 12px;
            overflow: hidden;
            border: none;
        }

        /* Product Gallery Styles */
        .product-gallery {
            background-color: #f9f9f9;
            height: 100%;
        }

        .main-image-container {
            height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
        }

        .main-image {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .main-image:hover {
            transform: scale(1.05);
        }

        .thumbnail-scroller {
            scrollbar-width: thin;
            scrollbar-color: #ddd #f9f9f9;
        }

        .thumbnail-scroller::-webkit-scrollbar {
            height: 6px;
        }

        .thumbnail-scroller::-webkit-scrollbar-track {
            background: #f9f9f9;
        }

        .thumbnail-scroller::-webkit-scrollbar-thumb {
            background-color: #ddd;
            border-radius: 6px;
        }

        .thumbnail-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .thumbnail-img:hover {
            border-color: #4CAF50;
            transform: translateY(-2px);
        }

        /* Product Details Styles */
        .product-details {
            height: 100%;
        }

        .product-title {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            line-height: 1.2;
        }

        .sold-count {
            font-size: 0.9rem;
        }

        .wishlist-btn {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wishlist-btn:hover {
            background-color: rgba(220, 53, 69, 0.1);
        }

        /* Price Section */
        .price-section {
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .current-price {
            font-size: 1.8rem;
            font-weight: 700;
            color: #000000;
        }

        .original-price {
            font-size: 1.2rem;
        }

        .discount-badge {
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
        }

        /* Variant Selection */
        .section-title {
            font-weight: 600;
            color: #333;
            font-size: 1rem;
        }

        .color-option, .size-option {
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .color-option.active, .size-option.active {
            background-color: #333;
            color: white;
            border-color: #333;
        }

        .color-dot {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 1px solid #ddd;
        }

        /* Quantity Selector */
        .quantity-selector .btn {
            width: 40px;
            padding: 0.375rem;
        }

        .quantity-input {
            font-weight: 600;
        }

        .stock-status {
            display: flex;
            align-items: center;
            color: #666;
            font-size: 0.9rem;
        }

        .stock-text {
            margin-left: 5px;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .add-to-cart-btn, .buy-now-btn {
            min-width: 180px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background-color: #e0b420;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .buy-now-btn:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Product Description */
        .product-description {
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
            padding: 1rem 0;
        }

        .accordion-button:not(.collapsed) {
            color: inherit;
            background-color: transparent;
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: transparent;
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .main-image-container {
                height: 400px;
            }
        }

        @media (max-width: 768px) {
            .main-image-container {
                height: 350px;
            }

            .product-title {
                font-size: 1.6rem;
            }

            .current-price {
                font-size: 1.5rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .add-to-cart-btn, .buy-now-btn {
                width: 100%;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image Gallery Functionality
            const thumbnails = document.querySelectorAll('.thumbnail-img');
            const mainImage = document.querySelector('.main-image');

            thumbnails.forEach(thumb => {
                thumb.addEventListener('click', function() {
                    // Update main image
                    mainImage.src = this.dataset.fullImage;

                    // Update active thumbnail
                    thumbnails.forEach(t => t.classList.remove('active-thumbnail'));
                    this.classList.add('active-thumbnail');
                    this.style.borderColor = '#4CAF50';
                });
            });

            // Wishlist Toggle
            $('.wishlist-btn').on('click', function(event) {
                event.preventDefault();
                var productId = $(this).data('product-id');
                var $icon = $(this).find('i');

                $.ajax({
                    url: '{{ route('wishlist.add') }}',
                    type: 'POST',
                    data: {
                        product_id: productId,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.status === 'added') {
                            $icon.removeClass('text-secondary').addClass('text-danger');
                            // Show added notification
                            Toastify({
                                text: "Added to wishlist!",
                                duration: 2000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "#4CAF50",
                            }).showToast();
                        } else if (response.status === 'removed') {
                            $icon.removeClass('text-danger').addClass('text-secondary');
                            // Show removed notification
                            Toastify({
                                text: "Removed from wishlist",
                                duration: 2000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "#f44336",
                            }).showToast();
                        }

                        $('#for-badge-count-wishlist').text(response.wishlistCount);
                    },
                    error: function(xhr, status, error) {
                        Toastify({
                            text: "Error updating wishlist",
                            duration: 2000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#f44336",
                        }).showToast();
                        console.error("AJAX error: " + status + ": " + error);
                    }
                });
            });

            // Variant Selection Logic
            let selectedColor = null;
            let selectedSize = null;
            const subVariant = @json($product->subVariant ?? []);
            const stockDisplay = document.querySelector('.stock-text');
            const quantityInput = document.querySelector('.quantity-input');
            const btnIncrease = document.querySelector('.quantity-increase');
            const btnDecrease = document.querySelector('.quantity-decrease');
            const addToCartBtn = document.querySelector('.add-to-cart-btn');
            const buyNowBtn = document.querySelector('.buy-now-btn');

            // Color selection
            document.querySelectorAll('.color-option').forEach(button => {
                button.addEventListener('click', function() {
                    selectedColor = this.dataset.color;
                    document.querySelectorAll('.color-option').forEach(btn =>
                        btn.classList.remove('active'));
                    this.classList.add('active');
                    updateStock();
                    updateHiddenFields();
                });
            });

            // Size selection
            document.querySelectorAll('.size-option').forEach(button => {
                button.addEventListener('click', function() {
                    selectedSize = this.dataset.size;
                    document.querySelectorAll('.size-option').forEach(btn =>
                        btn.classList.remove('active'));
                    this.classList.add('active');
                    updateStock();
                    updateHiddenFields();
                });
            });

            // Stock update function
            function updateStock() {
                if (selectedColor && selectedSize) {
                    const variant = subVariant.find(variant =>
                        variant.color === selectedColor && variant.size === selectedSize);

                    if (variant) {
                        if (variant.stock > 0) {
                            stockDisplay.textContent = `${variant.stock} available`;
                            stockDisplay.style.color = '#4CAF50';
                            quantityInput.max = variant.stock;
                            if (parseInt(quantityInput.value) > variant.stock) {
                                quantityInput.value = variant.stock;
                            }

                            addToCartBtn.disabled = false;
                            buyNowBtn.disabled = false;
                        } else {
                            stockDisplay.textContent = 'Out of stock';
                            stockDisplay.style.color = '#f44336';
                            quantityInput.value = 1;
                            quantityInput.max = 1;

                            addToCartBtn.disabled = true;
                            buyNowBtn.disabled = true;
                        }
                    } else {
                        stockDisplay.textContent = 'Variant not available';
                        stockDisplay.style.color = '#f44336';
                        quantityInput.value = 1;
                        quantityInput.max = 1;

                        addToCartBtn.disabled = true;
                        buyNowBtn.disabled = true;
                    }
                } else {
                    stockDisplay.textContent = 'Please select color and size';
                    stockDisplay.style.color = '#666';
                    quantityInput.value = 1;
                    quantityInput.max = 1;

                    addToCartBtn.disabled = true;
                    buyNowBtn.disabled = true;
                }
            }

            // Update hidden form fields
            function updateHiddenFields() {
                document.querySelectorAll('.color-input-hidden').forEach(input => input.value = selectedColor || '');
                document.querySelectorAll('.size-input-hidden').forEach(input => input.value = selectedSize || '');
                document.querySelectorAll('.quantity-input-hidden').forEach(input => input.value = quantityInput.value);
            }

            // Quantity controls
            btnIncrease.addEventListener('click', function() {
                let currentQuantity = parseInt(quantityInput.value);
                if (currentQuantity < parseInt(quantityInput.max)) {
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

            quantityInput.addEventListener('change', function() {
                if (this.value < 1) this.value = 1;
                if (this.value > parseInt(this.max)) this.value = this.max;
                updateHiddenFields();
            });

            // Initialize
            updateStock();
            updateHiddenFields();
        });
    </script>
@endsection
