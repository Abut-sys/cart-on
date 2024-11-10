@extends('layouts.index')

@section('content')

<div class="container-fluid mt-4">
    <div class="card mb-4 shadow-sm" style="background-color: #f0f0f0;"> <!-- Light gray background -->
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d3d3d3;">
            <h2 class="mb-0" style="color: black;">{{ $brand->name }}</h2> <!-- Brand name as header -->
            <a href="{{ route('brands.index') }}" class="btn btn-danger me-2" style="background-color: #ff0000; color: black;">
                <i class="fas fa-arrow-left"></i> Return to Brands
            </a>
        </div>
        <div class="card-body">
            <h5 class="card-title" style="color: black;">Description:</h5>
            <p class="card-text" style="color: black;">{{ $brand->description }}</p>

            @if ($brand->logo_path)
                <h5 class="card-title" style="color: black;">Logo:</h5>
                <img src="{{ asset('storage/' . $brand->logo_path) }}" alt="Logo {{ $brand->name }}" class="img-fluid" width="100">
            @endif

            <div class="mt-4">
                <a href="{{ route('brands.index') }}" class="btn btn-secondary" style="background-color: #6c757d; color: white;">Kembali ke Daftar Brand</a>
            </div>
        </div>
    </div>
</div>

@endsection
