@extends('layouts.index')

@section('title', 'Categories Product')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card category-card shadow-sm border-0 rounded-lg">
            <div class="card-header category-card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0">List of Product Categories</h2>
                <a href="{{ route('categories.create') }}" class="btn category-btn-add-category me-2">
                    <i class="fas fa-plus"></i> Add Category
                </a>
            </div>
            <div class="card-body category-card-body p-4">
                <div class="table-responsive">
                    <table class="table category-table table-striped table-hover w-100">
                        <thead class="category-thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Main Category</th>
                                <th>Sub-Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $index => $category)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
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
                                            class="btn category-btn-edit-category btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn category-btn-delete-category btn-sm" title="Delete">
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
@endsection