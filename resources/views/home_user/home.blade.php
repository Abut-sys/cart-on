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
                <img src="image/iklan1.png" alt="Iklan 1">
            </div>
            <div class="home-user-ad">
                <img src="image/iklan2.png" alt="Iklan 2">
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
                    class="home-product-newest-card">
                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                        class="home-product-newest-img-top">
                    <div class="home-product-newest-body text-center">
                        <h6 class="home-product-newest-title" style="color: black">{{ $product->name }}</h6>
                        <p class="home-product-newest-price">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                        @if (Auth::check())
                            <button type="button" class="btn p-0 home-product-newest-wishlist-icon"
                                data-product-id="{{ $product->id }}">
                                <i class="fas fa-heart {{ in_array($product->id, session('wishlist', [])) ? 'text-danger' : 'text-secondary' }}"
                                    style="font-size: 18px;"></i>
                            </button>
                        @endif
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const banner = document.querySelector('.home-user-banner');
        const banners = document.querySelectorAll('.home-user-banner-image');
        const prevButton = document.querySelector('.home-user-banner-prev');
        const nextButton = document.querySelector('.home-user-banner-next');
        let currentIndex = 0;
        const totalBanners = banners.length;

        function updateBannerPosition() {
            banner.style.transform = `translateX(-${currentIndex * 100}%)`;
        }

        function slideBanner() {
            currentIndex = (currentIndex + 1) % totalBanners;
            updateBannerPosition();
        }

        function slideToPrev() {
            currentIndex = (currentIndex - 1 + totalBanners) % totalBanners;
            updateBannerPosition();
        }

        prevButton.addEventListener('click', slideToPrev);
        nextButton.addEventListener('click', slideBanner);

        setInterval(slideBanner, 5000);
    });

    document.addEventListener("DOMContentLoaded", function() {
        const brandsContainers = document.querySelectorAll('.home-user-brands-container');

        brandsContainers.forEach(brandsContainer => {
            brandsContainer.addEventListener('wheel', (e) => {
                e.preventDefault();
                brandsContainer.scrollLeft += e.deltaY * 0.5;
            });

            let isDragging = false;
            let startX;
            let scrollLeft;

            brandsContainer.addEventListener('mousedown', (e) => {
                isDragging = true;
                brandsContainer.classList.add('dragging');
                startX = e.pageX - brandsContainer.offsetLeft;
                scrollLeft = brandsContainer.scrollLeft;
            });

            brandsContainer.addEventListener('mouseleave', () => {
                isDragging = false;
                brandsContainer.classList.remove('dragging');
            });

            brandsContainer.addEventListener('mouseup', () => {
                isDragging = false;
                brandsContainer.classList.remove('dragging');
            });

            brandsContainer.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                e.preventDefault();
                const x = e.pageX - brandsContainer.offsetLeft;
                const walk = (x - startX) * 2;
                brandsContainer.scrollLeft = scrollLeft - walk;
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var wishlistButtons = document.querySelectorAll('.home-product-newest-wishlist-icon');

        wishlistButtons.forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Mencegah pindah halaman
                var productId = button.getAttribute('data-product-id');

                // Kirim request untuk menambah ke wishlist (gunakan AJAX atau sesuaikan dengan kebutuhan Anda)
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '/wishlist', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Update UI atau beri feedback kepada pengguna (misalnya mengganti warna ikon)
                        var icon = button.querySelector('i');
                        icon.classList.toggle('text-danger');
                        icon.classList.toggle('text-secondary');
                    }
                };
                xhr.send('product_id=' + productId);
            });
        });
    });
</script>
