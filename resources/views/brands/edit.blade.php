@extends('layouts.index')

@section('title', 'Brands')

@section('content')
<div class="brand-edit-container mt-4">
    <div class="brand-edit-card shadow-lg">
        <div class="brand-edit-card-header d-flex justify-content-between align-items-center">
            <h2 class="brand-edit-title mb-0">Edit Brand</h2>
            <a href="{{ route('brands.index') }}" class="brand-edit-btn-return">
                <i class="fas fa-arrow-left"></i> Return
            </a>
        </div>

        <div class="brand-edit-card-body">
            <form action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="brand-edit-form-group">
                    <label for="name" class="brand-edit-label">Brand Name:</label>
                    <input type="text" class="form-control brand-edit-input" id="name" name="name" value="{{ $brand->name }}" required>
                </div>

                <div class="brand-edit-form-group">
                    <label for="category_product_id" class="brand-edit-label">Category Product</label>
                    <select name="category_product_id" id="category_product_id" class="brand-edit-input" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_product_id', $brand->category_product_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="brand-edit-form-group">
                    <label for="description" class="brand-edit-label">Description</label>
                    <textarea class="brand-edit-input" id="description" name="description" rows="4">{{ $brand->description }}</textarea>
                </div>

                <div class="brand-edit-form-group">
                    <label for="logo" class="brand-edit-label">Logo:</label>
                    <input type="file" class="form-control-file" id="logo" name="logo">
                    @if ($brand->logo_path)
                        <small class="form-text text-muted">Logo saat ini:</small>
                        <img src="{{ asset('storage/' . $brand->logo_path) }}" alt="Logo {{ $brand->name }}" width="100" class="brand-edit-img-thumbnail">
                    @endif
                </div>
                <div class="brand-edit-btn-container mt-4">
                    <button type="submit" class="brand-edit-btn-update">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
