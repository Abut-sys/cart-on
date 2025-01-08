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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const subcategoryList = document.getElementById('subcategory-list');
            const addSubcategoryBtn = document.getElementById('add-subcategory');

            // Add new subcategory with fadeSlideDown animation
            addSubcategoryBtn.addEventListener('click', function() {
                const newSubcategoryItem = document.createElement('li');
                newSubcategoryItem.className =
                'category-edit-subcategory-item mb-2 fadeSlideDown'; // Apply fadeSlideDown animation
                newSubcategoryItem.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <input type="text" class="form-control category-edit-subcategory-input"
                    name="new_subcategories[]" placeholder="Sub Category" required>
                <button type="button" class="btn btn-link text-danger remove-subcategory">Delete</button>
            </div>
        `;
                subcategoryList.appendChild(newSubcategoryItem);

                // Add event listener for the remove button
                newSubcategoryItem.querySelector('.remove-subcategory').addEventListener('click',
            function() {
                    removeSubcategory(newSubcategoryItem);
                });
            });

            // Remove existing subcategory with fadeSlideUp animation
            subcategoryList.addEventListener('click', function(event) {
                if (event.target && event.target.classList.contains('remove-subcategory')) {
                    const subcategoryItem = event.target.closest('li');
                    removeSubcategory(subcategoryItem);
                }
            });

            function removeSubcategory(subcategoryItem) {
                // Apply fadeSlideUp animation before removing
                subcategoryItem.classList.add('fadeSlideUp'); // Apply fadeSlideUp animation

                // Mark the subcategory for deletion if it exists in the database
                const subCategoryId = subcategoryItem.dataset.id;
                if (subCategoryId) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'deleted_subcategories[]';
                    hiddenInput.value = subCategoryId;
                    document.getElementById('categoryForm').appendChild(hiddenInput);
                }

                // Wait for the animation before removing the element
                setTimeout(() => {
                    subcategoryItem.remove();
                }, 400); // Matches the animation duration
            }
        });
    </script>
@endsection
