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
