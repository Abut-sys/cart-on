@extends('layouts.index')

@section('title', 'Product')

@section('content')
    <div class="container mt-4">
        <div class="card mb-4 shadow-sm" style="background-color: #f0f0f0;"> <!-- Light gray background -->
            <div class="card-header d-flex justify-content-between" style="background-color: #d3d3d3;">
                <h2 class="mb-0" style="color: black;">Add New Product</h2> <!-- Title -->
                <a href="{{ route('products.index') }}" class="btn btn-danger"
                    style="background-color: #ff0000; color: black;">
                    <i class="fas fa-arrow-left"></i> Return To Product List
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name" style="font-weight: bold; color: black; margin-bottom: 5px;">Product</label>
                        <input type="text" class="form-control" id="name" name="name" required
                            style="background-color: #dcdcdc; border-color: #c0c0c0;">
                    </div>

                    <div class="form-group mt-3">
                        <label for="description"
                            style="font-weight: bold; color: black; margin-bottom: 5px;">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4"
                            style="background-color: #dcdcdc; border-color: #c0c0c0;"></textarea>
                    </div>

                    <div class="form-group mt-3">
                        <label for="price" style="font-weight: bold; color: black; margin-bottom: 5px;">Price</label>
                        <input type="number" class="form-control" id="price" name="price" required
                            style="background-color: #dcdcdc; border-color: #c0c0c0;">
                    </div>

                    <div class="form-group mt-3">
                        <label for="stock" style="font-weight: bold; color: black; margin-bottom: 5px;">Stock</label>
                        <input type="number" class="form-control" id="stock" name="stock" required
                            style="background-color: #dcdcdc; border-color: #c0c0c0;">
                    </div>

                    <div class="form-group mt-3">
                        <label for="image" style="font-weight: bold; color: black; margin-bottom: 5px;">Image</label>
                        <input type="file" class="form-control-file" id="image" name="image">
                    </div>

                    <div class="form-group mt-3">
                        <label for="brand_id">Brand:</label>
                        <select name="brands_id" required>
                            <option value="">Select Brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <div class="form-group mt-3">
                        <label for="brand_id">Brand:</label>
                        <select name="brand_id" id="brand_id" class="form-control">
                            <option value="">Select Brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ isset($product) && $product->brand_id == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div> --}}


                    <div class="mt-4">
                        <button type="submit" class="btn confirm-btn" style="background-color: #00ff00; color: black;">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- <div class="container mt-5">
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
</div> --}}

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
