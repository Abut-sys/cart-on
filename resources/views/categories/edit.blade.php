@extends('layouts.index')

@section('title', 'Edit Kategori Produk')

@section('content')
    <div class="category-edit-container mt-5">
        <div class="category-edit-card shadow-lg">
            <div class="category-edit-card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0 fw-bold">Edit Category Product</h2>
                <a href="{{ route('categories.index') }}" class="category-edit-btn category-edit-btn-return">
                    <i class="fas fa-arrow-left"></i> Return
                </a>
            </div>
            <div class="category-edit-card-body">
                <form action="{{ route('categories.update', $category->id) }}" method="POST" id="categoryForm">
                    @csrf
                    @method('PUT')

                    <div class="category-edit-form-group mb-4">
                        <label for="category_name" class="category-edit-form-label">Main Category</label>
                        <input type="text" class="category-edit-form-control" id="category_name" name="name"
                            value="{{ old('name', $category->name) }}" required placeholder="Name Category">
                    </div>

                    <div class="category-edit-form-group mb-4">
                        <label for="sub_category_name" class="category-edit-form-label">Sub-Category</label>
                        <ul class="list-unstyled category-edit-subcategory-list mb-3" id="subcategory-list">
                            @foreach ($category->subCategories as $subCategory)
                                <li class="category-edit-subcategory-item mb-2" data-id="{{ $subCategory->id }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <input type="text" class="form-control category-edit-subcategory-input me-2"
                                            name="subcategories[{{ $subCategory->id }}]" value="{{ $subCategory->name }}"
                                            required>
                                        <button type="button"
                                            class="btn btn-link text-danger remove-subcategory">Delete</button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-4">
                            <button type="button" class="category-edit-btn category-edit-btn-add-subcategory w-100"
                                id="add-subcategory">
                                <i class="fas fa-plus"></i> Add Sub-category
                            </button>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="category-edit-btn category-edit-btn-success w-100">Save
                            Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/category-edit.js') }}"></script>
@endsection
