@extends('layouts.index')

@section('title', 'Brands')

@section('content')
    <div class="container-fluid mt-4">
        <div class="brand-index-head-row mb-4 position-relative">
            <h2 class="text-center w-100 brand-index-title">Brand Categories</h2>
            <a href="{{ route('brands.create') }}" class="brand-index-btn-add-brand">
                <i class="fas fa-plus"></i> Add Brand
            </a>
        </div>

        @if (session('success'))
            <div id="success-alert" class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ route('brands.index') }}" class="mb-4">
            <div class="brand-index-head-row">
                <div class="brand-index-left-col col-md-4 me-2">
                    <input type="text" name="search" class="brand-index-search"
                        placeholder="Search by ID, Name, or Category" value="{{ request('search') }}">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="brand-index-btn-filter">Search</button>
                    <a href="{{ route('brands.index') }}" class="brand-index-btn-reset">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive mt-4">
            <table class="brand-index-table text-center align-middle">
                <thead class="brand-index-thead-light">
                    <tr>
                        @php
                            $columns = [
                                'id' => 'ID',
                                'name' => 'Name',
                                'category' => 'Category',
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
                                <a href="{{ route('brands.index', array_merge(request()->query(), ['sort_column' => $col, 'sort_direction' => $dir])) }}"
                                    class="text-decoration-none text-white">
                                    {{ $label }} <i class="{{ $icon }}"></i>
                                </a>
                            </th>
                        @endforeach
                        <th>Logo</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($brands as $brand)
                        <tr class="brand-index-row">
                            <td>{{ $brand->id }}</td>
                            <td>{{ $brand->name }}</td>
                            <td>{{ $brand->categoryProduct->name ?? 'No Category' }}</td>
                            <td>
                                @if ($brand->logo_path)
                                    <img src="{{ asset('storage/' . $brand->logo_path) }}" alt="{{ $brand->name }}"
                                        style="max-width: 60px; max-height: 60px;" class="img-fluid rounded">
                                @else
                                    <span class="badge bg-secondary">No Logo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('brands.edit', $brand->id) }}" class="brand-index-btn-edit-brand">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this brand?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="brand-index-btn-delete-brand">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <nav>
                {{ $brands->withQueryString()->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    </div>
    <script src="{{ asset('js/brand-alerts.js') }}"></script>
@endsection
