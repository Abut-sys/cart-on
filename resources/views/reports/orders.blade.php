@extends('layouts.index')

@section('title', 'Sales Report')

@section('dongol')
    <div class="container odr-container py-4">
        <h2 class="mb-4 odr-title text-dark">Sales Report</h2>

        <!-- Report Tabs -->
        <ul class="nav nav-tabs odr-tabs mb-4" id="reportTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary"
                    type="button" role="tab" data-report-type="summary">
                    <i class="fas fa-chart-line me-2"></i> Sales Summary
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="transaction-tab" data-bs-toggle="tab" data-bs-target="#transaction"
                    type="button" role="tab" data-report-type="transactions">
                    <i class="fas fa-receipt me-2"></i> Transaction History
                </button>
            </li>
        </ul>

        <!-- Loading Indicator -->
        <div id="loading-indicator" class="text-center py-5" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading data...</p>
        </div>

        <div class="tab-content" id="reportTabsContent">
            <!-- Transaction History Tab -->
            <div class="tab-pane fade" id="transaction" role="tabpanel">
                <!-- Filter Form -->
                <form id="transaction-filter-form" class="mb-4 d-flex flex-wrap gap-3 align-items-end odr-filter-form p-4 bg-light rounded shadow-sm">
                    <div class="odr-form-group flex-grow-1">
                        <label for="unique_order_id" class="form-label text-muted small">Order ID</label>
                        <input type="text" name="unique_order_id" id="unique_order_id"
                            class="form-control odr-form-control shadow-none border" placeholder="e.g. ORD1234">
                    </div>
                    <div class="odr-form-group flex-grow-1">
                        <label for="status" class="form-label text-muted small">Order Status</label>
                        <select name="status" id="status" class="form-control odr-form-control shadow-none border">
                            <option value="">All</option>
                            <option value="pending">Pending</option>
                            <option value="packaged">Packaged</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="canceled">Canceled</option>
                        </select>
                    </div>
                    <div class="odr-form-group flex-grow-1">
                        <label for="tracking_number" class="form-label text-muted small">Tracking Number</label>
                        <input type="text" name="tracking_number" id="tracking_number"
                            class="form-control odr-form-control shadow-none border" placeholder="No Resi">
                    </div>
                    <div class="odr-form-group">
                        <label for="start_date" class="form-label text-muted small">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control odr-form-control shadow-none border">
                    </div>
                    <div class="odr-form-group">
                        <label for="end_date" class="form-label text-muted small">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control odr-form-control shadow-none border">
                    </div>
                    <div class="odr-form-group">
                        <button type="submit" class="btn btn-primary odr-btn-primary px-4">
                            <i class="fas fa-filter me-2"></i> Filter
                        </button>
                    </div>
                </form>

                <!-- Transaction Table Container -->
                <div id="transaction-table-container">
                    <!-- Table will be loaded here via AJAX -->
                </div>
            </div>

            <!-- Sales Summary Tab -->
            <div class="tab-pane fade show active" id="summary" role="tabpanel">
                <!-- Summary Filter Form -->
                <form id="summary-filter-form" class="mb-4 d-flex flex-wrap gap-3 align-items-end odr-filter-form p-4 bg-light rounded shadow-sm">
                    <div class="odr-form-group">
                        <label for="summary_start_date" class="form-label text-muted small">Start Date</label>
                        <input type="date" name="start_date" id="summary_start_date" class="form-control odr-form-control shadow-none border">
                    </div>
                    <div class="odr-form-group">
                        <label for="summary_end_date" class="form-label text-muted small">End Date</label>
                        <input type="date" name="end_date" id="summary_end_date" class="form-control odr-form-control shadow-none border">
                    </div>
                    <div class="odr-form-group">
                        <button type="submit" class="btn btn-primary odr-btn-primary px-4">
                            <i class="fas fa-filter me-2"></i> Filter
                        </button>
                    </div>
                </form>

                <!-- Summary Content Container -->
                <div id="summary-content-container">
                    <!-- Summary content will be loaded here via AJAX -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modals Container -->
    <div id="modals-container">
        <!-- Receipt and Invoice modals will be loaded here -->
    </div>

    <script>
        class SalesReportManager {
            constructor() {
                this.currentTab = 'summary';
                this.init();
            }

            init() {
                this.setupEventListeners();
                this.loadInitialData();
            }

            setupEventListeners() {
                // Tab switching
                document.querySelectorAll('[data-report-type]').forEach(tab => {
                    tab.addEventListener('click', (e) => {
                        const reportType = e.target.dataset.reportType;
                        this.switchTab(reportType);
                    });
                });

                // Form submissions
                document.getElementById('transaction-filter-form').addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.loadTransactionData();
                });

                document.getElementById('summary-filter-form').addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.loadSummaryData();
                });
            }

            switchTab(reportType) {
                this.currentTab = reportType;

                // Update URL without redirect
                const url = new URL(window.location);
                url.searchParams.set('report_type', reportType);
                window.history.pushState({}, '', url);

                // Load data for the selected tab
                if (reportType === 'transactions') {
                    this.loadTransactionData();
                } else if (reportType === 'summary') {
                    this.loadSummaryData();
                }
            }

            showLoading() {
                document.getElementById('loading-indicator').style.display = 'block';
            }

            hideLoading() {
                document.getElementById('loading-indicator').style.display = 'none';
            }

            async loadInitialData() {
                // Determine initial tab from URL
                const urlParams = new URLSearchParams(window.location.search);
                const reportType = urlParams.get('report_type') || 'summary';

                // Set active tab
                document.querySelectorAll('[data-report-type]').forEach(tab => {
                    tab.classList.remove('active');
                });
                document.querySelector(`[data-report-type="${reportType}"]`).classList.add('active');

                // Show correct tab pane
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.remove('show', 'active');
                });
                document.getElementById(reportType === 'transactions' ? 'transaction' : 'summary').classList.add('show', 'active');

                this.currentTab = reportType;

                // Load initial data
                if (reportType === 'transactions') {
                    await this.loadTransactionData();
                } else {
                    await this.loadSummaryData();
                }
            }

            async loadTransactionData() {
                this.showLoading();

                try {
                    const formData = new FormData(document.getElementById('transaction-filter-form'));
                    formData.append('report_type', 'transactions');

                    const params = new URLSearchParams(formData);

                    const response = await fetch(`{{ route('report.orders') }}?${params}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const data = await response.json();
                    this.renderTransactionTable(data.transactions);

                } catch (error) {
                    console.error('Error loading transaction data:', error);
                    this.showError('Failed to load transaction data');
                } finally {
                    this.hideLoading();
                }
            }

            async loadSummaryData() {
                this.showLoading();

                try {
                    const formData = new FormData(document.getElementById('summary-filter-form'));
                    formData.append('report_type', 'summary');

                    const params = new URLSearchParams(formData);

                    const response = await fetch(`{{ route('report.orders') }}?${params}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const data = await response.json();
                    this.renderSummaryContent(data.summary);

                } catch (error) {
                    console.error('Error loading summary data:', error);
                    this.showError('Failed to load summary data');
                } finally {
                    this.hideLoading();
                }
            }

            renderTransactionTable(transactions) {
                const container = document.getElementById('transaction-table-container');

                if (!transactions || transactions.length === 0) {
                    container.innerHTML = `
                        <div class="table-responsive rounded shadow-sm">
                            <table class="table table-hover odr-table mb-0">
                                <thead class="table-light odr-table-head">
                                    <tr>
                                        <th class="border-top-0">#</th>
                                        <th class="border-top-0">Order ID</th>
                                        <th class="border-top-0">Customer</th>
                                        <th class="border-top-0">Products</th>
                                        <th class="border-top-0">Date</th>
                                        <th class="border-top-0">Courier</th>
                                        <th class="border-top-0">No.resi</th>
                                        <th class="border-top-0">Address</th>
                                        <th class="border-top-0">Status</th>
                                        <th class="border-top-0 text-end">Total</th>
                                        <th class="border-top-0 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="11" class="text-center odr-no-orders py-5">
                                            <div class="py-5">
                                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                                <p class="h5 text-muted">No transactions found</p>
                                                <p class="text-muted small">Try adjusting your search filters</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    `;
                    return;
                }

                let tableHTML = `
                    <div class="table-responsive rounded shadow-sm">
                        <table class="table table-hover odr-table mb-0">
                            <thead class="table-light odr-table-head">
                                <tr>
                                    <th class="border-top-0">#</th>
                                    <th class="border-top-0">Order ID</th>
                                    <th class="border-top-0">Customer</th>
                                    <th class="border-top-0">Products</th>
                                    <th class="border-top-0">Date</th>
                                    <th class="border-top-0">Courier</th>
                                    <th class="border-top-0">No.resi</th>
                                    <th class="border-top-0">Address</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0 text-end">Total</th>
                                    <th class="border-top-0 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                transactions.forEach((transaction, index) => {
                    const statusClasses = {
                        'pending': 'bg-warning text-dark',
                        'packaged': 'bg-info text-white',
                        'shipped': 'bg-primary text-white',
                        'delivered': 'bg-success text-white',
                        'canceled': 'bg-danger text-white'
                    };

                    const statusClass = statusClasses[transaction.order_status] || 'bg-secondary';
                    const orderDate = new Date(transaction.order_date).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });

                    tableHTML += `
                        <tr class="align-middle">
                            <td class="text-muted">${index + 1}</td>
                            <td>
                                <span class="badge bg-light text-dark border">${transaction.unique_order_id}</span>
                            </td>
                            <td>${transaction.customer_name}</td>
                            <td class="small">
                                <span class="badge bg-secondary text-white">${transaction.total_quantity} items</span>
                                <div class="text-muted mt-1" title="${transaction.product_names}">
                                    ${transaction.product_names.length > 30 ? transaction.product_names.substring(0, 30) + '...' : transaction.product_names}
                                </div>
                            </td>
                            <td class="text-nowrap">${orderDate}</td>
                            <td class="text-nowrap">${transaction.courier} - ${transaction.shipping_service}</td>
                            <td>
                                ${transaction.tracking_number ?
                                    `<span class="badge bg-light text-dark border">${transaction.tracking_number}</span>` :
                                    '<span class="text-muted">-</span>'
                                }
                            </td>
                            <td class="small">${transaction.shipping_address.length > 20 ? transaction.shipping_address.substring(0, 20) + '...' : transaction.shipping_address}</td>
                            <td>
                                <span class="badge ${statusClass}">
                                    ${transaction.order_status.charAt(0).toUpperCase() + transaction.order_status.slice(1)}
                                </span>
                            </td>
                            <td class="text-end fw-bold">Rp ${new Intl.NumberFormat('id-ID').format(transaction.order_total)}</td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn btn-sm odr-btn-info text-white" onclick="showReceipt(${transaction.order_id})" title="Receipt">
                                        <i class="fas fa-receipt"></i>
                                    </button>
                                    <button class="btn btn-sm odr-btn-secondary" onclick="showInvoice(${transaction.order_id})" title="Invoice">
                                        <i class="fas fa-file-invoice"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });

                tableHTML += `
                            </tbody>
                        </table>
                    </div>
                `;

                container.innerHTML = tableHTML;
            }

            renderSummaryContent(summary) {
                const container = document.getElementById('summary-content-container');

                container.innerHTML = `
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card odr-card odr-card-primary h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-muted mb-2">Total Revenue</h6>
                                            <h3 class="mb-0">Rp ${new Intl.NumberFormat('id-ID').format(summary.total_revenue || 0)}</h3>
                                        </div>
                                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                                            <i class="fas fa-wallet text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <span class="badge bg-${(summary.revenue_change || 0) >= 0 ? 'success' : 'danger'}">
                                            ${(summary.revenue_change || 0) >= 0 ? '+' : ''}${(summary.revenue_change || 0).toFixed(2)}%
                                        </span>
                                        <span class="text-muted small ms-2">vs previous period</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card odr-card odr-card-success h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-muted mb-2">Total Transactions</h6>
                                            <h3 class="mb-0">${summary.total_transactions || 0}</h3>
                                        </div>
                                        <div class="bg-success bg-opacity-10 p-3 rounded">
                                            <i class="fas fa-shopping-cart text-success"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <span class="badge bg-${(summary.transactions_change || 0) >= 0 ? 'success' : 'danger'}">
                                            ${(summary.transactions_change || 0) >= 0 ? '+' : ''}${(summary.transactions_change || 0).toFixed(2)}%
                                        </span>
                                        <span class="text-muted small ms-2">vs previous period</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card odr-card odr-card-info h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-muted mb-2">Avg. Order Value</h6>
                                            <h3 class="mb-0">Rp ${new Intl.NumberFormat('id-ID').format(summary.average_order_value || 0)}</h3>
                                        </div>
                                        <div class="bg-info bg-opacity-10 p-3 rounded">
                                            <i class="fas fa-tag text-info"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <span class="badge bg-${(summary.aov_change || 0) >= 0 ? 'success' : 'danger'}">
                                            ${(summary.aov_change || 0) >= 0 ? '+' : ''}${(summary.aov_change || 0).toFixed(2)}%
                                        </span>
                                        <span class="text-muted small ms-2">vs previous period</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card odr-card odr-card-warning h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-muted mb-2">Products Sold</h6>
                                            <h3 class="mb-0">${summary.total_products_sold || 0}</h3>
                                        </div>
                                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                                            <i class="fas fa-box text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <span class="badge bg-${(summary.products_change || 0) >= 0 ? 'success' : 'danger'}">
                                            ${(summary.products_change || 0) >= 0 ? '+' : ''}${(summary.products_change || 0).toFixed(2)}%
                                        </span>
                                        <span class="text-muted small ms-2">vs previous period</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Tables -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Sales by Status</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Status</th>
                                                    <th class="text-end">Count</th>
                                                    <th class="text-end">Revenue</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${this.renderStatusBreakdown(summary.status_breakdown)}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Top Selling Categories</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Category</th>
                                                    <th class="text-end">Products Sold</th>
                                                    <th class="text-end">Revenue</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${this.renderCategoryBreakdown(summary.category_breakdown)}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            renderStatusBreakdown(statusBreakdown) {
                if (!statusBreakdown || statusBreakdown.length === 0) {
                    return '<tr><td colspan="3" class="text-center text-muted">No data available</td></tr>';
                }

                const statusClasses = {
                    'pending': 'bg-warning text-dark',
                    'packaged': 'bg-info text-white',
                    'shipped': 'bg-primary text-white',
                    'delivered': 'bg-success text-white',
                    'canceled': 'bg-danger text-white'
                };

                return statusBreakdown.map(status => {
                    const statusClass = statusClasses[status.order_status] || 'bg-secondary';
                    return `
                        <tr>
                            <td>
                                <span class="badge ${statusClass}">
                                    ${status.order_status.charAt(0).toUpperCase() + status.order_status.slice(1)}
                                </span>
                            </td>
                            <td class="text-end">${status.count}</td>
                            <td class="text-end">Rp ${new Intl.NumberFormat('id-ID').format(status.total)}</td>
                        </tr>
                    `;
                }).join('');
            }

            renderCategoryBreakdown(categoryBreakdown) {
                if (!categoryBreakdown || categoryBreakdown.length === 0) {
                    return '<tr><td colspan="3" class="text-center text-muted">No data available</td></tr>';
                }

                return categoryBreakdown.map(category => `
                    <tr>
                        <td>${category.subCategory_name || 'Uncategorized'}</td>
                        <td class="text-end">${category.total_sold || 0}</td>
                        <td class="text-end">Rp ${new Intl.NumberFormat('id-ID').format(category.total_revenue || 0)}</td>
                    </tr>
                `).join('');
            }

            showError(message) {
                const errorHTML = `
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        ${message}
                    </div>
                `;

                if (this.currentTab === 'transactions') {
                    document.getElementById('transaction-table-container').innerHTML = errorHTML;
                } else {
                    document.getElementById('summary-content-container').innerHTML = errorHTML;
                }
            }
        }

        // Global functions for modal actions
        function showReceipt(orderId) {
            // Implement receipt modal functionality
            console.log('Show receipt for order:', orderId);
        }

        function showInvoice(orderId) {
            // Implement invoice modal functionality
            console.log('Show invoice for order:', orderId);
        }

        function printSection(sectionId) {
            var printContents = document.getElementById(sectionId).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload();
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            new SalesReportManager();
        });
    </script>
@endsection
