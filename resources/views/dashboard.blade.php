@extends('layouts.index')

@section('title', 'Dashboard')

@section('content')
<div class="container mt-4">
    <div class="row d-flex justify-content-between">
        <!-- Dashboard Cards -->
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

    <!-- Charts Section -->
    <div class="row">
        <div class="col-md-7">
            <div class="recent-orders-card shadow-lg">
                <div class="recent-orders-header">
                    Latest Orders
                </div>
                <div class="recent-orders-body">
                    @if ($weeklyOrders->isNotEmpty())
                        <canvas id="weeklyOrdersChart"></canvas>
                    @else
                        <p class="no-orders-text">No recent orders found.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="recent-orders-card shadow-lg">
                <div class="recent-orders-header">
                    Online Customers Per Week
                </div>
                <div class="recent-orders-body">
                    @if ($weeklyCustomers->isNotEmpty())
                        <canvas id="weeklyCustomersChart"></canvas>
                    @else
                        <p class="no-orders-text">No data found for online customers.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('dashboard')
<script>
    // Orders Chart
    const ctxOrders = document.getElementById('weeklyOrdersChart').getContext('2d');
    const weeklyOrders = @json($weeklyOrders);

    const orderLabels = weeklyOrders.map(order => `Week ${order.week}`);
    const orderData = weeklyOrders.map(order => order.count);

    new Chart(ctxOrders, {
        type: 'line',
        data: {
            labels: orderLabels,
            datasets: [{
                label: 'Orders per Week',
                data: orderData,
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

    // Customers Chart
    const ctxCustomers = document.getElementById('weeklyCustomersChart').getContext('2d');
    const weeklyCustomers = @json($weeklyCustomers);

    const customerLabels = weeklyCustomers.map(customer => `Week ${customer.week}`);
    const customerData = weeklyCustomers.map(customer => customer.count);

    new Chart(ctxCustomers, {
        type: 'pie',
        data: {
            labels: customerLabels,
            datasets: [{
                label: 'Online Customers',
                data: customerData,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            }
        }
    });
</script>
@endsection
