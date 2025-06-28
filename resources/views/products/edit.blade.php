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
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
                    id="product-edit-form">
                    @csrf
                    @method('PUT')

                    <!-- Product Name -->
                    <div class="product-edit-form-group mb-4">
                        <label for="name" class="product-edit-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                            class="product-edit-input @error('name') is-invalid @enderror"
                            value="{{ old('name', $product->name) }}" required placeholder="Product Name">
                        @error('name')
                            <div class="product-edit-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Product Images -->
                    <div class="product-edit-form-group mb-4">
                        <label for="images" class="product-edit-label">Images</label>
                        <input type="file" name="images[]" id="images"
                            class="product-edit-input-file @error('images') is-invalid @enderror" accept="image/*" multiple>
                        <small class="text-muted d-block mt-1">Max size: 2MB per image. Supported formats: JPEG, PNG, JPG,
                            GIF, SVG</small>

                        @if ($product->images->isNotEmpty())
                            <div class="mt-3">
                                <small class="text-muted">Current Images:</small>
                                <div class="product-edit-current-images d-flex flex-wrap gap-3 mt-2">
                                    @foreach ($product->images as $image)
                                        <div class="product-edit-image-wrapper position-relative"
                                            data-image-id="{{ $image->id }}">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Product Image"
                                                class="img-thumbnail"
                                                style="width: 100px; height: 100px; object-fit: cover;">
                                            <button type="button"
                                                class="btn btn-danger btn-sm position-absolute top-0 end-0 product-edit-btn-remove-image"
                                                data-id="{{ $image->id }}"
                                                style="border-radius: 50%; width: 25px; height: 25px; padding: 0;">
                                                <i class="fas fa-times" style="font-size: 12px;"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @error('images')
                            <div class="product-edit-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Base Price (from old_price) -->
                    <div class="product-edit-form-group mb-4">
                        <label for="price" class="product-edit-label">Base Price <span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="price" id="price"
                                class="product-edit-input @error('price') is-invalid @enderror"
                                value="{{ old('price', $product->old_price) }}" min="0" step="0.01" required
                                placeholder="0.00">
                        </div>
                        <small class="text-muted">Base price before PPN and markup</small>
                        @error('price')
                            <div class="product-edit-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Markup -->
                    <div class="product-edit-form-group mb-4">
                        <label for="markup" class="product-edit-label">Markup (%)</label>
                        <div class="input-group">
                            <input type="number" name="markup" id="markup"
                                class="product-edit-input @error('markup') is-invalid @enderror"
                                value="{{ old('markup', $product->markup) }}" min="0" max="100" step="0.01"
                                placeholder="0.00">
                        </div>
                        <small class="text-muted">Markup percentage (applied after PPN)</small>
                        @error('markup')
                            <div class="product-edit-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Price Calculation Display -->
                    <div class="product-edit-form-group mb-4">
                        <div class="price-calculator-display p-3 rounded"
                            style="background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); border: 1px solid #ffc107;">
                            <h6 class="mb-2 fw-bold text-warning-emphasis">
                                <i class="fas fa-calculator me-2"></i>Price Calculation
                            </h6>
                            <div class="row g-2 small">
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between">
                                        <span>Base Price:</span>
                                        <span class="fw-semibold" id="display-base-price">Rp 0</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>PPN (11%):</span>
                                        <span class="fw-semibold" id="display-ppn">Rp 0</span>
                                    </div>
                                    <div class="d-flex justify-content-between border-top pt-1">
                                        <span>Price + PPN:</span>
                                        <span class="fw-semibold" id="display-price-with-ppn">Rp 0</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between">
                                        <span>Markup Amount:</span>
                                        <span class="fw-semibold" id="display-markup-amount">Rp 0</span>
                                    </div>
                                    <div class="d-flex justify-content-between border-top pt-1 mt-2">
                                        <span class="fw-bold">Final Price:</span>
                                        <span class="fw-bold text-success" id="display-final-price">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Description -->
                    <div class="product-edit-form-group mb-4">
                        <label for="description" class="product-edit-label">Description <span
                                class="text-danger">*</span></label>
                        <textarea name="description" id="description" class="product-edit-input @error('description') is-invalid @enderror"
                            rows="4" required placeholder="Product description...">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="product-edit-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Sub-Category -->
                    <div class="product-edit-form-group mb-4">
                        <label for="sub_category_product_id" class="product-edit-label">Sub-Category <span
                                class="text-danger">*</span></label>
                        <select name="sub_category_product_id" id="sub_category_product_id"
                            class="product-edit-input @error('sub_category_product_id') is-invalid @enderror" required>
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
                        <label for="brand_id" class="product-edit-label">Brand <span class="text-danger">*</span></label>
                        <select name="brand_id" id="brand_id"
                            class="product-edit-input @error('brand_id') is-invalid @enderror" required>
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

                    <!-- Product Variants -->
                    <div class="mb-4">
                        <h3 class="mt-4 mb-2">Product Variants</h3>
                        <small class="text-muted">Product variants are optional. Leave blank if not needed.</small>

                        <ul class="list-unstyled product-edit-variations-list mt-3" id="variations-container">
                            @if ($product->subVariant->isNotEmpty())
                                @foreach ($product->subVariant as $index => $variant)
                                    <li class="product-edit-variasi-item mb-3">
                                        <div class="d-flex flex-wrap align-items-center">
                                            <div class="me-3">
                                                <label for="variants[{{ $index }}][color]"
                                                    class="product-edit-label">Color</label>
                                                <input type="text" name="variants[{{ $index }}][color]"
                                                    class="product-edit-input"
                                                    value="{{ old("variants.$index.color", $variant->color) }}"
                                                    placeholder="e.g. Red">
                                                <input type="hidden" name="variants[{{ $index }}][id]"
                                                    value="{{ $variant->id }}">
                                            </div>
                                            <div class="me-3">
                                                <label for="variants[{{ $index }}][size]"
                                                    class="product-edit-label">Size</label>
                                                <input type="text" name="variants[{{ $index }}][size]"
                                                    class="product-edit-input"
                                                    value="{{ old("variants.$index.size", $variant->size) }}"
                                                    placeholder="e.g. M, L, XL">
                                            </div>
                                            <div class="me-3">
                                                <label for="variants[{{ $index }}][stock]"
                                                    class="product-edit-label">Stock</label>
                                                <input type="number" name="variants[{{ $index }}][stock]"
                                                    class="product-edit-input"
                                                    value="{{ old("variants.$index.stock", $variant->stock) }}"
                                                    min="0" placeholder="0">
                                            </div>
                                            <button type="button" class="btn product-edit-btn-remove">Delete</button>
                                        </div>
                                    </li>
                                @endforeach
                            @else
                                <li class="text-muted" id="no-variants-message">
                                    <em>No variants added yet. Click "Add Variant" to create one.</em>
                                </li>
                            @endif
                        </ul>

                        <button type="button" class="product-edit-btn product-edit-btn-add w-100">Add Variant</button>
                    </div>

                    <!-- Hidden input for deleted images -->
                    <div id="deleted-images-container"></div>

                    <!-- Submit Button -->
                    <button type="submit" class="mt-4 product-edit-btn product-edit-btn-confirm w-100">
                        Save Product
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function handleProductVariantForm() {
            const variationsContainer = document.getElementById('variations-container');
            let variationIndex = variationsContainer.children.length; // Start with the number of existing variants

            // Hide "no variants" message when adding first variant
            function hideNoVariantsMessage() {
                const noVariantsMessage = document.getElementById('no-variants-message');
                if (noVariantsMessage) {
                    noVariantsMessage.style.display = 'none';
                }
            }

            // Show "no variants" message if no variants exist
            function showNoVariantsMessageIfNeeded() {
                const actualVariants = variationsContainer.querySelectorAll('.product-edit-variasi-item');
                const noVariantsMessage = document.getElementById('no-variants-message');

                if (actualVariants.length === 0 && noVariantsMessage) {
                    noVariantsMessage.style.display = 'block';
                }
            }

            document.querySelector('.product-edit-btn-add').addEventListener('click', function() {
                hideNoVariantsMessage();

                // Create new variation item with dynamic index
                const newVariationItem = document.createElement('li');
                newVariationItem.className = 'product-edit-variasi-item mb-3 product-edit-variant-animated';
                newVariationItem.innerHTML = `
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="me-3">
                            <label class="product-edit-label">Color</label>
                            <input type="text" name="variants[${variationIndex}][color]" class="product-edit-input" placeholder="e.g. Red">
                        </div>
                        <div class="me-3">
                            <label class="product-edit-label">Size</label>
                            <input type="text" name="variants[${variationIndex}][size]" class="product-edit-input" placeholder="e.g. M, L, XL">
                        </div>
                        <div class="me-3">
                            <label class="product-edit-label">Stock</label>
                            <input type="number" name="variants[${variationIndex}][stock]" class="product-edit-input" min="0" placeholder="0">
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
                    if (variationItem && variationItem.classList.contains('product-edit-variasi-item')) {
                        variationItem.classList.add('product-edit-variant-deleting');

                        setTimeout(() => {
                            variationItem.remove();
                            showNoVariantsMessageIfNeeded();
                        }, 400);
                    }
                }
            });
        }

        class ProductEditManager {
            constructor() {
                this.deletedImages = new Set();
                this.init();
            }

            init() {
                this.bindEvents();
                this.initPriceCalculation();
                handleProductVariantForm();
            }

            bindEvents() {
                // Remove image buttons
                document.addEventListener('click', (e) => {
                    if (e.target.closest('.product-edit-btn-remove-image')) {
                        this.removeImage(e.target.closest('.product-edit-btn-remove-image'));
                    }
                });

                // Form submit handler
                document.getElementById('product-edit-form').addEventListener('submit', (e) => {
                    this.handleFormSubmit(e);
                });
            }

            initPriceCalculation() {
                const priceInput = document.getElementById('price');
                const markupInput = document.getElementById('markup');

                // Update price calculation when inputs change
                [priceInput, markupInput].forEach(input => {
                    input.addEventListener('input', () => {
                        this.updatePriceCalculation();
                    });
                });

                // Initial calculation
                this.updatePriceCalculation();
            }

            updatePriceCalculation() {
                const basePrice = parseFloat(document.getElementById('price').value) || 0;
                const markup = parseFloat(document.getElementById('markup').value) || 0;

                // Calculate PPN (11%)
                const ppnAmount = basePrice * 0.11;
                const priceWithPPN = basePrice + ppnAmount;

                // Calculate markup
                const markupAmount = (priceWithPPN * markup) / 100;
                const finalPrice = priceWithPPN + markupAmount;

                // Update display
                document.getElementById('display-base-price').textContent = 'Rp ' + this.formatNumber(basePrice);
                document.getElementById('display-ppn').textContent = 'Rp ' + this.formatNumber(ppnAmount);
                document.getElementById('display-price-with-ppn').textContent = 'Rp ' + this.formatNumber(priceWithPPN);
                document.getElementById('display-markup-amount').textContent = 'Rp ' + this.formatNumber(markupAmount);
                document.getElementById('display-final-price').textContent = 'Rp ' + this.formatNumber(finalPrice);
            }

            formatNumber(num) {
                return new Intl.NumberFormat('id-ID').format(Math.round(num));
            }

            removeImage(button) {
                const imageId = button.getAttribute('data-id');
                const imageWrapper = button.closest('.product-edit-image-wrapper');

                this.deletedImages.add(imageId);
                imageWrapper.style.opacity = '0.5';
                imageWrapper.style.position = 'relative';

                // Add overlay to show it's marked for deletion
                const overlay = document.createElement('div');
                overlay.className =
                    'position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center';
                overlay.style.backgroundColor = 'rgba(220, 53, 69, 0.8)';
                overlay.innerHTML = '<span class="text-white fw-bold">DELETED</span>';
                imageWrapper.appendChild(overlay);

                button.style.display = 'none';
            }

            handleFormSubmit(e) {
                // Add hidden inputs for deleted images
                const container = document.getElementById('deleted-images-container');
                container.innerHTML = '';

                this.deletedImages.forEach(imageId => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'deleted_images[]';
                    input.value = imageId;
                    container.appendChild(input);
                });
            }
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            new ProductEditManager();
        });
    </script>

@endsection
