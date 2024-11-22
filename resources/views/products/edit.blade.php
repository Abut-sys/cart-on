@extends('layouts.index')

@section('title', 'Edit Product')

@section('content')
    <div class="container mt-4">
        <div class="card product-create-card mb-4 shadow-sm">
            <div class="card-header product-create-card-header d-flex justify-content-between">
                <h2 class="mb-0 product-create-title">Edit Product</h2>
                <a href="{{ route('products.index') }}" class="btn product-create-btn-return">
                    <i class="fas fa-arrow-left"></i> Return
                </a>
            </div>
            <div class="card-body product-create-card-body">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group product-create-form-group">
                        <label for="name" class="product-create-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control product-create-input"
                            value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="product-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group product-create-form-group">
                        <label for="image_path" class="product-create-label">Image</label>
                        <input type="file" name="image_path" id="image_path" class="form-control product-create-input"
                            accept="image/*">
                        @if ($product->image_path)
                            <div class="mt-2">
                                <img src="{{ Storage::url($product->image_path) }}" alt="Product Image" width="100">
                            </div>
                        @endif
                        @error('image_path')
                            <div class="product-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group product-create-form-group">
                        <label for="price" class="product-create-label">Price</label>
                        <input type="number" name="price" id="price" class="form-control product-create-input"
                            value="{{ old('price', $product->price) }}" required>
                        @error('price')
                            <div class="product-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group product-create-form-group">
                        <label for="description" class="product-create-label">Description</label>
                        <textarea name="description" id="description" class="form-control product-create-input" rows="4">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="product-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group product-create-form-group">
                        <label for="sub_category_product_id" class="product-create-label">Sub-Category</label>
                        <select name="sub_category_product_id" id="sub_category_product_id"
                            class="form-control product-create-input" required>
                            <option value="">Choose Sub-Category</option>
                            @foreach ($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}"
                                    {{ old('sub_category_product_id', $product->sub_category_product_id) == $subcategory->id ? 'selected' : '' }}>
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
                                <option value="{{ $brand->id }}"
                                    {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <div class="product-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <h3 class="mt-4">Product Variants</h3>
                    <ul class="list-unstyled product-create-variations-list" id="variations-container">
                        @foreach ($product->subVariant as $index => $variant)
                            <li class="product-create-variasi-item mb-3">
                                <div class="d-flex flex-wrap align-items-center">
                                    <div class="me-3">
                                        <label for="variants[{{ $index }}][color]"
                                            class="product-create-label">Color</label>
                                        <input type="text" name="variants[{{ $index }}][color]"
                                            class="form-control product-create-input"
                                            value="{{ old('variants.' . $index . '.color', $variant->color) }}">
                                    </div>
                                    <div class="me-3">
                                        <label for="variants[{{ $index }}][size]"
                                            class="product-create-label">Size</label>
                                        <input type="text" name="variants[{{ $index }}][size]"
                                            class="form-control product-create-input"
                                            value="{{ old('variants.' . $index . '.size', $variant->size) }}">
                                    </div>
                                    <div class="me-3">
                                        <label for="variants[{{ $index }}][stock]"
                                            class="product-create-label">Stock</label>
                                        <input type="number" name="variants[{{ $index }}][stock]"
                                            class="form-control product-create-input"
                                            value="{{ old('variants.' . $index . '.stock', $variant->stock) }}">
                                    </div>
                                    <div>
                                        <button type="button" class="btn product-create-btn-remove"
                                            onclick="removeVariant(this)">Remove</button>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <button type="button" class="btn product-create-btn-add" onclick="addVariant()">Add More
                        Variant</button>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success w-100 product-create-btn-confirm">Update
                            Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let variationIndex = {{ count($product->subVariant) }};

            // Add variant input fields
            window.addVariant = function() {
                let container = document.getElementById('variations-container');
                let newVariation = document.createElement('li');
                newVariation.classList.add('product-create-variasi-item', 'mb-3');
                newVariation.innerHTML = `
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="me-3">
                            <label for="variants[${variationIndex}][color]" class="product-create-label">Color</label>
                            <input type="text" name="variants[${variationIndex}][color]" class="form-control product-create-input">
                        </div>
                        <div class="me-3">
                            <label for="variants[${variationIndex}][size]" class="product-create-label">Size</label>
                            <input type="text" name="variants[${variationIndex}][size]" class="form-control product-create-input">
                        </div>
                        <div class="me-3">
                            <label for="variants[${variationIndex}][stock]" class="product-create-label">Stock</label>
                            <input type="number" name="variants[${variationIndex}][stock]" class="form-control product-create-input">
                        </div>
                        <button type="button" class="btn product-create-btn-remove" onclick="removeVariant(this)">Remove</button>
                    </div>
                `;
                container.appendChild(newVariation);
                variationIndex++;
            };

            window.removeVariant = function(button) {
                button.closest('li').remove();
            };
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
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .product-create-alert-danger {
            color: red;
            font-size: 14px;
        }

        .product-create-btn-remove {
            background-color: red;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }

        .product-create-btn-add {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            cursor: pointer;
        }

        .product-create-btn-confirm {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border: none;
            cursor: pointer;
        }

        .product-create-variasi-item {
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
@endsection
