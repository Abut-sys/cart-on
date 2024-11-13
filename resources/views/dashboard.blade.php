@extends('layouts.index')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-4">
        <div class="row d-flex justify-content-between">
            <div class="col-md-3 mb-4 d-flex align-items-stretch">
                <a href="{{ route('products.index') }}" class="text-decoration-none w-100">
                    <div class="dashboard-card bg-primary hover-scale">
                        <div class="dashboard-card-body">
                            <div class="icon mb-2">
                                <i class="fas fa-boxes fa-2x"></i>
                            </div>
                            <h5 class="dashboard-card-title">Total Products</h5>
                            <p class="dashboard-card-text">{{ $totalProducts ?? '0' }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3 mb-4 d-flex align-items-stretch">
                <a href="{{ route('categories.index') }}" class="text-decoration-none w-100">
                    <div class="dashboard-card bg-success hover-scale">
                        <div class="dashboard-card-body">
                            <div class="icon mb-2">
                                <i class="fas fa-tag fa-2x"></i>
                            </div>
                            <h5 class="dashboard-card-title">Total Categories</h5>
                            <p class="dashboard-card-text">{{ $totalProductCategories ?? '0' }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3 mb-4 d-flex align-items-stretch">
                <a href="{{ route('brands.index') }}" class="text-decoration-none w-100">
                    <div class="dashboard-card bg-info hover-scale">
                        <div class="dashboard-card-body">
                            <div class="icon mb-2">
                                <i class="fas fa-star fa-2x"></i>
                            </div>
                            <h5 class="dashboard-card-title">Total Brands</h5>
                            <p class="dashboard-card-text">{{ $totalBrands ?? '0' }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3 mb-4 d-flex align-items-stretch">
                <a href="{{ route('vouchers.index') }}" class="text-decoration-none w-100">
                    <div class="dashboard-card bg-warning hover-scale">
                        <div class="dashboard-card-body">
                            <div class="icon mb-2">
                                <i class="fas fa-ticket-alt fa-2x"></i>
                            </div>
                            <h5 class="dashboard-card-title">Active Vouchers</h5>
                            <p class="dashboard-card-text">{{ $activeVouchers ?? '0' }}</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="recent-orders-card shadow-lg">
                    <div class="recent-orders-header">
                        Latest Orders
                    </div>
                    <div class="recent-orders-body">
                        <p class="no-orders-text">No recent orders found.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
