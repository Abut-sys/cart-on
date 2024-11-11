@extends('layouts.index')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Dashboard</h2>
        <div class="row d-flex justify-content-between">
            <!-- Overview Cards -->
            <div class="col-md-3 mb-4">
                <a href="{{ route('products.index') }}" class="text-decoration-none">
                    <div class="card text-white bg-primary border-0 shadow-lg rounded-lg hover-scale" style="border: 2px solid #004085;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <div class="mb-3">
                                <i class="fas fa-boxes fa-3x"></i>
                            </div>
                            <h5 class="card-title mb-1 fw-bold">Total Products</h5>
                            <p class="card-text fs-3 fw-bold">{{ $totalProducts ?? '0' }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3 mb-4">
                <a href="{{ route('categories.index') }}" class="text-decoration-none">
                    <div class="card text-white bg-success border-0 shadow-lg rounded-lg hover-scale" style="border: 2px solid #155724;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <div class="mb-3">
                                <i class="fas fa-tag fa-3x"></i>
                            </div>
                            <h5 class="card-title mb-1 fw-bold">Total Product Categories</h5>
                            <p class="card-text fs-3 fw-bold">{{ $totalProductCategories ?? '0' }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3 mb-4">
                <a href="{{ route('brands.index') }}" class="text-decoration-none">
                    <div class="card text-white bg-info border-0 shadow-lg rounded-lg hover-scale" style="border: 2px solid #0c5460;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <div class="mb-3">
                                <i class="fas fa-star fa-3x"></i>
                            </div>
                            <h5 class="card-title mb-1 fw-bold">Total Brands</h5>
                            <p class="card-text fs-3 fw-bold">{{ $totalBrands ?? '0' }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3 mb-4">
                <a href="{{ route('vouchers.index') }}" class="text-decoration-none">
                    <div class="card text-white bg-warning border-0 shadow-lg rounded-lg hover-scale" style="border: 2px solid #856404;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <div class="mb-3">
                                <i class="fas fa-ticket-alt fa-3x"></i>
                            </div>
                            <h5 class="card-title mb-1 fw-bold">Active Vouchers</h5>
                            <p class="card-text fs-3 fw-bold">{{ $activeVouchers ?? '0' }}</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Orders Section -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4 border-0 shadow-lg">
                    <div class="card-header bg-dark text-white">
                        Latest Orders
                    </div>
                    <div class="card-body">
                        <p class="text-muted fw-bold">No recent orders found.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
