@extends('layouts.index')

@section('title', 'Product Categories')

@section('content')
    <div class="container-fluid mt-4">
        <div class="category-index-head-row mb-4 position-relative">
            <h2 class="text-center w-100 fw-bold">Product Categories</h2>
            <a href="{{ route('categories.create') }}" class="category-index-btn-add-category">
                <i class="fas fa-plus"></i> Add Category
            </a>
        </div>

        <form method="GET" action="{{ route('categories.index') }}" class="mb-4">
            <div class="category-index-head-row">
                <div class="category-index-left-col col-md-4 me-2">
                    <input type="text" name="search" class="category-index-search"
                        placeholder="Search by ID, Name, or Sub-category" value="{{ request('search') }}">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="category-index-btn-filter">Search</button>
                    <a href="{{ route('categories.index') }}" class="category-index-btn-reset">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive mt-4">
            <table class="category-index-table text-center align-middle">
                <thead class="category-index-thead-light">
                    <tr>
                        @php
                            $columns = [
                                'id' => 'ID',
                                'name' => 'Main Category',
                                'sub_category' => 'Sub-Category',
                            ];
                        @endphp

                        @foreach ($columns as $col => $label)
                            @php
                                $isSorted = request('sort_column') === $col;
                                $dir = $isSorted ? (request('sort_direction') === 'asc' ? 'desc' : 'asc') : 'desc';
                                $icon = $isSorted
                                    ? 'fas fa-sort-' . (request('sort_direction') === 'asc' ? 'up' : 'down')
                                    : 'fas fa-sort';
                            @endphp
                            <th>
                                <a href="{{ route('categories.index', array_merge(request()->query(), ['sort_column' => $col, 'sort_direction' => $dir])) }}"
                                    class="text-decoration-none text-white">
                                    {{ $label }} <i class="{{ $icon }}"></i>
                                </a>
                            </th>
                        @endforeach
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr class="category-index-row">
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                <ul class="category-index-list">
                                    @foreach ($category->subCategories as $subCategory)
                                        <li>{{ $subCategory->name }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <a href="{{ route('categories.edit', $category->id) }}"
                                    class="category-index-btn-edit-category">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="category-index-btn-delete-category">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr class="category-index-row">
                            <td colspan="4">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 category-index-pagination">
            <nav>
                {{ $categories->withQueryString()->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    </div>
@endsection
