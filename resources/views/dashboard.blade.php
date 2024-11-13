@extends('layouts.index')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Dashboard</h2>
        <div class="row d-flex justify-content-between">
            <!-- Overview Cards -->
            <div class="col-md-3 mb-4">
                <a href="{{ route('products.index') }}" class="text-decoration-none">
                    <div class="card text-white bg-primary border-0 shadow-lg rounded-lg hover-scale"
                        style="border: 2px solid #004085;">
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
                    <div class="card text-white bg-success border-0 shadow-lg rounded-lg hover-scale"
                        style="border: 2px solid #155724;">
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
                    <div class="card text-white bg-info border-0 shadow-lg rounded-lg hover-scale"
                        style="border: 2px solid #0c5460;">
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
                    <div class="card text-white bg-warning border-0 shadow-lg rounded-lg hover-scale"
                        style="border: 2px solid #856404;">
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
                        @if ($weeklyOrders->isNotEmpty())
                            <canvas id="weeklyOrdersChart"></canvas>
                        @else
                            <p class="text-muted fw-bold">No recent orders found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('dashboard')
    <script>
        const ctx = document.getElementById('weeklyOrdersChart').getContext('2d');
        const weeklyOrders = @json($weeklyOrders);

        const labels = weeklyOrders.map(order => `Week ${order.week}`);
        const data = weeklyOrders.map(order => order.count);

        const weeklyOrdersChart = new Chart(ctx, {
            type: 'line', // Anda bisa mengganti dengan 'bar' atau jenis lain sesuai kebutuhan
            data: {
                labels: labels,
                datasets: [{
                    label: 'Orders per Week',
                    data: data,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Orders'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Weeks'
                        }
                    }
                }
            }
        });
    </script>
@endsection
