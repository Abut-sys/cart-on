@extends('layouts.index')

@section('content')

<div class="container-fluid mt-4">
    <div class="card mb-4 shadow-sm" style="background-color: #f0f0f0;"> <!-- Light gray background -->
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d3d3d3;">
            <h2 class="mb-0" style="color: black;">{{ $product->name }}</h2> <!-- Product name as header -->
            <a href="{{ route('products.index') }}" class="btn btn-danger me-2" style="background-color: #ff0000; color: black;">
                <i class="fas fa-arrow-left"></i> Return to Products
            </a>
        </div>
        <div class="card-body">
            <h5 class="card-title" style="color: black;">Description:</h5>
            <p class="card-text" style="color: black;">{{ $product->description }}</p>

            <h5 class="card-title" style="color: black;">Price:</h5>
            <p class="card-text" style="color: black;">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

            <h5 class="card-title" style="color: black;">Stock:</h5>
            <p class="card-text" style="color: black;">{{ $product->stock }}</p>

            @if ($product->image_path)
                <h5 class="card-title" style="color: black;">Image:</h5>
                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="img-fluid">
            @endif

            {{-- <div class="mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-secondary" style="background-color: #6c757d; color: white;">Kembali ke Daftar Produk</a>
            </div> --}}
        </div>
    </div>
</div>


{{-- <div class="container mt-5">
    <h1 class="text-center">{{ $product->name }}</h1>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Deskripsi:</h5>
            <p class="card-text">{{ $product->description }}</p>

            <h5 class="card-title">Harga:</h5>
            <p class="card-text">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

            <h5 class="card-title">Stok:</h5>
            <p class="card-text">{{ $product->stock }}</p>

            @if ($product->image_path)
                <h5 class="card-title">Gambar:</h5>
                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="img-fluid">
            @endif

            <div class="mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali ke Daftar Produk</a>
            </div>
        </div>
    </div>
</div> --}}

{{-- <h1>{{ $product->name }}</h1>
<p>Deskripsi: {{ $product->description }}</p>
<p>Harga: Rp {{ number_format($product->price, 0, ',', '.') }}</p>
<p>Stok: {{ $product->stock }}</p>
<img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" width="100">
<a href="{{ route('products.index') }}">Kembali ke Daftar Produk</a> --}}

@endsection
