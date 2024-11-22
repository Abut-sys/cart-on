@extends('layouts.index')

@section('title', 'Product Details')

@section('content')
    <div class="container mt-4">
        <div class="card product-show-card mb-4 shadow-sm">
            <div class="card-header product-show-card-header d-flex justify-content-between">
                <a href="{{ route('products.index') }}" class="btn product-show-btn-return">
                    <i class="fas fa-arrow-left"></i> Return
                </a>
            </div>
            <div class="card-body product-show-card-body">
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                            class="img-fluid">
                    </div>
                    <div class="col-md-8">
                        {{-- product --}}
                        <h3>{{ $product->name }}</h3>
                        <p><strong>Price:</strong>{{ number_format($product->price, 2) }}</p>
                        <p><strong>Description:</strong> {{ $product->description }}</p>

                        {{-- SubCategory --}}
                        <p><strong>Subcategory:</strong> {{ $product->subCategory->name }}</p>

                        {{-- Brand --}}
                        <p><strong>Brand:</strong> {{ $product->brand->name }}</p>

                        {{-- Variants --}}
                        <h4>Variants:</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product->subVariant as $variant)
                                    <tr>
                                        <td>{{ $variant->color }}</td>
                                        <td>{{ $variant->size }}</td>
                                        <td>{{ $variant->stock }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

    <style>
        .product-show-card-header {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-show-btn-return {
            color: white;
            text-decoration: none;
            font-size: 14px;
        }

        .product-show-card-body {
            padding: 20px;
        }

        .product-show-title {
            font-weight: bold;
            font-size: 24px;
        }

        .btn {
            padding: 8px 12px;
            font-size: 14px;
            border-radius: 4px;
        }

        .btn-warning {
            background-color: #f39c12;
            color: white;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }

        .btn-warning:hover {
            background-color: #e67e22;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }
    </style>
