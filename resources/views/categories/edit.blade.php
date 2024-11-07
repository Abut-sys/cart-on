@extends('layouts.index')

@section('title', 'Categories Product')

@section('content')
    <div class="container mt-4">
        <div class="card mb-4 shadow-sm" style="background-color: #f0f0f0;"> <!-- Light gray background -->
            <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d3d3d3;">
                <!-- Light gray header -->
                <h2 class="mb-0" style="color: black;">Add Product Category</h2> <!-- Black header text -->
                <a href="{{ route('categories.index') }}" class="btn btn-danger me-2"
                    style="background-color: #ff0000; color: black;">
                    <i class="fas fa-arrow-left"></i> Return
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('categories.update', $category->id) }}" method="POST" id="categoryForm">
                    @csrf
                    @method('PUT')

                    <!-- Input for Category Name with spacing -->
                    <div class="form-group mb-3"> <!-- Added mb-3 for spacing -->
                        <label for="category_name" style="font-weight: bold; color: black; margin-bottom: 5px;">Name
                            Category</label>
                        <input type="text" class="form-control" id="category_name" name="name"
                            value="{{ $category->name }}" required
                            style="background-color: #dcdcdc; border-color: #c0c0c0;"> <!-- Gray input -->
                    </div>

                    <h2 class="mb-3" style="color: black;">Sub-Category</h2>
                    <ul class="list-unstyled mb-3" id="subcategory-list"> <!-- Added mb-3 for spacing -->
                        @foreach ($category->subCategories as $subCategory)
                            <li class="mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <input type="text" class="form-control me-2"
                                        name="subcategories[{{ $subCategory->id }}]" value="{{ $subCategory->name }}"
                                        required style="background-color: #dcdcdc; border-color: #c0c0c0;">
                                    <!-- Gray input -->
                                    <button type="button" class="btn btn-link text-danger"
                                        onclick="removeSubcategory(this, {{ $subCategory->id }})">Delete</button>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
                        <button type="button" class="btn" id="add-subcategory"
                            style="background-color: #d3d3d3; color: black;">
                            <i class="fas fa-plus"></i> Add Sub-Category
                        </button>

                        <div class="ms-auto"> <!-- Adds margin to the left of the Save Changes button -->
                            <!-- Button to Save Changes -->
                            <button type="submit" class="btn" style="background-color: #00ff00; color: black;">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
