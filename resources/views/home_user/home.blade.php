@extends('layouts.index')

@section('title', 'Home')

@section('content')
    <div class="home-user-banner-container">
        <div class="home-user-banner-wrapper">
            <div class="home-user-banner">
                <img src="image/banner (2612 x 992 px).png" alt="Banner 1" class="home-user-banner-image">
                <img src="image/banner1.png" alt="Banner 2" class="home-user-banner-image">
                <img src="image/banner2.png" alt="Banner 3" class="home-user-banner-image">
            </div>
            <button class="home-user-banner-prev">&lt;</button>
            <button class="home-user-banner-next">&gt;</button>
        </div>
        <div class="home-user-ads">
            <div class="home-user-ad">
                <img src="image/iklan1.png" alt="Iklan 1" class="ad-image">
            </div>
            <div class="home-user-ad">
                <img src="image/iklan2.png" alt="Iklan 2" class="ad-image">
            </div>
        </div>
    </div>

    <div class="home-user-shop-by-categories">
        <h2>Shop By Categories :</h2>
        <div class="home-user-categories-container">
            <div class="home-user-category">
                <img src="image/Category-layout (1).png" alt="Category 1">
            </div>
            <div class="home-user-category">
                <img src="image/Category-layout (2).png" alt="Category 2">
            </div>
            <div class="home-user-category">
                <img src="image/Category-layout (3).png" alt="Category 3">
            </div>
            <div class="home-user-category">
                <img src="image/Category-layout (4).png" alt="Category 4">
            </div>
            <div class="home-user-category">
                <img src="image/Category-layout (5).png" alt="Category 5">
            </div>
        </div>
    </div>

    <div class="home-user-shop-by-products-newest">
        <h2>Newest product :</h2>
        <div class="home-product-newest-container d-flex flex-wrap justify-content-start">
            @forelse ($products->take(5) as $product)
                <a href="{{ Auth::check() ? route('products-all.show', $product->id) : route('login') }}"
                    class="home-product-newest-card text-decoration-none text-dark">

                    @if (auth()->check())
                        <i class="fas fa-heart home-product-newest-wishlist-icon
                    {{ in_array($product->id, $userWishlistIds) ? 'text-danger' : 'text-secondary' }}"
                            data-product-id="{{ $product->id }}"></i>
                    @endif

                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}"
                        class="home-product-newest-img-top">
                    <div class="home-product-newest-body text-center">
                        <h6 class="home-product-newest-title">{{ $product->name }}</h6>
                        <p class="home-product-newest-price">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                        <div class="d-flex justify-content-between align-items-center px-2"
                            style="font-size: 13px; color: gray;">
                            <div class="d-flex align-items-center gap-1 text-warning">
                                <i class="fas fa-star"></i>
                                <span class="text-dark">{{ number_format($product->rating ?? 4.5, 1) }}</span>
                            </div>
                            <span>{{ $product->sales }}+ Sold</span>
                        </div>
                    </div>
                </a>
            @empty
                <p>No products available at the moment.</p>
            @endforelse
        </div>
    </div>

    <div class="home-user-shop-by-brands">
        @foreach ($categories as $category)
            <h2>Featured {{ $category->name }}</h2>
            <div class="home-user-brands-container">
                @forelse ($category->brands as $brand)
                    <div class="home-user-brand">
                        <a href="{{ route('products-all.index', ['brand' => $brand->name]) }}">
                            <img src="{{ asset('storage/' . $brand->logo_path) }}" alt="{{ $brand->name }}">
                        </a>
                    </div>
                @empty
                    <p>No brands available in this category.</p>
                @endforelse
            </div>
        @endforeach
    </div>
@endsection

@include('components.ratingWeb')

