@extends('layouts.index')

@section('content')

<div class="container mt-5">
    <h1 class="text-center mb-4">Edit Produk</h1>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nama Produk:</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
        </div>

        <div class="form-group">
            <label for="description">Deskripsi:</label>
            <textarea class="form-control" id="description" name="description" rows="4">{{ $product->description }}</textarea>
        </div>

        <div class="form-group">
            <label for="price">Harga:</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ $product->price }}" required>
        </div>

        <div class="form-group">
            <label for="stock">Stok:</label>
            <input type="number" class="form-control" id="stock" name="stock" value="{{ $product->stock }}" required>
        </div>

        <div class="form-group">
            <label for="image">Gambar:</label>
            <input type="file" class="form-control-file" id="image" name="image">
            @if ($product->image_path)
                <small class="form-text text-muted">Gambar saat ini:</small>
                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" width="100" class="img-thumbnail">
            @endif
        </div>

        <button type="submit" class="btn btn-primary btn-block">Perbarui</button>
    </form>

    <div class="text-center mt-4">
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali ke Daftar Produk</a>
    </div>
</div>

{{-- <h1>Edit Produk</h1>
<form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <label>Nama Produk:</label>
    <input type="text" name="name" value="{{ $product->name }}" required>

    <label>Deskripsi:</label>
    <textarea name="description">{{ $product->description }}</textarea>

    <label>Harga:</label>
    <input type="number" name="price" value="{{ $product->price }}" required>

    <label>Stok:</label>
    <input type="number" name="stock" value="{{ $product->stock }}" required>

    <label>Gambar:</label>
    <input type="file" name="image">

    <button type="submit">Perbarui</button>
</form> --}}

@endsection
