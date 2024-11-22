@extends('layouts.index')

@section('title', 'Product')

@section('content')

    {{-- Product Table --}}
    <div class="container-fluid mt-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center"
                style="background-color: #d3d3d3; color: black;">
                <h2 class="mb-0">List Of Products</h2>
                <a href="{{ route('products.create') }}" class="btn btn-success me-2"
                    style="background-color: #00FF00; color: black;">
                    <i class="fas fa-plus"></i> Add Product
                </a>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success text-center">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-hover w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>SubCategory</th>
                                <th>Brand</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>
                                        @if ($product->image_path)
                                            <img src="{{ asset('storage/' . $product->image_path) }}"
                                                alt="{{ $product->name }}" width="50" class="img-fluid">
                                        @else
                                            <span>No Image</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->subCategory->name ?? 'N/A' }}</td>
                                    <td>{{ $product->brand->name ?? 'N/A' }}</td>
                                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="d-flex">
                                        <a href="{{ route('products.edit', $product->id) }}"
                                            class="btn btn-warning btn-sm mx-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                            style="display:inline;" class="mx-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('products.show', $product->id) }}"
                                            class="btn btn-info btn-sm mx-1" title="View">
                                            <i class="fas fa-eye"></i> Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4 d-flex justify-content-end">
                        {{ $products->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .pagination .page-item .page-link {
            background-color: #f0f0f0;
            color: #007bff;
            border: 1px solid #ddd;
            margin: 0 2px;
            padding: 5px 10px;
            font-size: 0.875rem;
            transition: background-color 0.3s ease;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        .pagination .page-item:hover .page-link:not(.active) {
            background-color: #dcdcdc;
            color: #007bff;
        }

        .pagination .page-item.disabled .page-link {
            color: #999;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }
    </style>
@endsection
