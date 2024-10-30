@extends('layouts.index')

@section('content')

<div class="container mt-5">
    <h1 class="text-center mb-4">Tambah Produk Baru</h1>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Nama Produk:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="description">Deskripsi:</label>
            <textarea class="form-control" id="description" name="description" rows="4"></textarea>
        </div>

        <div class="form-group">
            <label for="price">Harga:</label>
            <input type="number" class="form-control" id="price" name="price" required>
        </div>

        <div class="form-group">
            <label for="stock">Stok:</label>
            <input type="number" class="form-control" id="stock" name="stock" required>
        </div>

        <div class="form-group">
            <label for="image">Gambar:</label>
            <input type="file" class="form-control-file" id="image" name="image">
        </div>

        <button type="submit" class="btn btn-primary btn-block">Simpan</button>
    </form>

    <div class="text-center mt-4">
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali ke Daftar Produk</a>
    </div>
</div>


{{-- <h1>Tambah Produk Baru</h1>
<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label>Nama Produk:</label>
    <input type="text" name="name" required>

    <label>Deskripsi:</label>
    <textarea name="description"></textarea>

    <label>Harga:</label>
    <input type="number" name="price" required>

    <label>Stok:</label>
    <input type="number" name="stock" required>

    <label>Gambar:</label>
    <input type="file" name="image">

    <button type="submit">Simpan</button>
</form> --}}


@endsection
