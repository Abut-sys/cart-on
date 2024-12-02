@extends('layouts.index')

@section('title', 'Brands')

@section('content')
<div class="brand-create-container mt-4">
    <div class="brand-create-card shadow-lg">
        <div class="brand-create-card-header d-flex justify-content-between align-items-center">
            <h2 class="brand-create-title mb-0">New Brand</h2>
            <a href="{{ route('brands.index') }}" class="brand-create-btn-return">
                <i class="fas fa-arrow-left"></i> Return
            </a>
        </div>

        <div class="brand-create-card-body">
            <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="brand-create-form-group">
                    <label for="name" class="brand-create-label">Brand Name</label>
                    <input type="text" class="brand-create-input" id="name" name="name" required placeholder="Nama Brand">
                </div>

                <div class="brand-create-form-group">
                    <label for="category_product_id" class="brand-create-label">Category Product</label>
                    <select name="category_product_id" id="category_product_id" class="brand-create-input" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_product_id', $brand->category_product_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>   
                </div>

                <div class="brand-create-form-group">
                    <label for="description" class="brand-create-label">Description</label>
                    <textarea class="brand-create-input" id="description" name="description" rows="4" placeholder="Deskripsi Brand"></textarea>
                </div>

                <div class="brand-create-form-group">
                    <label for="logo" class="brand-create-label">Logo</label>
                    <input type="file" class="brand-create-input-file" id="logo" name="logo" required>
                </div>

                <div class="brand-create-btn-container mt-4">
                    <button type="submit" class="brand-create-btn-confirm">
                        Confirm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
