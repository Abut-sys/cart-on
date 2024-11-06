@extends('layouts.index')

@section('content')
    <div class="container mt-4">
        <div class="card category-edit-card mb-4 shadow-sm">
            <div class="card-header category-edit-card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0 category-edit-title">Add Product Category</h2>
                <a href="{{ route('categories.index') }}" class="btn category-edit-btn-return me-2">
                    <i class="fas fa-arrow-left"></i> Return
                </a>
            </div>
            <div class="card-body category-edit-card-body">
                <form action="{{ route('categories.update', $category->id) }}" method="POST" id="categoryForm">
                    @csrf
                    @method('PUT')

                    <div class="form-group category-edit-form-group mb-3">
                        <label for="category_name" class="category-edit-label">Name Category</label>
                        <input type="text" class="form-control category-edit-input" id="category_name" name="name"
                            value="{{ $category->name }}" required>
                    </div>

                    <h2 class="category-edit-subcategory-title mb-3">Sub-Category</h2>
                    <ul class="list-unstyled category-edit-subcategory-list mb-3" id="subcategory-list">
                        @foreach ($category->subCategories as $subCategory)
                            <li class="category-edit-subcategory-item mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <input type="text" class="form-control category-edit-subcategory-input me-2"
                                        name="subcategories[{{ $subCategory->id }}]" value="{{ $subCategory->name }}"
                                        required>
                                    <button type="button" class="btn category-edit-btn-delete"
                                        onclick="removeSubcategory(this, {{ $subCategory->id }})">Delete</button>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
                        <button type="button" class="btn category-edit-btn-add-subcategory" id="add-subcategory">
                            <i class="fas fa-plus"></i> Add Sub-Category
                        </button>

                        <div class="ms-auto">
                            <button type="submit" class="btn category-edit-btn-save">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
