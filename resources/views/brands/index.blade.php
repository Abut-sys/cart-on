@extends('layouts.index')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow-sm brand-card">
            <div class="card-header d-flex justify-content-between align-items-center brand-card-header">
                <h2 class="mb-0">List of Brands</h2>
                <a href="{{ route('brands.create') }}" class="btn brand-btn-add me-2">
                    <i class="fas fa-plus"></i> Add Brand
                </a>
            </div>

            <div class="card-body brand-card-body">
                <!-- Success Message -->
                @if (session('success'))
                    <div class="alert brand-alert-success text-center">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive brand-table-responsive">
                    <table class="table table-striped table-hover w-100 brand-table">
                        <thead>
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
                                            alt="Logo {{ $brand->name }}" width="50" class="brand-logo">
                                    </td>
                                    <td>
                                        <a href="{{ route('brands.show', $brand->id) }}" 
                                           class="btn btn-info btn-sm brand-btn-view" title="View">
                                           <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('brands.edit', $brand->id) }}" 
                                           class="btn btn-warning btn-sm brand-btn-edit" title="Edit">
                                           <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm brand-btn-delete" title="Delete">
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
