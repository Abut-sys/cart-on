@extends('layouts.index')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center"
                style="background-color: #d3d3d3; color: black;">
                <!-- Title and Add Button in Header -->
                <h2 class="mb-0">List Of Product Category</h2>
                <a href="{{ route('categories.create') }}" class="btn btn-success me-2"
                    style="background-color: #00FF00; color: black;">
                    <i class="fas fa-plus"></i> Add Category
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Main Category</th>
                                <th>Sub-Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        <ul class="list-unstyled mb-0">
                                            @foreach ($category->subCategories as $subCategory)
                                                <li>{{ $subCategory->name }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <!-- Icons for Edit and Delete actions -->
                                        <a href="{{ route('categories.edit', $category->id) }}"
                                            class="btn btn-primary btn-sm" style="background-color: #0000FF; color: white;"
                                            title="Edit">
                                            <!-- Blue for Edit Category -->
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                style="background-color: #FF0000; color: white;" title="Delete">
                                                <!-- Red for Delete Category -->
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
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
