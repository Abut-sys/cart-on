@extends('layouts.index')

@section('title', 'Tambah Kategori Produk')

@section('content')
    <div class="category-create-container mt-5">
        <div class="category-create-card shadow-lg">
            <div class="category-create-card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0 fw-bold">New Category Product</h2>
                <a href="{{ route('categories.index') }}" class="category-create-btn category-create-btn-return">
                    <i class="fas fa-arrow-left"></i> Return
                </a>
            </div>
            <div class="category-create-card-body">
                <form action="{{ route('categories.store') }}" method="POST" id="categoryForm">
                    @csrf

                    <div class="category-create-form-group mb-4">
                        <label for="category_name" class="category-create-form-label">Main Category</label>
                        <input type="text" class="category-create-form-control" id="category_name" name="name"
                            required placeholder="Name Category">
                    </div>

                    <div class="category-create-form-group mb-4">
                        <label for="sub_category_name" class="category-create-form-label">Sub-Category</label>
                        <ul class="list-unstyled category-create-subcategory-list mb-3" id="subcategory-list">
                            <li class="category-create-subcategory-item mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <input type="text" class="category-create-form-control sub_category_name"
                                        name="sub_category_name[]" placeholder="Sub Category">
                                </div>
                            </li>
                        </ul>
                        <div class="mt-4">
                            <button type="button" class="category-create-btn category-create-btn-add-subcategory w-100"
                                id="add-subcategory">
                                <i class="fas fa-plus"></i> Add Sub-category
                            </button>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="category-create-btn category-create-btn-success w-100">Create
                            Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/category-form.js') }}"></script>
@endsection
