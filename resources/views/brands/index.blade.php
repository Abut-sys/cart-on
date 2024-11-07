@extends('layouts.index')

@section('title', 'Brands')

@section('content')

{{-- <h1 class="text-center my-4">Daftar Brand</h1>
<div class="text-center mb-3">
    <a href="{{ route('brands.create') }}" class="btn btn-primary">Buat Brand Baru</a>
</div> --}}
{{--
@if(session('success'))
    <div class="alert alert-success text-center">
        {{ session('success') }}
    </div>
@endif --}}

{{-- <div class="container">
    <ul class="list-group">
        @foreach ($brands as $brand)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('storage/' . $brand->logo_path) }}" alt="Logo {{ $brand->name }}" width="50" class="mr-3">
                    <div>
                        <h5 class="mb-1">{{ $brand->name }}</h5>
                    </div>
                </div>
                <div>
                    <a href="{{ route('brands.show', $brand->id) }}" class="btn btn-info btn-sm">Lihat</a>
                    <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>
</div> --}}

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

            <div class="table-responsive">
                <table class="table table-striped table-hover w-100">
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
                                    <img src="{{ asset('storage/' . $brand->logo_path) }}" alt="Logo {{ $brand->name }}" width="50">
                                </td>
                                <td>
                                    <!-- Icons for View, Edit, and Delete actions -->
                                    <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm me-3" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('brands.show', $brand->id) }}" class="btn btn-info btn-sm" title="View">
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
