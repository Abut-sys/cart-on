@extends('layouts.index')

@section('title', 'Home')

@section('content')
    <div class="banner-container">
        <div class="banner">
            <img src="image/Logo_baru.png" alt="Banner 1" class="banner-image">
            <img src="image/Logo_baru.png" alt="Banner 2" class="banner-image">
            <img src="image/Logo_baru.png" alt="Banner 3" class="banner-image">
        </div>
        <div class="banner-text">
            <h1>Selamat Datang di Website Kami</h1>
            <p>Temukan berbagai informasi menarik di sini!</p>
        </div>
    </div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const banner = document.querySelector('.banner');
        const banners = document.querySelectorAll('.banner-image');
        let currentIndex = 0;

        setInterval(function() {
            currentIndex = (currentIndex + 1) % banners.length;
            banner.style.transform = `translateX(-${currentIndex * 100}%)`;
        }, 5000);
    });
</script>