@include('components.liveChat')

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // ==================== BANNER SLIDER ====================
            const banner = document.querySelector('.home-user-banner');
            const images = document.querySelectorAll('.home-user-banner-image');
            const prevBtn = document.querySelector('.home-user-banner-prev');
            const nextBtn = document.querySelector('.home-user-banner-next');

            let currentIndex = 0;
            const totalImages = images.length;

            // Create indicator dots
            const dotsContainer = document.createElement('div');
            dotsContainer.className = 'home-user-banner-dots';
            banner.parentNode.appendChild(dotsContainer);

            for (let i = 0; i < totalImages; i++) {
                const dot = document.createElement('div');
                dot.className = 'home-user-banner-dot';
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => goToSlide(i));
                dotsContainer.appendChild(dot);
            }

            const dots = document.querySelectorAll('.home-user-banner-dot');

            // Set initial positions
            function initializeSlider() {
                images.forEach((img, index) => {
                    img.style.transform = translateX($ {
                        index * 100
                    } % );
                });
            }

            // Go to specific slide
            function goToSlide(index) {
                currentIndex = index;
                updateSlider();
            }

            // Update slider position and active dot
            function updateSlider() {
                images.forEach((img, index) => {
                    img.style.transform = translateX($ {
                        100 * (index - currentIndex)
                    } % );
                });

                // Update active dot
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentIndex);
                });
            }

            // Next slide
            function nextSlide() {
                currentIndex = (currentIndex + 1) % totalImages;
                updateSlider();
            }

            // Previous slide
            function prevSlide() {
                currentIndex = (currentIndex - 1 + totalImages) % totalImages;
                updateSlider();
            }

            // Event listeners
            nextBtn.addEventListener('click', nextSlide);
            prevBtn.addEventListener('click', prevSlide);

            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowRight') nextSlide();
                if (e.key === 'ArrowLeft') prevSlide();
            });

            // Auto-advance (optional)
            let autoplay = setInterval(nextSlide, 5000);

            // Pause autoplay on hover
            banner.parentNode.addEventListener('mouseenter', () => {
                clearInterval(autoplay);
            });

            banner.parentNode.addEventListener('mouseleave', () => {
                autoplay = setInterval(nextSlide, 5000);
            });

            // Touch swipe support for mobile
            let touchStartX = 0;
            let touchEndX = 0;

            banner.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            }, {
                passive: true
            });

            banner.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            }, {
                passive: true
            });

            function handleSwipe() {
                const threshold = 50;
                if (touchEndX < touchStartX - threshold) {
                    nextSlide();
                } else if (touchEndX > touchStartX + threshold) {
                    prevSlide();
                }
            }

            // Initialize
            initializeSlider();

            // ==================== WISHLIST HANDLER ====================
            $('.home-product-newest-wishlist-icon').on('click', function(event) {
                event.preventDefault();
                const productId = $(this).data('product-id');
                const $this = $(this);

                $.ajax({
                    url: '{{ route('wishlist.add') }}',
                    type: 'POST',
                    data: {
                        product_id: productId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'added') {
                            $this.removeClass('text-secondary').addClass('text-danger');
                        } else if (response.status === 'removed') {
                            $this.removeClass('text-danger').addClass('text-secondary');
                        }
                        $('#for-badge-count-wishlist').text(response.wishlistCount);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error:", error);
                    }
                });
            });

            // ==================== HORIZONTAL SCROLL BRANDS ====================
            const brandsContainers = document.querySelectorAll('.home-user-brands-container');
            brandsContainers.forEach(container => {
                let isDragging = false;
                let startX;
                let scrollLeft;

                container.addEventListener('mousedown', (e) => {
                    isDragging = true;
                    startX = e.pageX - container.offsetLeft;
                    scrollLeft = container.scrollLeft;
                });

                container.addEventListener('mouseleave', () => {
                    isDragging = false;
                });

                container.addEventListener('mouseup', () => {
                    isDragging = false;
                });

                container.addEventListener('mousemove', (e) => {
                    if (!isDragging) return;
                    e.preventDefault();
                    const x = e.pageX - container.offsetLeft;
                    const walk = (x - startX) * 2;
                    container.scrollLeft = scrollLeft - walk;
                });
            });
        });
    </script>

    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
@endpush
