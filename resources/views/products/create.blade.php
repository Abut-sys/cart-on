@extends('layouts.index')

@section('title', 'Create Product')

@section('content')
    <div class="container mt-4">
        <div class="card product-create-card mb-4 shadow-sm">
            <div class="card-header product-create-card-header d-flex justify-content-between">
                <h2 class="mb-0 product-create-title">Create Product</h2>
                <a href="{{ route('products.index') }}" class="btn product-create-btn-return">
                    <i class="fas fa-arrow-left"></i> Return
                </a>
            </div>
            <div class="card-body product-create-card-body">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group product-create-form-group">
                        <label for="name" class="product-create-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control product-create-input"
                            value="{{ old('name') }}" required>
                        @error('name')
                            <div class="product-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group product-create-form-group">
                        <label for="image_path" class="product-create-label">Image</label>
                        <input type="file" name="image_path" id="image_path" class="form-control product-create-input"
                            accept="image/*" required>
                        @error('image_path')
                            <div class="product-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group product-create-form-group">
                        <label for="price" class="product-create-label">Price</label>
                        <input type="number" name="price" id="price" class="form-control product-create-input"
                            value="{{ old('price') }}" required>
                        @error('price')
                            <div class="product-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group product-create-form-group">
                        <label for="description" class="product-create-label">Description</label>
                        <textarea name="description" id="description" class="form-control product-create-input" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="product-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group product-create-form-group">
                        <label for="sub_category_product_id" class="product-create-label">Sub-Category</label>
                        <select name="sub_category_product_id" id="sub_category_product_id" class="form-control product-create-input"
                            required>
                            <option value="">Choose Sub-Category</option>
                            @foreach ($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}"
                                    {{ old('sub_category_product_id') == $subcategory->id ? 'selected' : '' }}>
                                    {{ $subcategory->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('sub_category_product_id')
                            <div class="product-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group product-create-form-group">
                        <label for="brand_id" class="product-create-label">Brand</label>
                        <select name="brand_id" id="brand_id" class="form-control product-create-input" required>
                            <option value="">Choose Brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <div class="product-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <h3 class="mt-4">Product Variant</h3>
                    <ul class="list-unstyled product-create-variations-list" id="variations-container">
                        <li class="product-create-variasi-item mb-3">
                            <div class="d-flex flex-wrap align-items-center">
                                <div class="me-3">
                                    <label for="warna[]" class="product-create-label">Color</label>
                                    <input type="text" name="variants[0][color]"
                                        class="form-control product-create-input" value="{{ old('variants.0.color') }}">
                                </div>
                                <div class="me-3">
                                    <label for="ukuran[]" class="product-create-label">Size</label>
                                    <input type="text" name="variants[0][size]" class="form-control product-create-input"
                                        value="{{ old('variants.0.size') }}">
                                </div>
                                <div class="me-3">
                                    <label for="stok[]" class="product-create-label">Stock</label>
                                    <input type="number" name="variants[0][stock]"
                                        class="form-control product-create-input" value="{{ old('variants.0.stock') }}">
                                </div>
                                <div>
                                    <button type="button" class="btn product-create-btn-remove">Delete</button>
                                </div>
                            </div>
                        </li>
                    </ul>

                    <button type="button" class="btn product-create-btn-add">More Variant</button>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success w-100 product-create-btn-confirm">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
         document.addEventListener('DOMContentLoaded', function() {
            let variationIndex = 1;

            document.querySelector('.product-create-btn-add').addEventListener('click', function() {
                let container = document.getElementById('variations-container');
                let newVariation = document.createElement('li');
                newVariation.classList.add('product-create-variasi-item', 'mb-3');
                newVariation.innerHTML = ` 
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="me-3">
                            <label for="variants[${variationIndex}][color]" class="product-create-label">Warna</label>
                            <input type="text" name="variants[${variationIndex}][color]" class="form-control product-create-input">
                        </div>
                        <div class="me-3">
                            <label for="variants[${variationIndex}][size]" class="product-create-label">Ukuran</label>
                            <input type="text" name="variants[${variationIndex}][size]" class="form-control product-create-input">
                        </div>
                        <div class="me-3">
                            <label for="variants[${variationIndex}][stock]" class="product-create-label">Stok</label>
                            <input type="number" name="variants[${variationIndex}][stock]" class="form-control product-create-input">
                        </div>
                        <button type="button" class="btn product-create-btn-remove">Hapus</button>
                    </div>
                `;
                container.appendChild(newVariation);
                variationIndex++;
            });

            document.getElementById('variations-container').addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('product-create-btn-remove')) {
                    e.target.closest('li').remove();
                }
            });
        });
    </script>

    <style>
        .product-create-card-header {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-create-btn-return {
            color: white;
            text-decoration: none;
            font-size: 14px;
        }

        .product-create-card-body {
            padding: 20px;
        }

        .product-create-form-group {
            margin-bottom: 20px;
        }

        .product-create-label {
            font-weight: bold;
        }

        .product-create-input {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .product-create-btn-add {
            background-color: #757575;
            color: white;
            margin-top: 10px;
        }

        .product-create-btn-confirm {
            background-color: #4CAF50;
            color: white;
            width: 100%;
        }

        .product-create-btn-remove {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .product-create-btn-remove:hover {
            background-color: #d32f2f;
        }

        .product-create-btn-add:hover {
            background-color: #616161;
        }

        .product-create-btn-confirm:hover {
            background-color: #388E3C;
        }

        .product-create-alert-danger {
            color: #f44336;
            font-size: 12px;
        }

        .product-create-variasi-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-create-variasi-item .d-flex {
            display: flex;
            align-items: center;
        }
    </style>
@endsection
