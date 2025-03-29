@extends('layouts.index')

@section('title', 'Product Details')

@section('content')
    <div class="brand-show-container mt-4">
        <div class="product-show-card shadow-lg">
            <div class="product-show-card-header d-flex justify-content-between align-items-center">
                <h2 class="product-show-title mb-0">Product Details</h2>
                <a href="{{ route('products.index') }}" class="product-show-btn-return">
                    <i class="fas fa-arrow-left"></i> Return
                </a>
            </div>
            <div class="card-body product-show-card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div id="product-images-gallery">
                            <div id="main-image">
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                    alt="{{ $product->name }}" class="img-fluid product-show-image"
                                    style="max-width: 100%;">
                            </div>
                            <div id="thumbnail-images" class="mt-3" style="overflow-x: auto; white-space: nowrap;">
                                @foreach ($product->images as $image)
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Thumbnail"
                                        class="img-fluid product-show-thumbnail"
                                        data-full-image="{{ asset('storage/' . $image->image_path) }}"
                                        style="width: 150px; height: 150px; object-fit: cover; cursor: pointer; margin-right: 15px; display: inline-block;">
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h3 class="product-show-title">{{ $product->name }}</h3>
                        <p class="product-show-price"><strong>Price:</strong> {{ number_format($product->price, 2) }}</p>
                        <p class="product-show-description"><strong>Description:</strong> {{ $product->description }}</p>
                        <p class="product-show-subcategory"><strong>Subcategory:</strong> {{ $product->subCategory->name }}</p>
                        <p class="product-show-brand"><strong>Brand:</strong> {{ $product->brand->name }}</p>

                        <h4 class="product-show-variants-title">Variants:</h4>
                        <table class="table product-show-variants-table">
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

    <script>
        document.querySelectorAll('.product-show-thumbnail').forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                const mainImage = document.getElementById('main-image').querySelector('img');
                mainImage.src = this.getAttribute('data-full-image');
            });
        });
    </script>
@endsection