@extends('layouts.index')

@section('title', 'Product')

@section('content')

    {{-- with table --}}
    <div class="container-fluid mt-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center"
                style="background-color: #d3d3d3; color: black;">

                <!-- Title and Add Button in Header -->
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
                                <th>Name</th>
                                <th>Price</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td>
                                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                            width="50">
                                    </td>
                                    <td>
                                        <!-- Icons for View, Edit, and Delete actions -->
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm"
                                            title="View">
                                            <i class="fas fa-eye"></i> Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Styled Pagination Links -->
                    <div class="mt-4 d-flex justify-content-end">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination pagination-sm"> <!-- Changed to pagination-sm for smaller size -->
                                <!-- Previous Page Link -->
                                @if ($products->onFirstPage())
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $products->previousPageUrl() }}"
                                            aria-label="Previous">
                                            <span aria-hidden="true">&laquo; Previous</span>
                                        </a>
                                    </li>
                                @endif

                                <!-- Pagination Elements -->
                                @foreach ($products->links()->elements as $element)
                                    <!-- Array Of Links -->
                                    @if (is_array($element))
                                        @foreach ($element as $page => $url)
                                            <li class="page-item {{ $products->currentPage() == $page ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endforeach
                                    @endif
                                @endforeach

                                <!-- Next Page Link -->
                                @if ($products->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $products->nextPageUrl() }}" aria-label="Next">
                                            <span aria-hidden="true">Next &raquo;</span>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next</a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                    <!-- Custom Styling -->
                    <style>
                        .pagination .page-item .page-link {
                            background-color: #f0f0f0;
                            color: #007bff;
                            border: 1px solid #ddd;
                            margin: 0 2px;
                            padding: 5px 10px;
                            /* Adjusted padding for smaller buttons */
                            font-size: 0.875rem;
                            /* Smaller font size */
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
                    </style>

                </div>
            </div>
        </div>
    </div>

    <style>
        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }
    </style>
@endsection
