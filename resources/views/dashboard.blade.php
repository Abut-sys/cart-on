@extends('layouts.index')

@section('title', 'Dashboard')

@section('dongol')
    <div class="modern-dashboard">
        <div class="dashboard-container">
            <!-- Welcome Section -->
            <div class="welcome-section">
                <div class="welcome-content">
                    <h1 class="welcome-title">Dashboard Overview</h1>
                    <p class="welcome-subtitle">Track your business performance at a glance</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <!-- Enhanced Sales Overview Card -->
                <div class="stat-card sales-card enhanced-sales" data-aos="fade-up" data-aos-delay="100">
                    <div class="sales-background-pattern"></div>
                    <div class="stat-card-header">
                        <div class="stat-icon sales-icon">
                            <i class="fas fa-chart-line"></i>
                            <div class="icon-pulse sales-pulse"></div>
                        </div>
                        <div class="stat-trend">
                            @php
                                $trend = $salesTrend ?? '0%';
                                $trendValue = (float) str_replace('%', '', $trend);
                                $isPositive = $trendValue >= 0;
                            @endphp
                            <span class="trend-badge {{ $isPositive ? 'trend-up' : 'trend-down' }}">
                                <i class="fas {{ $isPositive ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                                {{ abs($trendValue) }}%
                            </span>
                        </div>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-title enhanced-title">
                            <i class="fas fa-chart-bar"></i>
                            Sales Overview
                        </h3>
                        <div class="sales-highlight">
                            <div class="sales-period">This Month</div>
                        </div>
                        <div class="stat-metrics enhanced-metrics">
                            <div class="metric orders-metric">
                                <div class="metric-icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="metric-data">
                                    <span class="metric-value">{{ $totalOrders ?? '0' }}</span>
                                    <span class="metric-label">Total Orders</span>
                                </div>
                            </div>
                            <div class="metric revenue-metric">
                                <div class="metric-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="metric-data">
                                    <span class="metric-value">{{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</span>
                                    <span class="metric-label">Revenue (IDR)</span>
                                </div>
                            </div>
                        </div>
                        <div class="sales-footer">
                            <div class="sales-comparison">
                                <span class="comparison-text">vs last month</span>
                                <span class="comparison-value {{ $isPositive ? 'positive' : 'negative' }}">
                                    {{ $isPositive ? '+' : '' }}{{ $trend }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Card -->
                <div class="stat-card products-card" data-aos="fade-up" data-aos-delay="200">
                    <a href="{{ route('products.index') }}" class="card-link">
                        <div class="stat-card-header">
                            <div class="stat-icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="stat-action">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-title">Products</h3>
                            <div class="stat-number">{{ $totalProducts ?? '0' }}</div>

                            @if ($topProducts->isNotEmpty() && $topProducts->sum('total_sold') > 0)
                                <div class="top-products">
                                    <h6 class="section-subtitle">
                                        <i class="fas fa-fire"></i>
                                        Top Sellers
                                    </h6>
                                    <div class="product-list">
                                        @foreach ($topProducts->take(3) as $product)
                                            <div class="product-item">
                                                <span class="product-name">{{ Str::limit($product->name, 20) }}</span>
                                                <span class="product-badge">{{ $product->total_sold }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="empty-state">
                                    <span class="empty-text">No sales data</span>
                                </div>
                            @endif
                        </div>
                    </a>
                </div>

                <!-- Vouchers Card -->
                <div class="stat-card vouchers-card" data-aos="fade-up" data-aos-delay="300">
                    <a href="{{ route('vouchers.index') }}" class="card-link">
                        <div class="stat-card-header">
                            <div class="stat-icon voucher-icon">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-title">Vouchers</h3>
                            <div class="stat-number">{{ $activeVouchers ?? '0' }}</div>

                            <div class="voucher-status">
                                <div class="status-item active-status">
                                    <div class="status-indicator active"></div>
                                    <span class="status-label">Active</span>
                                </div>
                            </div>

                            @if (isset($recentVouchers) && $recentVouchers->isNotEmpty())
                                <div class="recent-vouchers">
                                    <h6 class="section-subtitle">
                                        <i class="fas fa-clock"></i>
                                        Recent
                                    </h6>
                                    <div class="voucher-list">
                                        @foreach ($recentVouchers->take(2) as $voucher)
                                            <div class="voucher-item">
                                                <div class="voucher-info">
                                                    <span class="voucher-code">{{ $voucher->code }}</span>
                                                    <span class="voucher-discount">
                                                        {{ $voucher->type === 'percentage'
                                                            ? $voucher->discount_value . '%'
                                                            : 'IDR ' . number_format($voucher->discount_value, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="empty-state">
                                    <span class="empty-text">No recent vouchers</span>
                                </div>
                            @endif
                        </div>
                    </a>
                </div>
            </div>

            <!-- Analytics Section -->
            <div class="analytics-section">
                <!-- Chart Card -->
                <div class="chart-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="chart-header">
                        <div class="chart-title">
                            <h3>Orders Analytics</h3>
                            <p>Weekly order distribution</p>
                        </div>
                        <div class="chart-filters">
                            <select id="yearFilter" class="filter-select">
                                @foreach ($availableYears as $year)
                                    <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                            <select id="monthFilter" class="filter-select">
                                <option value="0" {{ $selectedMonth == 0 ? 'selected' : '' }}>All Months</option>
                                @foreach (range(1, 12) as $month)
                                    <option value="{{ $month }}" {{ $month == $selectedMonth ? 'selected' : '' }}>
                                        {{ date('M', mktime(0, 0, 0, $month, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="chart-body">
                        @if ($weekdayOrders->isNotEmpty())
                            <canvas id="weekdayOrdersChart"></canvas>
                        @else
                            <div class="empty-chart">
                                <i class="fas fa-chart-line"></i>
                                <p>No data available for selected period</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Customers Card -->
                <div class="customers-card" data-aos="fade-up" data-aos-delay="600">
                    <div class="customers-header">
                        <h3>Recent Online Customers</h3>
                        <div class="online-indicator">
                            <span class="pulse-dot"></span>
                            <span>Live</span>
                        </div>
                    </div>
                    <div class="customers-body">
                        @if ($recentCustomers->isNotEmpty())
                            <div class="customer-list">
                                @foreach ($recentCustomers as $customer)
                                    <div class="customer-item">
                                        <div class="customer-avatar">
                                            @if ($customer->profile && $customer->profile->profile_picture)
                                                <img src="{{ asset('storage/profile_pictures/' . $customer->profile->profile_picture) }}"
                                                    alt="{{ $customer->name }}" class="avatar-image">
                                            @else
                                                <div class="avatar-initials">
                                                    {{ strtoupper(substr($customer->name, 0, 2)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="customer-info">
                                            <h6 class="customer-name">{{ $customer->name }}</h6>
                                            <p class="customer-email">{{ $customer->email }}</p>
                                        </div>
                                        <div class="customer-status">
                                            <span
                                                class="status-time">{{ $customer->last_online_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-customers">
                                <i class="fas fa-users"></i>
                                <p>No recent online customers</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Brands Card -->
            <div class="stat-card brands-card" data-aos="fade-up" data-aos-delay="300">
                <a href="{{ route('brands.index') }}" class="card-link">
                    <div class="stat-card-header">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-action">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-title">Brands</h3>
                        <div class="stat-number">{{ $totalBrands ?? '0' }}</div>

                        @if ($topBrands->isNotEmpty() && $topBrands->sum('total_sold') > 0)
                            <div class="top-brands">
                                <h6 class="section-subtitle">
                                    <i class="fas fa-crown"></i>
                                    Top Brands
                                </h6>
                                <div class="brand-list">
                                    @foreach ($topBrands as $brand)
                                        <div class="brand-item">
                                            <span class="brand-name">{{ Str::limit($brand->name, 20) }}</span>
                                            <div class="brand-badge text-end">
                                                <small>{{ $brand->total_sold }} sold</small><br>
                                                <small>IDR {{ number_format($brand->total_income, 0, ',', '.') }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="empty-state">
                                <span class="empty-text">No brand data</span>
                            </div>
                        @endif
                    </div>
                </a>
            </div>
        </div>


    @endsection

    @section('dashboard')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize AOS animations
                AOS.init({
                    duration: 800,
                    easing: 'ease-out-cubic',
                    once: true
                });

                initChart();
                setupFilters();

                // Handle Turbolinks/Livewire if used
                document.addEventListener('turbolinks:load', function() {
                    initChart();
                    setupFilters();
                });
            });

            let ordersChart = null;

            function initChart() {
                const ctx = document.getElementById('weekdayOrdersChart');
                if (!ctx) return;

                if (ordersChart) {
                    ordersChart.destroy();
                }

                const weekdayOrders = @json($weekdayOrders);
                const dayLabels = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

                let orderData = Array(7).fill(0);
                weekdayOrders.forEach(order => {
                    orderData[order.day_of_week] = order.count;
                });

                // Create gradient
                const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 350);
                gradient.addColorStop(0, 'rgba(102, 126, 234, 0.8)');
                gradient.addColorStop(1, 'rgba(118, 75, 162, 0.1)');

                ordersChart = new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: dayLabels,
                        datasets: [{
                            label: 'Orders',
                            data: orderData,
                            backgroundColor: gradient,
                            borderColor: '#667eea',
                            borderWidth: 3,
                            pointBackgroundColor: '#667eea',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 3,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointHitRadius: 15,
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                borderColor: '#667eea',
                                borderWidth: 1,
                                cornerRadius: 12,
                                displayColors: false,
                                callbacks: {
                                    title: function(context) {
                                        return dayLabels[context[0].dataIndex];
                                    },
                                    label: function(context) {
                                        return `${context.parsed.y} orders`;
                                    }
                                }
                            },
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)',
                                    drawBorder: false
                                },
                                ticks: {
                                    color: '#6b7280',
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    color: '#6b7280',
                                    font: {
                                        size: 12,
                                        weight: '600'
                                    }
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        }
                    }
                });
            }

            function setupFilters() {
                const yearFilter = document.getElementById('yearFilter');
                const monthFilter = document.getElementById('monthFilter');

                if (!yearFilter || !monthFilter) return;

                yearFilter.removeEventListener('change', handleFilterChange);
                monthFilter.removeEventListener('change', handleFilterChange);

                yearFilter.addEventListener('change', handleFilterChange);
                monthFilter.addEventListener('change', handleFilterChange);
            }

            let filterDebounce;

            function handleFilterChange() {
                clearTimeout(filterDebounce);

                filterDebounce = setTimeout(() => {
                    const year = document.getElementById('yearFilter').value;
                    const month = document.getElementById('monthFilter').value;

                    // Show loading state with modern spinner
                    const chartContainer = document.querySelector('.chart-body');
                    if (chartContainer) {
                        chartContainer.innerHTML = `
                        <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100%; color: #667eea;">
                            <div style="width: 40px; height: 40px; border: 3px solid #e5e7eb; border-top: 3px solid #667eea; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 1rem;"></div>
                            <p style="margin: 0; font-weight: 500;">Updating chart...</p>
                        </div>
                        <style>
                            @keyframes spin {
                                0% { transform: rotate(0deg); }
                                100% { transform: rotate(360deg); }
                            }
                        </style>
                    `;
                    }

                    window.location.href = `{{ route('dashboard.index') }}?year=${year}&month=${month}`;
                }, 500);
            }
        </script>
    @endsection

