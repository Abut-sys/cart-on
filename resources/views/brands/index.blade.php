@extends('layouts.index')

@section('title', 'Brands')

@section('content')

    <div class="brand-index-container-fluid mt-4">
        <div class="brand-index-card shadow-sm">
            <div class="brand-index-card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0">List of Brands</h2>
                <a href="{{ route('brands.create') }}" class="brand-index-btn-add-brand btn me-2">
                    <i class="fas fa-plus"></i> Add Brand
                </a>
            </div>

            <div class="brand-index-card-body">
                @if (session('success'))
                    <div class="brand-index-alert-success alert text-center">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="brand-index-table-responsive table-responsive">
                    <table class="brand-index-table table table-striped table-hover w-100">
                        <thead class="brand-index-thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Logo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($brands as $brand)
                                <tr>
                                    <td>{{ $brand->id }}</td>
                                    <td>{{ $brand->name }}</td>
                                    <td>
                                        <img src="{{ asset('storage/' . $brand->logo_path) }}"
                                            alt="Logo {{ $brand->name }}" width="50" class="brand-index-logo">
                                    </td>
                                    <td>
                                        <a href="{{ route('brands.edit', $brand->id) }}"
                                            class="brand-index-btn-edit-brand btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('brands.destroy', $brand->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="brand-index-btn-delete-brand btn btn-danger btn-sm me-3"
                                                title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('brands.show', $brand->id) }}"
                                            class="brand-index-btn-view btn btn-info btn-sm" title="View">
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
                                @if ($brands->onFirstPage())
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $brands->previousPageUrl() }}"
                                            aria-label="Previous">
                                            <span aria-hidden="true">&laquo; Previous</span>
                                        </a>
                                    </li>
                                @endif

                                <!-- Pagination Elements -->
                                @foreach ($brands->links()->elements as $element)
                                    <!-- Array Of Links -->
                                    @if (is_array($element))
                                        @foreach ($element as $page => $url)
                                            <li class="page-item {{ $brands->currentPage() == $page ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endforeach
                                    @endif
                                @endforeach

                                <!-- Next Page Link -->
                                @if ($brands->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $brands->nextPageUrl() }}" aria-label="Next">
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
@endsection
