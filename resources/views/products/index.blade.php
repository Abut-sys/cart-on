@extends('layouts.index')

@section('title', 'Product')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4 position-relative">
            <h2 class="text-center w-100 fw-bold product-index-title">List of Products</h2>
            <a href="{{ route('products.create') }}" class="btn btn-success product-index-btn-add">
                <i class="fas fa-plus"></i> Add Product
            </a>
        </div>

        <div class="product-index-form">
            <form method="GET" action="{{ route('products.index') }}" class="mb-4">
                <div class="product-index-head-row d-flex justify-content-between align-items-center">
                    <div class="product-index-left-col d-flex">
                        <div class="product-index-col-md-4 me-2">
                            <input type="text" name="search" class="form-control product-index-search pe-5"
                                placeholder="Search by ID or Product Name" value="{{ request('search') }}">
                        </div>
                        <div class="product-index-col-md-2 me-2">
                            <div class="d-flex align-items-center position-relative">
                                <select name="sort_id" class="form-select product-index-select pe-3">
                                    <option value disabled selected ="">Sort ID</option>
                                    <option value="asc" {{ request('sort_id') == 'asc' ? 'selected' : '' }}>ASC</option>    
                                    <option value="desc" {{ request('sort_id') == 'desc' ? 'selected' : '' }}>DESC</option>
                                </select>
                                <i class="fas fa-sort-down position-absolute end-0 me-2 product-index-sort-icon"></i>
                            </div>
                        </div>
                        <div class="product-index-col-md-2">
                            <div class="d-flex align-items-center position-relative">
                                <select name="sort_name" class="form-select product-index-select pe-5">
                                    <option value disabled selected ="">Sort Name</option>
                                    <option value="asc" {{ request('sort_name') == 'asc' ? 'selected' : '' }}>A-Z</option>
                                    <option value="desc" {{ request('sort_name') == 'desc' ? 'selected' : '' }}>Z-A</option>
                                </select>
                                <i class="fas fa-sort-alpha-down position-absolute end-0 me-2 product-index-sort-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="product-index-right-col">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn product-index-btn-filter">Search</button>
                            <a href="{{ route('products.index') }}" class="btn product-index-btn-reset ms-2">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive mt-4">
            <table class="table product-index-table">
                <thead class="product-index-thead-light">
                    <tr>
                        <th class="product-index-th">ID</th>
                        <th class="product-index-th">Image</th>
                        <th class="product-index-th">Name</th>
                        <th class="product-index-th">SubCategory</th>
                        <th class="product-index-th">Brand</th>
                        <th class="product-index-th">Price</th>
                        <th class="product-index-th">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr class="product-index-row">
                            <td class="product-index-td">{{ $product->id }}</td>
                            <td class="product-index-td">
                                @if ($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}"
                                         alt="{{ $product->name }}" width="50" class="img-fluid product-index-img">
                                @else
                                    <span class="product-index-no-image">No Image</span>
                                @endif
                            </td>
                            <td class="product-index-td">{{ $product->name }}</td>
                            <td class="product-index-td">{{ $product->subCategory->name ?? 'N/A' }}</td>
                            <td class="product-index-td">{{ $product->brand->name ?? 'N/A' }}</td>
                            <td class="product-index-td">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="product-index-td">
                                <a href="{{ route('products.edit', $product->id) }}"
                                   class="btn btn-sm btn-warning product-index-btn-edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                      class="d-inline product-index-form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger product-index-btn-delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                <a href="{{ route('products.show', $product->id) }}"
                                   class="btn btn-sm btn-info product-index-btn-details">
                                    <i class="fas fa-eye"></i> Details
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <nav class="product-index-pagination">
                {{ $products->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    </div>
@endsection
