@extends('layouts.index')

@section('title', 'Edit Product')

@section('content')
    <div class="product-edit-container mt-5">
        <div class="product-edit-card shadow-lg">
            <div class="product-edit-card-header d-flex justify-content-between align-items-center">
                <h2 class="product-edit-title mb-0 fw-bold">Edit Product</h2>
                <a href="{{ route('products.index') }}" class="product-edit-btn product-edit-btn-return">
                    <i class="fas fa-arrow-left"></i> Return
                </a>
            </div>

            <div class="product-edit-card-body">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Product Name -->
                    <div class="product-edit-form-group mb-4">
                        <label for="name" class="product-edit-label">Name</label>
                        <input type="text" name="name" id="name" class="product-edit-input"
                            value="{{ old('name', $product->name) }}" required placeholder="Product Name">
                        @error('name')
                            <div class="product-edit-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Product Image -->
                    <div class="product-edit-form-group mb-4">
                        <label for="image_path" class="product-edit-label">Image</label>
                        <input type="file" name="images[]" id="image_path" class="product-edit-input-file"
                            accept="image/*" multiple>
                        <small class="text-muted">Curent Image:</small>
                        <div class="product-edit-current-images">
                            @foreach ($product->images as $image)
                                <div class="product-edit-image-wrapper mb-2">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Image" width="100"
                                        class="img-fluid">
                                    <button type="button" class="btn btn-danger btn-sm product-edit-btn-remove-image"
                                        data-id="{{ $image->id }}">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        @error('images')
                            <div class="product-edit-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="product-edit-form-group mb-4">
                        <label for="price" class="product-edit-label">Price</label>
                        <input type="number" name="price" id="price" class="product-edit-input"
                            value="{{ old('price', $product->price) }}" required placeholder="Price">
                        @error('price')
                            <div class="product-edit-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Product Description -->
                    <div class="product-edit-form-group mb-4">
                        <label for="description" class="product-edit-label">Description</label>
                        <textarea name="description" id="description" class="product-edit-input" rows="4">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="product-edit-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Sub-Category -->
                    <div class="product-edit-form-group mb-4">
                        <label for="sub_category_product_id" class="product-edit-label">Sub-Category</label>
                        <select name="sub_category_product_id" id="sub_category_product_id" class="product-edit-input"
                            required>
                            <option value="">Choose Sub-Category</option>
                            @foreach ($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}"
                                    {{ old('sub_category_product_id', $product->sub_category_product_id) == $subcategory->id ? 'selected' : '' }}>
                                    {{ $subcategory->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('sub_category_product_id')
                            <div class="product-edit-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Brand -->
                    <div class="product-edit-form-group mb-4">
                        <label for="brand_id" class="product-edit-label">Brand</label>
                        <select name="brand_id" id="brand_id" class="product-edit-input" required>
                            <option value="">Choose Brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <div class="product-edit-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <h3 class="mt-4 mb-4">Product Variant</h3>
                    <ul class="list-unstyled product-edit-variations-list" id="variations-container">
                        @foreach ($product->subVariant as $index => $variant)
                            <li class="product-edit-variasi-item mb-3">
                                <div class="d-flex flex-wrap align-items-center">
                                    <div class="me-3">
                                        <label for="variants[{{ $index }}][color]"
                                            class="product-edit-label">Color</label>
                                        <input type="text" name="variants[{{ $index }}][color]"
                                            class="product-edit-input"
                                            value="{{ old("variants.$index.color", $variant->color) }}">
                                    </div>
                                    <div class="me-3">
                                        <label for="variants[{{ $index }}][size]"
                                            class="product-edit-label">Size</label>
                                        <input type="text" name="variants[{{ $index }}][size]"
                                            class="product-edit-input"
                                            value="{{ old("variants.$index.size", $variant->size) }}">
                                    </div>
                                    <div class="me-3">
                                        <label for="variants[{{ $index }}][stock]"
                                            class="product-edit-label">Stock</label>
                                        <input type="number" name="variants[{{ $index }}][stock]"
                                            class="product-edit-input"
                                            value="{{ old("variants.$index.stock", $variant->stock) }}">
                                    </div>
                                    <button type="button" class="btn product-edit-btn-remove">Delete</button>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <button type="button" class="product-edit-btn product-edit-btn-add w-100">More Variant</button>

                    <div class="mt-4">
                        <button type="submit" class="product-edit-btn product-edit-btn-confirm w-100">
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

            document.querySelector('.product-edit-btn-add').addEventListener('click', function() {
                // Create new variation item with dynamic index
                const newVariationItem = document.createElement('li');
                newVariationItem.className = 'product-edit-variasi-item mb-3 product-edit-variant-animated';
                newVariationItem.innerHTML = `
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="me-3">
                            <label class="product-edit-label">Color</label>
                            <input type="text" name="variants[${variationIndex}][color]" class="product-edit-input">
                        </div>
                        <div class="me-3">
                            <label class="product-edit-label">Size</label>
                            <input type="text" name="variants[${variationIndex}][size]" class="product-edit-input">
                        </div>
                        <div class="me-3">
                            <label class="product-edit-label">Stock</label>
                            <input type="number" name="variants[${variationIndex}][stock]" class="product-edit-input">
                        </div>
                        <button type="button" class="btn product-edit-btn-remove">Delete</button>
                    </div>
                `;
                variationsContainer.appendChild(newVariationItem);

                // Increment the index for the next variant
                variationIndex++;

                // Remove the animation class after a short delay
                setTimeout(() => {
                    newVariationItem.classList.remove('product-edit-variant-animated');
                }, 400);
            });

            // Event delegation to handle removing dynamically added variants
            variationsContainer.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('product-edit-btn-remove')) {
                    const variationItem = e.target.closest('li');
                    variationItem.classList.add('product-edit-variant-deleting');

                    setTimeout(() => {
                        variationItem.remove();
                    }, 400);
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            handleProductVariantForm();

            const deletedImages = new Set();

            document.querySelectorAll('.product-edit-btn-remove-image').forEach(button => {
                button.addEventListener('click', function() {
                    const imageId = this.getAttribute('data-id');
                    deletedImages.add(imageId);
                    // Remove the image preview from the UI
                    this.closest('.product-edit-image-wrapper').remove();
                });
            });

            const form = document.querySelector('form');
            form.addEventListener('submit', function() {
                deletedImages.forEach(id => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'deleted_images[]';
                    hiddenInput.value = id;
                    form.appendChild(hiddenInput);
                });
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.product-edit-btn-remove-image').forEach(button => {
                button.addEventListener('click', function() {
                    const imageId = this.getAttribute('data-id');
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('products.update', $product->id) }}';
                    form.innerHTML = `
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="deleted_images[]" value="${imageId}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                });
            });
        });
    </script>

@endsection
