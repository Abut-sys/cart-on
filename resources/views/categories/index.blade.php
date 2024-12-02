@extends('layouts.index')

@section('title', 'Categories Product')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4 position-relative">
            <h2 class="text-center w-100 fw-bold">Product Categories</h2>
            <a href="{{ route('categories.create') }}" class="btn category-btn-add-category">
                <i class="fas fa-plus"></i> Add Category
            </a>
        </div>

        <div class="category-form">
            <form method="GET" action="{{ route('categories.index') }}" class="mb-4">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control category-search"
                            placeholder="Search by ID or Main Category" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex align-items-center position-relative">
                            <select name="sort_id" class="form-select category-select">
                                <option value="">Sort ID</option>
                                <option value="asc" {{ request('sort_id') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                <option value="desc" {{ request('sort_id') == 'desc' ? 'selected' : '' }}>Descending</option>
                            </select>
                            <i class="fas fa-sort-down position-absolute end-0 me-2 sort-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex align-items-center position-relative">
                            <select name="sort_name" class="form-select category-select">
                                <option value="">Sort Name</option>
                                <option value="asc" {{ request('sort_name') == 'asc' ? 'selected' : '' }}>A-Z</option>
                                <option value="desc" {{ request('sort_name') == 'desc' ? 'selected' : '' }}>Z-A</option>
                            </select>
                            <i class="fas fa-sort-alpha-down position-absolute end-0 me-2 sort-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn category-btn-filter">Search</button>
                            <a href="{{ route('categories.index') }}" class="btn category-btn-reset ms-2">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive mt-4">
            <table class="table category-table">
                <thead class="category-thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Main Category</th>
                        <th>Sub-Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr class="category-row">
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                <ul class="list-unstyled category-list mb-0">
                                    @foreach ($category->subCategories as $subCategory)
                                        <li>{{ $subCategory->name }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <a href="{{ route('categories.edit', $category->id) }}"
                                    class="btn btn-sm btn-warning category-btn-edit-category">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger category-btn-delete-category">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <nav>
                {{ $categories->withQueryString()->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    </div>
@endsection
