@extends('layouts.index')

@section('title', 'Product')

@section('content')
    <div class="container mt-4">
        <div class="card mb-4 shadow-sm" style="background-color: #f0f0f0;"> <!-- Light gray background -->
            <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d3d3d3;">
                <h2 class="mb-0" style="color: black;">Edit Product</h2> <!-- Title -->
                <a href="{{ route('products.index') }}" class="btn btn-danger me-2"
                    style="background-color: #ff0000; color: black;">
                    <i class="fas fa-arrow-left"></i> Return To Product List
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3"> <!-- Added mb-3 for spacing -->
                        <label for="name" style="font-weight: bold; color: black; margin-bottom: 5px;">Product
                            Name:</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ $product->name }}" required style="background-color: #dcdcdc; border-color: #c0c0c0;">
                        <!-- Gray input -->
                    </div>

                    <div class="form-group mb-3"> <!-- Added mb-3 for spacing -->
                        <label for="description"
                            style="font-weight: bold; color: black; margin-bottom: 5px;">Description:</label>
                        <textarea class="form-control" id="description" name="description" rows="4"
                            style="background-color: #dcdcdc; border-color: #c0c0c0;">{{ $product->description }}</textarea>
                    </div>

                    <div class="form-group mb-3"> <!-- Added mb-3 for spacing -->
                        <label for="price" style="font-weight: bold; color: black; margin-bottom: 5px;">Price:</label>
                        <input type="number" class="form-control" id="price" name="price"
                            value="{{ $product->price }}" required
                            style="background-color: #dcdcdc; border-color: #c0c0c0;">
                    </div>

                    <div class="form-group mb-3"> <!-- Added mb-3 for spacing -->
                        <label for="stock" style="font-weight: bold; color: black; margin-bottom: 5px;">Stock:</label>
                        <input type="number" class="form-control" id="stock" name="stock"
                            value="{{ $product->stock }}" required
                            style="background-color: #dcdcdc; border-color: #c0c0c0;">
                    </div>

                    <div class="form-group mb-3"> <!-- Added mb-3 for spacing -->
                        <label for="image" style="font-weight: bold; color: black; margin-bottom: 5px;">Image:</label>
                        <input type="file" class="form-control-file" id="image" name="image">
                        @if ($product->image_path)
                            <small class="form-text text-muted">Gambar saat ini:</small>
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                width="100" class="img-thumbnail">
                        @endif
                    </div>

                    {{-- <div class="form-group mb-3">
                        <label for="brand_id">Brand:</label>
                        <select name="brand_id" required>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div> --}}

                    <div class="form-group mb-3">
                        <label for="brand_id">Brand:</label>
                        <select name="brands_id" id="brand_id" class="form-control">
                            <option value="">Select Brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ isset($product) && $product->brand_id == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn" style="background-color: #00ff00; color: black;">
                            <i class="fas fa-save"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- <div class="container mt-5">
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
</div> --}}

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
