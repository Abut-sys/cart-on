@extends('layouts.index')

@section('title', 'Products')

@section('content')
    <div class="container-fluid mt-4">
        <div class="product-index-head-row mb-4 position-relative">
            <h2 class="text-center w-100 fw-bold">List of Products</h2>
            <a href="{{ route('products.create') }}" class="product-index-btn-add">
                <i class="fas fa-plus"></i> Add Product
            </a>
        </div>

        <form method="GET" action="{{ route('products.index') }}" class="mb-4">
            <div class="product-index-head-row">
                <div class="product-index-left-col col-md-4 me-2">
                    <input type="text" name="search" class="product-index-search"
                        placeholder="Search by ID, Name, Category, Brand, Variant..." value="{{ request('search') }}">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="product-index-btn-filter">Search</button>
                    <a href="{{ route('products.index') }}" class="product-index-btn-reset">Reset</a>
                    <a href="{{ route('qr.scan') }}" class="btn btn-outline-success">
                        <i class="fas fa-camera"></i> Scan QR
                    </a>
                </div>
            </div>
        </form>

        <div class="table-responsive mt-4">
            <table class="product-index-table text-center align-middle">
                <thead class="product-index-thead-light">
                    <tr>
                        @php
                            $sortableColumns = [
                                'id' => 'ID',
                                'name' => 'Name',
                                'old_price' => 'Old Price',
                                'price' => 'Price',
                                'sales' => 'Sales',
                                'sub_category' => 'SubCategory',
                                'brand' => 'Brand',
                                'rating' => 'Rating',
                            ];
                        @endphp

                        @foreach ($sortableColumns as $key => $label)
                            @php
                                $isSorted = request('sort_column') === $key;
                                $currentDir = request('sort_direction');
                                $dir = $isSorted ? ($currentDir === 'asc' ? 'desc' : 'asc') : 'desc';
                                $icon = $isSorted
                                    ? 'fas fa-sort-' . ($currentDir === 'asc' ? 'up' : 'down')
                                    : 'fas fa-sort';
                            @endphp

                            <th>
                                <a href="{{ route('products.index', array_merge(request()->query(), ['sort_column' => $key, 'sort_direction' => $dir])) }}"
                                    class="text-decoration-none text-white">
                                    {{ $label }} <i class="{{ $icon }}"></i>
                                </a>
                            </th>
                        @endforeach

                        <th>Color</th>
                        <th>Size</th>
                        <th>Stock</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($products as $product)
                        <tr class="product-index-row">
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>Rp {{ number_format($product->old_price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>{{ $product->sales }}</td>
                            <td>{{ $product->subCategory->name ?? 'N/A' }}</td>
                            <td>{{ $product->brand->name ?? 'N/A' }}</td>
                            <td>{{ $product->rating }}</td>

                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach ($product->subVariant as $variant)
                                        <li>{{ $variant->color }}</li>
                                    @endforeach
                                </ul>
                            </td>

                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach ($product->subVariant as $variant)
                                        <li>{{ $variant->size }}</li>
                                    @endforeach
                                </ul>
                            </td>

                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach ($product->subVariant as $variant)
                                        <li>{{ $variant->stock }}</li>
                                    @endforeach
                                </ul>
                            </td>

                            <td>
                                @if ($product->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                        alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <span>No Image</span>
                                @endif
                            </td>

                            <td class="d-flex flex-column gap-1">
                                <a href="{{ route('products.edit', $product->id) }}" class="product-index-btn-edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="product-index-btn-delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                <a href="{{ route('products.show', $product->id) }}" class="product-index-btn-details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr class="product-index-row">
                            <td colspan="14">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $products->withQueryString()->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
