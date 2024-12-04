@extends('layouts.index')

@section('title', 'Create Product')

@section('content')
    <div class="product-create-container mt-5">
        <div class="product-create-card shadow-lg">
            <div class="product-create-card-header d-flex justify-content-between align-items-center">
                <h2 class="product-create-title mb-0 fw-bold">Create Product</h2>
                <a href="{{ route('products.index') }}" class="product-create-btn product-create-btn-return">
                    <i class="fas fa-arrow-left"></i> Return
                </a>
            </div>

            <div class="product-create-card-body">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Product Name -->
                    <div class="product-create-form-group mb-4">
                        <label for="name" class="product-create-label">Name</label>
                        <input type="text" name="name" id="name" class="product-create-input"
                            value="{{ old('name') }}" required placeholder="Product Name">
                        @error('name')
                            <div class="product-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Product Image -->
                    <div class="product-create-form-group mb-4">
                        <label for="image_path" class="product-create-label">Image</label>
                        <input type="file" name="image_path" id="image_path" class="product-create-input-file"
                            accept="image/*" required>
                        @error('image_path')
                            <div class="product-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="product-create-form-group mb-4">
                        <label for="price" class="product-create-label">Price</label>
                        <input type="number" name="price" id="price" class="product-create-input"
                            value="{{ old('price') }}" required placeholder="Price">
                        @error('price')
                            <div class="product-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Product Description -->
                    <div class="product-create-form-group mb-4">
                        <label for="description" class="product-create-label">Description</label>
                        <textarea name="description" id="description" class="product-create-input" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="product-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Sub-Category -->
                    <div class="product-create-form-group mb-4">
                        <label for="sub_category_product_id" class="product-create-label">Sub-Category</label>
                        <select name="sub_category_product_id" id="sub_category_product_id" class="product-create-input"
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

                    <!-- Brand -->
                    <div class="product-create-form-group mb-4">
                        <label for="brand_id" class="product-create-label">Brand</label>
                        <select name="brand_id" id="brand_id" class="product-create-input" required>
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

                    <h3 class="mt-4 mb-4">Product Variant</h3>
                    <ul class="list-unstyled product-create-variations-list" id="variations-container">
                        <li class="product-create-variasi-item mb-3">
                            <div class="d-flex flex-wrap align-items-center">
                                <div class="me-3">
                                    <label for="variants[0][color]" class="product-create-label">Color</label>
                                    <input type="text" name="variants[0][color]" class="product-create-input"
                                        value="{{ old('variants.0.color') }}">
                                </div>
                                <div class="me-3">
                                    <label for="variants[0][size]" class="product-create-label">Size</label>
                                    <input type="text" name="variants[0][size]" class="product-create-input"
                                        value="{{ old('variants.0.size') }}">
                                </div>
                                <div class="me-3">
                                    <label for="variants[0][stock]" class="product-create-label">Stock</label>
                                    <input type="number" name="variants[0][stock]" class="product-create-input"
                                        value="{{ old('variants.0.stock') }}">
                                </div>
                            </div>
                        </li>
                    </ul>

                    <button type="button" class="product-create-btn product-create-btn-add w-100">More Variant</button>

                    <div class="mt-4">
                        <button type="submit" class="product-create-btn product-create-btn-confirm w-100">
                            Save Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function handleProductVariantForm() {
            const variationsContainer = document.getElementById('variations-container');
            let variationIndex = variationsContainer.children.length; // Start with the number of existing variants
    
            document.querySelector('.product-create-btn-add').addEventListener('click', function() {
                // Create a new variation item with a dynamic index
                const newVariationItem = document.createElement('li');
                newVariationItem.className = 'product-create-variasi-item mb-3 product-create-variant-animated';
                newVariationItem.innerHTML = `
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="me-3">
                            <label class="product-create-label">Color</label>
                            <input type="text" name="variants[${variationIndex}][color]" class="product-create-input">
                        </div>
                        <div class="me-3">
                            <label class="product-create-label">Size</label>
                            <input type="text" name="variants[${variationIndex}][size]" class="product-create-input">
                        </div>
                        <div class="me-3">
                            <label class="product-create-label">Stock</label>
                            <input type="number" name="variants[${variationIndex}][stock]" class="product-create-input">
                        </div>
                        <button type="button" class="btn product-create-btn-remove">Delete</button>
                    </div>
                `;
                variationsContainer.appendChild(newVariationItem);
    
                // Increment the index for the next variant
                variationIndex++;
    
                // Remove the animation class after a short delay
                setTimeout(() => {
                    newVariationItem.classList.remove('product-create-variant-animated');
                }, 400);
            });
    
            // Event delegation to handle removing dynamically added variants
            variationsContainer.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('product-create-btn-remove')) {
                    const variationItem = e.target.closest('li');
                    variationItem.classList.add('product-create-variant-deleting');
    
                    setTimeout(() => {
                        variationItem.remove();
                    }, 400);
                }
            });
        }
    
        document.addEventListener('DOMContentLoaded', function() {
            handleProductVariantForm();
        });
    </script>    

@endsection
