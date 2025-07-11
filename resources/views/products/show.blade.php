@extends('layouts.index')

@section('title', 'Product Details')

@section('content')
    <div class="product-detail-container">
        <div class="product-detail-card">
            <!-- Product Header -->
            <div class="product-header">
                <div class="breadcrumb-nav">
                    <a href="{{ route('home.index') }}">Home</a> &gt;
                    <a href="{{ route('products.index') }}">Products</a> &gt;
                    <span>{{ $product->name }}</span>
                </div>
                <h1 class="product-title">{{ $product->name }}</h1>
                <div class="product-meta">
                    <span class="product-brand">{{ $product->brand->name }}</span>
                    <span class="product-category">{{ $product->subCategory->name }}</span>
                    <div class="product-rating">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($product->rating))
                                <i class="fas fa-star"></i>
                            @elseif ($i - 0.5 <= $product->rating)
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                        <span>({{ $product->rating_count }} reviews)</span>
                    </div>
                </div>
            </div>

            <!-- Product Content -->
            <div class="product-content">
                <!-- Gallery Column -->
                <div class="gallery-column">
                    <div class="main-image-container">
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                            alt="{{ $product->name }}" class="main-image" id="mainProductImage">
                        <div class="image-badge">Hover to zoom</div>
                    </div>
                    <div class="thumbnail-gallery">
                        @foreach ($product->images as $image)
                            <div class="thumbnail-container">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Thumbnail" class="thumbnail"
                                    data-full-image="{{ asset('storage/' . $image->image_path) }}">
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Info Column -->
                <div class="info-column">
                    <!-- Price Section -->
                    <div class="price-section">
                        @if ($product->old_price)
                            <div class="old-price">Rp {{ number_format($product->old_price, 0, ',', '.') }}</div>
                        @endif
                        <div class="current-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        @if ($product->old_price)
                            <div class="discount-badge">
                                {{ round(100 - ($product->price / $product->old_price) * 100) }}% OFF
                            </div>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="description-section">
                        <h3>Product Description</h3>
                        <p>{{ $product->description }}</p>
                    </div>

                    <!-- Variants -->
                    <div class="variants-section">
                        <h3>Available Options</h3>
                        <table class="variants-table">
                            <thead>
                                <tr>
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product->subVariant as $variant)
                                    <tr>
                                        <td>
                                            @if ($variant->color)
                                                <span class="color-swatch"
                                                    style="background-color: {{ $variant->color }}"></span>
                                                {{ $variant->color }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $variant->size ?: '-' }}</td>
                                        <td>
                                            @if ($variant->stock > 5)
                                                <span class="in-stock">In Stock ({{ $variant->stock }})</span>
                                            @elseif($variant->stock > 0)
                                                <span class="low-stock">Low Stock ({{ $variant->stock }})</span>
                                            @else
                                                <span class="out-of-stock">Out of Stock</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- QR Code Section -->
                    <div class="qr-code-section">
                        <div class="section-header">
                            <h3>Product QR Code</h3>
                            <p>Scan this code for quick access to product details</p>
                        </div>

                        <div class="qr-code-container">
                            @if ($product->qr_code_path)
                                <div class="qr-code-display">
                                    <img src="{{ asset($product->qr_code_path) }}" alt="QR Code" class="qr-code-image">
                                    <div class="qr-code-actions">
                                        <a href="{{ asset($product->qr_code_path) }}"
                                            download="QR-{{ str_replace(' ', '-', $product->name) }}.png"
                                            class="action-btn download-btn">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                        <a href="{{ route('products.qr.print', $product->id) }}" target="_blank"
                                            class="action-btn print-btn">
                                            <i class="fas fa-print"></i> Print
                                        </a>
                                        <a href="{{ route('products.qr.refresh', $product->id) }}"
                                            class="action-btn refresh-btn">
                                            <i class="fas fa-sync-alt"></i> Refresh
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="no-qr-code">
                                    <div class="qr-code-placeholder">
                                        <i class="fas fa-qrcode"></i>
                                        <p>No QR Code generated</p>
                                    </div>
                                    <a href="{{ route('products.qr.generate', $product->id) }}" class="generate-qr-btn">
                                        <i class="fas fa-plus-circle"></i> Generate QR Code
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Zoom Modal -->
    <div class="modal fade" id="imageZoomModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="" alt="Zoomed Product Image" id="zoomedImage" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <script>
        // Image gallery functionality
        document.querySelectorAll('.thumbnail').forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                const mainImage = document.getElementById('mainProductImage');
                mainImage.src = this.getAttribute('data-full-image');

                // Update active thumbnail
                document.querySelectorAll('.thumbnail-container').forEach(container => {
                    container.classList.remove('active');
                });
                this.closest('.thumbnail-container').classList.add('active');
            });
        });

        // Initialize first thumbnail as active
        document.querySelector('.thumbnail-container').classList.add('active');
    </script>
@endsection
