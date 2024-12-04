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
                <img src="image/category1.jpg" alt="Category 1">
            </div>
            <div class="home-user-category">
                <img src="image/category2.jpg" alt="Category 2">
            </div>
            <div class="home-user-category">
                <img src="image/category3.jpg" alt="Category 3">
            </div>
            <div class="home-user-category">
                <img src="image/category4.jpg" alt="Category 4">
            </div>
            <div class="home-user-category">
                <img src="image/category5.jpg" alt="Category 5">
            </div>
        </div>
    </div>

    <div class="home-user-shop-by-brands">
        @foreach ($categories as $category)
            <h2>Featured {{ $category->name }}</h2>
            <div class="home-user-brands-container">
                @forelse ($category->brands as $brand)
                    <div class="home-user-brand">
                        <img src="{{ asset('storage/' . $brand->logo_path) }}" alt="{{ $brand->name }}">
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
        // Ambil semua elemen dengan kelas .home-user-brands-container
        const brandsContainers = document.querySelectorAll('.home-user-brands-container');

        // Loop untuk setiap container
        brandsContainers.forEach(brandsContainer => {
            // Mouse wheel scrolling
            brandsContainer.addEventListener('wheel', (e) => {
                e.preventDefault();
                brandsContainer.scrollLeft += e.deltaY *
                0.5; // Ubah kecepatan scroll sesuai keinginan
            });

            // Dragging functionality
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
                const walk = (x - startX) * 2; // Kecepatan drag
                brandsContainer.scrollLeft = scrollLeft - walk;
            });
        });
    });
</script>
