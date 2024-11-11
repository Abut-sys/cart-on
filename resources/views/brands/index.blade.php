@extends('layouts.index')

@section('title', 'Brands')

@section('content')

<div class="container-fluid mt-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d3d3d3; color: black;">
            <!-- Title and Add Button in Header -->
            <h2 class="mb-0">List of Brands</h2>
            <a href="{{ route('brands.create') }}" class="btn btn-success me-2" style="background-color: #00FF00; color: black;">
                <i class="fas fa-plus"></i> Add Brand
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif
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
                                        <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="brand-index-btn-delete-brand btn btn-danger btn-sm me-3" title="Delete">
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
                </div>
            </div>
        </div>
    </div>
@endsection
