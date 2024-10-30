@extends('layouts.index')

@section('content')

<div class="container mt-5">
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
</div>

{{-- <h1>{{ $product->name }}</h1>
<p>Deskripsi: {{ $product->description }}</p>
<p>Harga: Rp {{ number_format($product->price, 0, ',', '.') }}</p>
<p>Stok: {{ $product->stock }}</p>
<img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" width="100">
<a href="{{ route('products.index') }}">Kembali ke Daftar Produk</a> --}}

@endsection
