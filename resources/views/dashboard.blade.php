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

        <style>
            /* ================================
                MODERN DASHBOARD CSS
                Complete styling including voucher card
                ================================ */

            .modern-dashboard {
                padding: 2rem 0;
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                min-height: 100vh;
                max-width: 100%;
                width: 100%;
            }

            .dashboard-container {
                max-width: 1400px;
                margin: 0 auto;
                padding: 0 2rem;
                width: 100%;
            }

            /* ================================
                WELCOME SECTION
                ================================ */
            .welcome-section {
                margin-bottom: 2.5rem;
                text-align: center;
            }

            .welcome-title {
                font-size: 2.5rem;
                font-weight: 700;
                color: #111827;
                margin-bottom: 0.5rem;
                text-shadow: none;
            }

            .welcome-subtitle {
                font-size: 1.1rem;
                color: #6b7280;
                margin-bottom: 0;
            }

            /* ================================
                STATS GRID LAYOUT
                ================================ */
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 1.5rem;
                margin-bottom: 2.5rem;
            }

            /* ================================
                BASE STAT CARD STYLES
                ================================ */
            .stat-card {
                background: #ffffff;
                border-radius: 20px;
                padding: 1.8rem;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                border: 1px solid #e5e7eb;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                position: relative;
                overflow: hidden;
            }

            .stat-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
            }

            .stat-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            }

            /* ================================
                ENHANCED SALES CARD
                ================================ */
            .enhanced-sales {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                position: relative;
                overflow: hidden;
            }

            .enhanced-sales::before {
                background: linear-gradient(90deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0.1) 100%);
            }

            .sales-background-pattern {
                position: absolute;
                top: -50%;
                right: -30%;
                width: 200px;
                height: 200px;
                background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
                border-radius: 50%;
                pointer-events: none;
            }

            .sales-background-pattern::after {
                content: '';
                position: absolute;
                top: 60%;
                left: -40%;
                width: 150px;
                height: 150px;
                background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
                border-radius: 50%;
            }

            .enhanced-sales .stat-card-header {
                margin-bottom: 1rem;
            }

            .sales-icon {
                background: rgba(255, 255, 255, 0.2) !important;
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.3);
                color: white !important;
            }

            .sales-pulse {
                background: rgba(255, 255, 255, 0.3) !important;
            }

            .enhanced-title {
                color: white !important;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                font-size: 1.2rem;
            }

            .sales-highlight {
                margin-bottom: 1.5rem;
            }

            .sales-period {
                background: rgba(255, 255, 255, 0.15);
                padding: 0.4rem 1rem;
                border-radius: 20px;
                font-size: 0.85rem;
                font-weight: 600;
                display: inline-block;
                backdrop-filter: blur(10px);
            }

            .enhanced-metrics {
                background: rgba(255, 255, 255, 0.1);
                padding: 1.5rem;
                border-radius: 16px;
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                margin-bottom: 1.5rem;
            }

            .enhanced-metrics .metric {
                display: flex;
                align-items: center;
                gap: 1rem;
                padding: 0.8rem 0;
            }

            .metric-icon {
                width: 40px;
                height: 40px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.1rem;
            }

            .metric-data {
                flex: 1;
            }

            .enhanced-metrics .metric-value {
                color: white;
                font-size: 1.8rem;
            }

            .enhanced-metrics .metric-label {
                color: rgba(255, 255, 255, 0.8);
            }

            .sales-footer {
                border-top: 1px solid rgba(255, 255, 255, 0.2);
                padding-top: 1rem;
            }

            .sales-comparison {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .comparison-text {
                color: rgba(255, 255, 255, 0.8);
                font-size: 0.9rem;
            }

            .comparison-value {
                font-weight: 700;
                font-size: 1rem;
            }

            .comparison-value.positive {
                color: #10b981;
            }

            .comparison-value.negative {
                color: #f87171;
            }

            /* ================================
                STAT CARD COMPONENTS
                ================================ */
            .stat-card-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1.5rem;
            }

            .stat-icon {
                width: 60px;
                height: 60px;
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                color: white;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                position: relative;
            }

            .icon-pulse {
                position: absolute;
                width: 100%;
                height: 100%;
                border-radius: 16px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                opacity: 0.3;
                animation: pulse-animation 2s infinite;
            }

            @keyframes pulse-animation {
                0% {
                    transform: scale(1);
                    opacity: 0.3;
                }

                50% {
                    transform: scale(1.1);
                    opacity: 0.1;
                }

                100% {
                    transform: scale(1);
                    opacity: 0.3;
                }
            }

            .trend-badge {
                padding: 0.4rem 1rem;
                border-radius: 25px;
                font-size: 0.85rem;
                font-weight: 700;
                display: flex;
                align-items: center;
                gap: 0.3rem;
                transition: all 0.3s ease;
            }

            .trend-up {
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                color: white;
                box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            }

            .trend-down {
                background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
                color: white;
                box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
            }

            .trend-badge i {
                font-size: 0.8rem;
            }

            .stat-action {
                opacity: 0;
                transition: opacity 0.3s ease;
                color: #6b7280;
            }

            .stat-card:hover .stat-action {
                opacity: 1;
            }

            .stat-title {
                font-size: 1.1rem;
                font-weight: 600;
                color: #374151;
                margin-bottom: 1rem;
            }

            .stat-number {
                font-size: 2.5rem;
                font-weight: 700;
                color: #111827;
                line-height: 1;
            }

            .stat-metrics {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1rem;
            }

            .metric {
                text-align: center;
            }

            .metric-value {
                display: block;
                font-size: 1.8rem;
                font-weight: 700;
                color: #111827;
                line-height: 1.2;
            }

            .metric-label {
                font-size: 0.85rem;
                color: #6b7280;
                font-weight: 500;
            }

            /* ================================
                VOUCHER CARD SPECIFIC STYLES
                ================================ */
            .vouchers-card::before {
                background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
            }

            .voucher-icon {
                background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            }

            .voucher-status {
                margin-bottom: 1.5rem;
                display: flex;
                flex-wrap: wrap;
                gap: 0.8rem;
            }

            .status-item {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
                background: rgba(245, 158, 11, 0.1);
                border-radius: 12px;
                font-size: 0.85rem;
                font-weight: 600;
                color: #92400e;
            }

            .status-indicator {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background: #16a34a;
                animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            .status-indicator.active {
                background: #16a34a;
            }

            .status-indicator.inactive {
                background: #ef4444;
                animation: none;
            }

            .status-indicator.expired {
                background: #6b7280;
                animation: none;
            }

            @keyframes pulse {

                0%,
                100% {
                    opacity: 1;
                }

                50% {
                    opacity: 0.5;
                }
            }

            .recent-vouchers {
                margin-top: 1.5rem;
            }

            .section-subtitle {
                font-size: 0.9rem;
                color: #6b7280;
                margin-bottom: 1rem;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .section-subtitle i {
                font-size: 0.8rem;
                color: #9ca3af;
            }

            .voucher-list {
                display: flex;
                flex-direction: column;
                gap: 0.8rem;
            }

            .voucher-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1rem;
                background: rgba(245, 158, 11, 0.05);
                border-radius: 12px;
                transition: all 0.3s ease;
                border: 1px solid rgba(245, 158, 11, 0.1);
            }

            .voucher-item:hover {
                background: rgba(245, 158, 11, 0.1);
                transform: translateX(4px);
                border-color: rgba(245, 158, 11, 0.2);
            }

            .voucher-info {
                display: flex;
                flex-direction: column;
                gap: 0.3rem;
            }

            .voucher-code {
                font-size: 0.9rem;
                color: #374151;
                font-weight: 600;
                font-family: 'Courier New', monospace;
                background: rgba(245, 158, 11, 0.1);
                padding: 0.2rem 0.5rem;
                border-radius: 6px;
                display: inline-block;
            }

            .voucher-discount {
                font-size: 0.8rem;
                color: #d97706;
                font-weight: 700;
            }

            .voucher-status-badge {
                padding: 0.4rem 0.8rem;
                border-radius: 12px;
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .voucher-status-badge.active {
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                color: white;
                box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
            }

            .voucher-status-badge.inactive {
                background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
                color: white;
                box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
            }

            .voucher-status-badge.expired {
                background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
                color: white;
                box-shadow: 0 2px 8px rgba(107, 114, 128, 0.3);
            }

            /* Enhanced hover effects for voucher card */
            .vouchers-card:hover .voucher-icon {
                transform: scale(1.1);
                box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
            }

            .vouchers-card:hover .status-indicator.active {
                animation-duration: 1s;
            }

            /* Additional voucher metrics */
            .voucher-metrics {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1rem;
                margin-top: 1rem;
                padding-top: 1rem;
                border-top: 1px solid #f3f4f6;
            }

            .voucher-metrics .metric-value {
                font-size: 1.2rem;
                color: #d97706;
            }

            .voucher-metrics .metric-label {
                font-size: 0.75rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            /* ================================
                PRODUCT SPECIFIC STYLES
                ================================ */
            .top-products {
                margin-top: 1.5rem;
            }

            .product-list {
                display: flex;
                flex-direction: column;
                gap: 0.8rem;
            }

            .product-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.8rem;
                background: rgba(99, 102, 241, 0.05);
                border-radius: 12px;
                transition: background 0.2s ease;
            }

            .product-item:hover {
                background: rgba(99, 102, 241, 0.1);
            }

            .product-name {
                font-size: 0.9rem;
                color: #374151;
                font-weight: 500;
            }

            .product-badge {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 0.3rem 0.8rem;
                border-radius: 12px;
                font-size: 0.8rem;
                font-weight: 600;
            }

            /* ================================
                ANALYTICS SECTION
                ================================ */
            .analytics-section {
                display: grid;
                grid-template-columns: 2fr 1fr;
                gap: 1.5rem;
            }

            .chart-card,
            .customers-card {
                background: #ffffff;
                border-radius: 20px;
                padding: 1.8rem;
                border: 1px solid #e5e7eb;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            }

            .chart-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                margin-bottom: 2rem;
            }

            .chart-title h3 {
                font-size: 1.3rem;
                font-weight: 700;
                color: #111827;
                margin-bottom: 0.3rem;
            }

            .chart-title p {
                font-size: 0.9rem;
                color: #6b7280;
                margin: 0;
            }

            .chart-filters {
                display: flex;
                gap: 0.8rem;
            }

            .filter-select {
                padding: 0.6rem 1rem;
                border: 2px solid #e5e7eb;
                border-radius: 12px;
                font-size: 0.9rem;
                background: white;
                color: #374151;
                cursor: pointer;
                transition: border-color 0.2s ease;
            }

            .filter-select:focus {
                outline: none;
                border-color: #667eea;
            }

            .chart-body {
                height: 350px;
                position: relative;
            }

            .empty-chart,
            .empty-customers {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                height: 100%;
                color: #9ca3af;
            }

            .empty-chart i,
            .empty-customers i {
                font-size: 3rem;
                margin-bottom: 1rem;
                opacity: 0.5;
            }

            /* ================================
                CUSTOMERS CARD
                ================================ */
            .customers-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1.5rem;
            }

            .customers-header h3 {
                font-size: 1.3rem;
                font-weight: 700;
                color: #111827;
                margin: 0;
            }

            .online-indicator {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                font-size: 0.9rem;
                color: #16a34a;
                font-weight: 600;
            }

            .pulse-dot {
                width: 8px;
                height: 8px;
                background: #16a34a;
                border-radius: 50%;
                animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            .customer-list {
                display: flex;
                flex-direction: column;
                gap: 1rem;
                max-height: 350px;
                overflow-y: auto;
            }

            .customer-item {
                display: flex;
                align-items: center;
                gap: 1rem;
                padding: 1rem;
                background: rgba(99, 102, 241, 0.02);
                border-radius: 16px;
                transition: all 0.2s ease;
            }

            .customer-item:hover {
                background: rgba(99, 102, 241, 0.05);
                transform: translateX(4px);
            }

            .customer-avatar {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: 700;
                font-size: 0.9rem;
                overflow: hidden;
                position: relative;
            }

            .avatar-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 12px;
            }

            .avatar-initials {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                height: 100%;
            }

            .customer-info {
                flex: 1;
            }

            .customer-name {
                font-size: 0.95rem;
                font-weight: 600;
                color: #111827;
                margin-bottom: 0.2rem;
            }

            .customer-email {
                font-size: 0.8rem;
                color: #6b7280;
                margin: 0;
            }

            .status-time {
                font-size: 0.8rem;
                color: #16a34a;
                font-weight: 500;
            }

            /* ================================
                UTILITY CLASSES
                ================================ */
            .card-link {
                text-decoration: none;
                color: inherit;
            }

            .empty-state {
                margin-top: 1rem;
                text-align: center;
                padding: 1.5rem;
                background: rgba(243, 244, 246, 0.5);
                border-radius: 12px;
                border: 2px dashed #d1d5db;
            }

            .empty-text {
                font-size: 0.9rem;
                color: #9ca3af;
                font-style: italic;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
            }

            .empty-text::before {
                content: '🎫';
                font-style: normal;
                opacity: 0.5;
            }

            /* Loading state */
            .voucher-loading {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem;
                color: #9ca3af;
            }

            .loading-spinner {
                width: 24px;
                height: 24px;
                border: 2px solid #f3f4f6;
                border-top: 2px solid #d97706;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                margin-right: 0.5rem;
            }

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }

            /* ================================
                RESPONSIVE DESIGN
                ================================ */
            @media (max-width: 1024px) {
                .analytics-section {
                    grid-template-columns: 1fr;
                }

                .dashboard-container {
                    padding: 0 1.5rem;
                }
            }

            @media (max-width: 768px) {
                .dashboard-container {
                    padding: 0 1rem;
                }

                .welcome-title {
                    font-size: 2rem;
                }

                .stats-grid {
                    grid-template-columns: 1fr;
                }

                .chart-header {
                    flex-direction: column;
                    gap: 1rem;
                }

                .chart-filters {
                    align-self: stretch;
                }

                .enhanced-metrics {
                    padding: 1rem;
                }

                .enhanced-metrics .metric {
                    padding: 0.5rem 0;
                }

                .metric-icon {
                    width: 35px;
                    height: 35px;
                }

                /* Voucher card mobile adjustments */
                .voucher-item {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 0.8rem;
                }

                .voucher-status-badge {
                    align-self: flex-end;
                }

                .voucher-status {
                    justify-content: center;
                }

                .voucher-metrics {
                    grid-template-columns: 1fr;
                    gap: 0.5rem;
                }
            }

            @media (max-width: 480px) {
                .stat-card {
                    padding: 1.2rem;
                }

                .stat-number {
                    font-size: 2rem;
                }

                .voucher-code {
                    font-size: 0.8rem;
                    padding: 0.1rem 0.4rem;
                }

                .voucher-discount {
                    font-size: 0.75rem;
                }

                .voucher-status-badge {
                    padding: 0.3rem 0.6rem;
                    font-size: 0.7rem;
                }
            }
        </style>
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
