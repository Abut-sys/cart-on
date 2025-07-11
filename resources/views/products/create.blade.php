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
                        <label for="images" class="product-create-label">Images</label>
                        <input type="file" name="images[]" id="images" class="product-create-input-file"
                            accept="image/*" multiple required>
                        @error('images.*')
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

                    <!-- PPN -->
                    <div class="product-create-form-group mb-4">
                        <label for="ppn" class="product-create-label">PPN (%)</label>
                        <input type="number" name="ppn" id="ppn" class="product-create-input"
                            value="{{ old('ppn') }}" placeholder="Contoh: 11">
                        @error('ppn')
                            <div class="product-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Markup -->
                    <div class="product-create-form-group mb-4">
                        <label for="markup" class="product-create-label">Markup (%)</label>
                        <input type="number" name="markup" id="markup" class="product-create-input"
                            value="{{ old('markup') }}" placeholder="Contoh: 10">
                        @error('markup')
                            <div class="product-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Price Calculation Display -->
                    <div class="price-calculation-display mb-4"
                        style="background: linear-gradient(135deg, #f1dd6f, #ffed4a); border-radius: 10px; padding: 20px; border: 2px solid #f1c40f; box-shadow: 0 4px 10px rgba(255, 215, 0, 0.3);">
                        <div class="d-flex align-items-center mb-3">
                            <h5 class="mb-0 fw-bold" style="color: #b8860b;">Price Calculation</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="calculation-item mb-2">
                                    <span class="fw-semibold" style="color: #8b4513;">Base Price:</span>
                                    <span class="float-end fw-bold" style="color: #8b4513;" id="display-base-price">Rp
                                        0</span>
                                </div>
                                <div class="calculation-item mb-2">
                                    <span class="fw-semibold" style="color: #8b4513;">PPN Amount:</span>
                                    <span class="float-end fw-bold" style="color: #8b4513;" id="display-ppn-amount">Rp
                                        0</span>
                                </div>
                                <div class="calculation-item mb-2">
                                    <span class="fw-semibold" style="color: #8b4513;">Markup Amount:</span>
                                    <span class="float-end fw-bold" style="color: #8b4513;" id="display-markup-amount">Rp
                                        0</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="final-price text-center p-3"
                                    style="background: rgba(255, 255, 255, 0.7); border-radius: 8px; border: 2px dashed #f39c12;">
                                    <div class="fw-semibold mb-1" style="color: #8b4513;">Final Selling Price</div>
                                    <div class="h4 mb-0 fw-bold" style="color: #d35400;" id="display-final-price">Rp 0</div>
                                </div>
                            </div>
                        </div>
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
                            <option value disabled selected ="">Choose Sub-Category</option>
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
                            <option value disabled selected ="">Choose Brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <div class="product-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <h3 class="mt-4 mb-4">Product Variant <small class="text-muted">(Optional)</small></h3>
                    <ul class="list-unstyled product-create-variations-list" id="variations-container">
                        <li class="product-create-variasi-item mb-3">
                            <div class="d-flex flex-wrap align-items-center">
                                <div class="me-3">
                                    <label for="variants[0][color]" class="product-create-label">Color</label>
                                    <input type="text" name="variants[0][color]" class="product-create-input"
                                        value="{{ old('variants.0.color') }}" placeholder="Optional">
                                </div>
                                <div class="me-3">
                                    <label for="variants[0][size]" class="product-create-label">Size</label>
                                    <input type="text" name="variants[0][size]" class="product-create-input"
                                        value="{{ old('variants.0.size') }}" placeholder="Optional">
                                </div>
                                <div class="me-3">
                                    <label for="variants[0][stock]" class="product-create-label">Stock</label>
                                    <input type="number" name="variants[0][stock]" class="product-create-input"
                                        value="{{ old('variants.0.stock') }}" placeholder="Optional">
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
                            <input type="text" name="variants[${variationIndex}][color]" class="product-create-input" placeholder="Optional">
                        </div>
                        <div class="me-3">
                            <label class="product-create-label">Size</label>
                            <input type="text" name="variants[${variationIndex}][size]" class="product-create-input" placeholder="Optional">
                        </div>
                        <div class="me-3">
                            <label class="product-create-label">Stock</label>
                            <input type="number" name="variants[${variationIndex}][stock]" class="product-create-input" placeholder="Optional">
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

        function handlePriceCalculation() {
            const priceInput = document.getElementById('price');
            const ppnInput = document.getElementById('ppn');
            const markupInput = document.getElementById('markup');

            const displayBasePrice = document.getElementById('display-base-price');
            const displayPpnAmount = document.getElementById('display-ppn-amount');
            const displayMarkupAmount = document.getElementById('display-markup-amount');
            const displayFinalPrice = document.getElementById('display-final-price');

            function formatCurrency(amount) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(amount);
            }

            function calculatePrice() {
                const basePrice = parseFloat(priceInput.value) || 0;
                const ppnPercentage = parseFloat(ppnInput.value) || 0;
                const markupPercentage = parseFloat(markupInput.value) || 0;

                const ppnAmount = (basePrice * ppnPercentage) / 100;
                const markupAmount = (basePrice * markupPercentage) / 100;
                const finalPrice = basePrice + ppnAmount + markupAmount;

                displayBasePrice.textContent = formatCurrency(basePrice);
                displayPpnAmount.textContent = formatCurrency(ppnAmount);
                displayMarkupAmount.textContent = formatCurrency(markupAmount);
                displayFinalPrice.textContent = formatCurrency(finalPrice);
            }

            // Add event listeners to update calculation in real-time
            priceInput.addEventListener('input', calculatePrice);
            ppnInput.addEventListener('input', calculatePrice);
            markupInput.addEventListener('input', calculatePrice);

            // Initial calculation
            calculatePrice();
        }

        document.addEventListener('DOMContentLoaded', function() {
            handleProductVariantForm();
            handlePriceCalculation();
        });
    </script>

@endsection
