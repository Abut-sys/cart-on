@extends('layouts.index')

@section('title', 'Tambah Kategori Produk')

@section('content')
    <div class="container mt-4">
        <div class="card category-create-card mb-4 shadow-sm">
            <div class="card-header category-create-card-header d-flex justify-content-between">
                <h2 class="mb-0 category-create-title">New Category Product</h2>
                <a href="{{ route('categories.index') }}" class="btn category-create-btn-return">
                    <i class="fas fa-arrow-left"></i> Return
                </a>
            </div>
            <div class="card-body category-create-card-body">
                <form action="{{ route('categories.store') }}" method="POST" id="categoryForm">
                    @csrf
                    <div class="form-group category-create-form-group">
                        <label for="category_name" class="category-create-label">Main Category</label>
                        <input type="text" class="form-control category-create-input" id="category_name" name="name"
                            required placeholder="Nama Kategori">
                    </div>

                    <label for="sub_category_name" class="category-create-label">Sub-Category</label>
                    <ul class="list-unstyled category-create-subcategory-list mb-3" id="subcategory-list">
                        <li class="category-create-subcategory-item mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <input type="text" class="form-control category-create-input sub_category_name"
                                    name="sub_category_name[]" placeholder="Sub Category">
                            </div>
                        </li>
                    </ul>

                    <button type="button" class="btn btn-primary category-create-btn-add-subcategory" id="add-subcategory">
                        <i class="fas fa-plus"></i>   Add Sub-category
                    </button>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success category-create-btn-confirm">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function handleSubcategoryForm() {
            const subcategoryList = document.getElementById('subcategory-list');

            // Add new subcategory input field
            document.getElementById('add-subcategory').addEventListener('click', function() {
                const newSubcategoryItem = document.createElement('li');
                newSubcategoryItem.className = 'category-create-subcategory-item mb-2';
                newSubcategoryItem.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <input type="text" class="form-control category-create-input sub_category_name"
                            name="sub_category_name[]" placeholder="Sub Category">
                        <button type="button" class="btn btn-link text-danger remove-subcategory">Delete</button>
                    </div>
                `;
                subcategoryList.appendChild(newSubcategoryItem);
            });

            // Handle delete subcategory
            subcategoryList.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-subcategory')) {
                    e.target.closest('li').remove();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            handleSubcategoryForm();
        });
    </script>
@endsection
