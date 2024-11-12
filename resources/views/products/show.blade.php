@extends('layouts.index')

@section('title', 'Details Product')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card mb-4 shadow-sm" style="background-color: #f0f0f0;"> <!-- Light gray background -->
            <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d3d3d3;">
                <h2 class="mb-0" style="color: black;">{{ $product->name }}</h2> <!-- Product name as header -->
                <a href="{{ route('products.index') }}" class="btn btn-danger me-2"
                    style="background-color: #ff0000; color: black;">
                    <i class="fas fa-arrow-left"></i> Return to Products
                </a>
            </div>
            <div class="card-body">

                <h5 class="card-title" style="color: black;">Brand:</h5>
                <p>Brand: {{ $product->brand ? $product->brand->name : 'No brand assigned' }}</p>

                <h5 class="card-title" style="color: black;">Category:</h5>
                <p>{{ $product->categoryProduct ? $product->categoryProduct->name : 'No category assigned' }}</p>

                <h5 class="card-title" style="color: black;">Sub Category:</h5>
                <p>{{ $product->subCategoryProduct ? $product->subCategoryProduct->name : 'No subcategory assigned' }}</p>

                <h5 class="card-title" style="color: black;">Description:</h5>
                <p class="card-text" style="color: black;">{{ $product->description }}</p>

                <h5 class="card-title" style="color: black;">Price:</h5>
                <p class="card-text" style="color: black;">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                <h5 class="card-title" style="color: black;">Stock:</h5>
                <p class="card-text" style="color: black;">{{ $product->stock }}</p>

                <h4>Sub-Variants:</h4>
                <ul>
                    @foreach ($product->subVariants as $subVariant)
                        <li>{{ $subVariant->name }}</li>
                    @endforeach
                </ul>


                @if ($product->image_path)
                    <h5 class="card-title" style="color: black;">Image:</h5>
                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="img-fluid">
                @endif
            </div>
        </div>
    </div>
@endsection
