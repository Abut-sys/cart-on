@extends('layouts.index')

@section('title', 'Categories Product')

@section('content')
    <div class="container mt-4">

        <div class="card category-create-card mb-4 shadow-sm">
            <div class="card-header category-create-card-header d-flex justify-content-between">
                <h2 class="mb-0 category-create-title">Add Product Category</h2>
                <a href="{{ route('categories.index') }}" class="btn category-create-btn-return">
                    <i class="fas fa-arrow-left"></i> Return
                </a>
            </div>
            <div class="card-body category-create-card-body">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="form-group category-create-form-group">
                        <label for="category_name" class="category-create-label">Category Name</label>
                        <input type="text" class="form-control category-create-input" id="category_name" name="name" required
                            placeholder="Category Name">
                    </div>

                    <div class="form-group category-create-form-group mt-3">
                        <label for="sub_category_name" class="category-create-label">Sub-Category</label>
                        <input type="text" class="form-control category-create-input" id="sub_category_name" name="sub_category_name"
                            placeholder="Sub Category">
                    </div>

                    <div class="category-create-btn-container mt-4">
                        <button type="submit" class="btn category-create-btn-confirm">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection