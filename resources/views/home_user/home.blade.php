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
        <h2>Our Featured Brands</h2>
        <div class="home-user-brands-container">
            <div class="home-user-brands-inner">
                @foreach ($brands as $brand)
                    <div class="home-user-brand">
                        <a href="{{ route('products-all.index', ['brand' => $brand->name]) }}">
                            <img src="{{ asset('storage/' . $brand->logo_path) }}" alt="{{ $brand->name }}">
                        </a>
                    </div>
                @endforeach

                {{-- Duplikat isi untuk loop --}}
                @foreach ($brands as $brand)
                    <div class="home-user-brand">
                        <a href="{{ route('products-all.index', ['brand' => $brand->name]) }}">
                            <img src="{{ asset('storage/' . $brand->logo_path) }}" alt="{{ $brand->name }}">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@include('components.ratingWeb')

@include('components.liveChat')

@push('scripts')
    <script src="{{ asset('js/bannerSlider.js') }}"></script>
    <script src="{{ asset('js/brandsScroll.js') }}"></script>
    <script src="{{ asset('js/wishlistHandler.js') }}"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new BannerSlider();
            new BrandsScroll();
            new WishlistHandler('{{ csrf_token() }}');
        });
    </script>
@endpush
