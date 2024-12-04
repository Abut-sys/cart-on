@extends('layouts.index')

@section('title', 'Brands')

@section('content')
    <div class="brand-index-container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4 position-relative">
            <h2 class="text-center w-100 fw-bold">Brand Categories</h2>
            <a href="{{ route('brands.create') }}" class="btn brand-index-btn-add-brand">
                <i class="fas fa-plus"></i> Add Brand
            </a>
        </div>

        <!-- Filter Form -->
        <div class="brand-index-form">
            <form method="GET" action="{{ route('brands.index') }}" class="mb-4">
                <div class="brand-index-head-row d-flex justify-content-between align-items-center">
                    <div class="brand-index-left-col d-flex">
                        <div class="brand-index-col-md-3 me-2">
                            <input type="text" name="search" class="form-control brand-index-search" placeholder="Search by ID or Name" value="{{ request('search') }}">
                        </div>
                        <div class="brand-index-col-md-2 me-2">
                            <div class="d-flex align-items-center position-relative">
                                <select name="sort_id" class="form-select brand-index-select">
                                    <option value="">Sort ID</option>
                                    <option value="asc" {{ request('sort_id') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                    <option value="desc" {{ request('sort_id') == 'desc' ? 'selected' : '' }}>Descending</option>
                                </select>
                                <i class="fas fa-sort-down position-absolute end-0 me-2 brand-index-sort-icon"></i>
                            </div>
                        </div>
                        <div class="brand-index-col-md-2 me-2">
                            <div class="d-flex align-items-center position-relative">
                                <select name="sort_name" class="form-select brand-index-select pe-5">
                                    <option value="">Sort Name</option>
                                    <option value="asc" {{ request('sort_name') == 'asc' ? 'selected' : '' }}>A-Z</option>
                                    <option value="desc" {{ request('sort_name') == 'desc' ? 'selected' : '' }}>Z-A</option>
                                </select>
                                <i class="fas fa-sort-alpha-down position-absolute end-0 me-2 brand-index-sort-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="brand-index-right-col">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn brand-index-btn-filter">Search</button>
                            <a href="{{ route('brands.index') }}" class="btn brand-index-btn-reset ms-2">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>        

        <!-- Brands Table -->
        <div class="brand-index-table-responsive table-responsive mt-4">
            <table class="table brand-index-table">
                <thead class="brand-index-thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Logo</th>
                        <th>Category Product</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($brands as $brand)
                        <tr class="brand-index-row">
                            <td>{{ $brand->id }}</td>
                            <td>{{ $brand->name }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $brand->logo_path) }}" alt="Logo {{ $brand->name }}"
                                    width="50" class="brand-index-logo">
                            </td>
                            <td>{{ $brand->categoryProduct->name ?? 'No Category' }}</td>
                            <td>
                                <a href="{{ route('brands.edit', $brand->id) }}"
                                    class="btn btn-sm btn-warning brand-index-btn-edit-brand">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger brand-index-btn-delete-brand">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                <a href="{{ route('brands.show', $brand->id) }}"
                                    class="btn btn-sm btn-info brand-index-btn-view-brand">
                                    <i class="fas fa-eye"></i> Details
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            <nav>
                {{ $brands->withQueryString()->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    </div>
@endsection
