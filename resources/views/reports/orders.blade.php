@extends('layouts.index')

@section('title', 'Sales Report')

@section('dongol')
    <div class="container odr-container py-4">
        <h2 class="mb-4 odr-title text-dark">Sales Report</h2>

        <!-- Report Tabs -->
        <ul class="nav nav-tabs odr-tabs mb-4" id="reportTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link {{ request('report_type') === 'summary' || request('report_type') === null ? 'active' : '' }}"
                    id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary" type="button" role="tab">
                    <i class="fas fa-chart-line me-2"></i> Sales Summary
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ request('report_type') === 'transactions' ? 'active' : '' }}"
                    id="transaction-tab" data-bs-toggle="tab" data-bs-target="#transaction" type="button" role="tab">
                    <i class="fas fa-receipt me-2"></i> Transaction History
                </button>
            </li>
        </ul>

        <div class="tab-content" id="reportTabsContent">
            <!-- Transaction History Tab -->
            <div class="tab-pane fade {{ request('report_type') === 'transactions' ? 'show active' : '' }}" id="transaction" role="tabpanel">
                <!-- Filter Form -->
                <form method="GET"
                    class="mb-4 d-flex flex-wrap gap-3 align-items-end odr-filter-form p-4 bg-light rounded shadow-sm">
                    <input type="hidden" name="report_type" value="transactions">
                    <div class="odr-form-group flex-grow-1">
                        <label for="unique_order_id" class="form-label text-muted small">Order ID</label>
                        <input type="text" name="unique_order_id" id="unique_order_id"
                            class="form-control odr-form-control shadow-none border"
                            value="{{ request('unique_order_id') }}" placeholder="e.g. ORD1234">
                    </div>
                    <div class="odr-form-group flex-grow-1">
                        <label for="status" class="form-label text-muted small">Order Status</label>
                        <select name="status" id="status" class="form-control odr-form-control shadow-none border">
                            <option value="">All</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="packaged" {{ request('status') == 'packaged' ? 'selected' : '' }}>Packaged
                            </option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered
                            </option>
                            <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled
                            </option>
                        </select>
                    </div>
                    <div class="odr-form-group flex-grow-1">
                        <label for="tracking_number" class="form-label text-muted small">Tracking Number</label>
                        <input type="text" name="tracking_number" id="tracking_number"
                            class="form-control odr-form-control shadow-none border"
                            value="{{ request('tracking_number') }}" placeholder="No Resi">
                    </div>
                    <div class="odr-form-group">
                        <label for="start_date" class="form-label text-muted small">Start Date</label>
                        <input type="date" name="start_date" class="form-control odr-form-control shadow-none border"
                            value="{{ request('start_date') }}">
                    </div>
                    <div class="odr-form-group">
                        <label for="end_date" class="form-label text-muted small">End Date</label>
                        <input type="date" name="end_date" class="form-control odr-form-control shadow-none border"
                            value="{{ request('end_date') }}">
                    </div>
                    <div class="odr-form-group">
                        <button type="submit" class="btn btn-primary odr-btn-primary px-4">
                            <i class="fas fa-filter me-2"></i> Filter
                        </button>
                    </div>
                </form>

                <!-- Report Table -->
                <div class="table-responsive rounded shadow-sm">
                    <table class="table table-hover odr-table mb-0">
                        <thead class="table-light odr-table-head">
                            <tr>
                                <th class="border-top-0">#</th>
                                <th class="border-top-0">Order ID</th>
                                <th class="border-top-0">Customer</th>
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
                            @forelse ($transactions as $index => $report)
                                <tr class="align-middle">
                                    <td class="text-muted">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $report->unique_order_id }}</span>
                                        <span class="badge bg-light text-dark border">{{ $report->unique_order_id }}</span>
                                    </td>
                                    <td>{{ $report->customer_name }}</td>
                                    <td class="text-nowrap">
                                        {{ \Carbon\Carbon::parse($report->order_date)->format('d M Y') }}</td>
                                    <td class="text-nowrap">{{ $report->courier }} - {{ $report->shipping_service }}</td>
                                    <td>
                                        @if ($report->tracking_number)
                                            <span
                                                class="badge bg-light text-dark border">{{ $report->tracking_number }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <!-- Updated address field -->
                                    <td class="small">{{ Str::limit($report->shipping_address, 20) }}</td>
                                    <td>
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-warning text-dark',
                                                'packaged' => 'bg-info text-white',
                                                'shipped' => 'bg-primary text-white',
                                                'delivered' => 'bg-success text-white',
                                                'canceled' => 'bg-danger text-white',
                                            ];
                                        @endphp
                                        <span class="badge {{ $statusClasses[$report->order_status] ?? 'bg-secondary' }}">
                                            {{ ucfirst($report->order_status) }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold">Rp {{ number_format($report->order_total, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button class="btn btn-sm odr-btn-info text-white" data-bs-toggle="modal"
                                                data-bs-target="#receiptModal{{ $report->order_id }}" title="Receipt">
                                                <i class="fas fa-receipt"></i>
                                            </button>
                                            <button class="btn btn-sm odr-btn-secondary" data-bs-toggle="modal"
                                                data-bs-target="#invoiceModal{{ $report->order_id }}" title="Invoice">
                                                <i class="fas fa-file-invoice"></i>
                                            </button>
                                        </div>
                                        <!-- Modal: Struk -->
                                        <div class="modal fade odr-modal" id="receiptModal{{ $report->order_id }}"
                                            tabindex="-1" aria-labelledby="receiptModalLabel{{ $report->order_id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-md odr-modal-dialog">
                                                <div class="modal-content odr-modal-content border-0 shadow-lg">
                                                    <div class="modal-header odr-modal-header bg-light">
                                                        <h5 class="modal-title">Order Receipt
                                                            #{{ $report->unique_order_id }}</h5>
                                                        <button type="button" class="btn-close odr-btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-4"
                                                        id="receipt-content-{{ $report->order_id }}">
                                                        <div class="mb-4">
                                                            <h6 class="text-muted mb-3">ORDER DETAILS</h6>
                                                            <div class="d-flex justify-content-between mb-2">
                                                                <span class="text-muted">Order ID</span>
                                                                <span>{{ $report->unique_order_id }}</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between mb-2">
                                                                <span class="text-muted">Date</span>
                                                                <span>{{ \Carbon\Carbon::parse($report->order_date)->format('d M Y H:i') }}</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between mb-2">
                                                                <span class="text-muted">Status</span>
                                                                <span
                                                                    class="badge {{ $statusClasses[$report->order_status] ?? 'bg-secondary' }}">
                                                                    {{ ucfirst($report->order_status) }}
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="mb-4">
                                                            <h6 class="text-muted mb-3">CUSTOMER</h6>
                                                            <p class="mb-1">{{ $report->customer_name }}</p>
                                                            <!-- Updated address field -->
                                                            <p class="text-muted small">{{ $report->shipping_address }}
                                                            </p>
                                                        </div>

                                                        <div class="mb-4">
                                                            <h6 class="text-muted mb-3">SHIPPING</h6>
                                                            <div class="d-flex justify-content-between mb-2">
                                                                <span class="text-muted">Courier</span>
                                                                <span>{{ $report->courier }} -
                                                                    {{ $report->shipping_service }}</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between">
                                                                <span class="text-muted">Tracking No.</span>
                                                                <span>{{ $report->tracking_number ?? '-' }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="border-top pt-3">
                                                            <div class="d-flex justify-content-between fw-bold">
                                                                <span>TOTAL</span>
                                                                <span>Rp
                                                                    {{ number_format($report->order_total, 0, ',', '.') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer odr-modal-footer bg-light">
                                                        <button type="button"
                                                            class="btn btn-outline-secondary odr-btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button class="btn btn-primary odr-btn-primary"
                                                            onclick="printSection('receipt-content-{{ $report->order_id }}')">
                                                            <i class="fas fa-print me-2"></i> Print
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal: Invoice -->
                                        <div class="modal fade odr-modal" id="invoiceModal{{ $report->order_id }}"
                                            tabindex="-1" aria-labelledby="invoiceModalLabel{{ $report->order_id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-md odr-modal-dialog">
                                                <div class="modal-content odr-modal-content border-0 shadow-lg">
                                                    <div class="modal-header odr-modal-header bg-light">
                                                        <h5 class="modal-title">Invoice #{{ $report->unique_order_id }}
                                                        </h5>
                                                        <button type="button" class="btn-close odr-btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-4"
                                                        id="invoice-content-{{ $report->order_id }}">
                                                        <div class="text-center mb-4">
                                                            <h5>INVOICE</h5>
                                                            <p class="text-muted small">#{{ $report->unique_order_id }}
                                                            </p>
                                                        </div>

                                                        <div class="mb-4">
                                                            <h6 class="text-muted mb-3">BILL TO</h6>
                                                            <p class="mb-1">{{ $report->customer_name }}</p>
                                                            <!-- Updated address field -->
                                                            <p class="text-muted small">{{ $report->shipping_address }}
                                                            </p>
                                                        </div>

                                                        <div class="mb-4">
                                                            <h6 class="text-muted mb-3">ORDER DETAILS</h6>
                                                            <div class="d-flex justify-content-between mb-2">
                                                                <span class="text-muted">Date</span>
                                                                <span>{{ \Carbon\Carbon::parse($report->order_date)->format('d M Y H:i') }}</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between mb-2">
                                                                <span class="text-muted">Payment Status</span>
                                                                <span
                                                                    class="badge bg-{{ $report->payment_status == 'completed' ? 'success' : 'warning' }}">
                                                                    {{ ucfirst($report->payment_status ?? 'pending') }}
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="border-top pt-3">
                                                            <div class="d-flex justify-content-between fw-bold fs-5">
                                                                <span>TOTAL AMOUNT</span>
                                                                <span>Rp
                                                                    {{ number_format($report->order_total, 0, ',', '.') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer odr-modal-footer bg-light">
                                                        <button type="button"
                                                            class="btn btn-outline-secondary odr-btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button class="btn btn-primary odr-btn-primary"
                                                            onclick="printSection('invoice-content-{{ $report->order_id }}')">
                                                            <i class="fas fa-print me-2"></i> Print
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center odr-no-orders py-5">
                                        <div class="py-5">
                                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                            <p class="h5 text-muted">No transactions found</p>
                                            <p class="text-muted small">Try adjusting your search filters</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Sales Summary Tab -->
            <div class="tab-pane fade {{ request('report_type') == 'summary' || request('report_type') === null ? 'show active' : '' }}"
                id="summary" role="tabpanel">
            <div class="tab-pane fade {{ request('report_type') == 'summary' || request('report_type') === null ? 'show active' : '' }}"
                id="summary" role="tabpanel">
                <form method="GET"
                    class="mb-4 d-flex flex-wrap gap-3 align-items-end odr-filter-form p-4 bg-light rounded shadow-sm">
                    <input type="hidden" name="report_type" value="summary">
                    <div class="odr-form-group">
                        <label for="start_date" class="form-label text-muted small">Start Date</label>
                        <input type="date" name="start_date" class="form-control odr-form-control shadow-none border"
                            value="{{ request('start_date') }}">
                    </div>
                    <div class="odr-form-group">
                        <label for="end_date" class="form-label text-muted small">End Date</label>
                        <input type="date" name="end_date" class="form-control odr-form-control shadow-none border"
                            value="{{ request('end_date') }}">
                    </div>
                    <div class="odr-form-group">
                        <button type="submit" class="btn btn-primary odr-btn-primary px-4">
                            <i class="fas fa-filter me-2"></i> Filter
                        </button>
                    </div>
                </form>

                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card odr-card odr-card-primary h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="text-muted mb-2">Total Revenue</h6>
                                        <h3 class="mb-0">Rp
                                            {{ number_format($summary['total_revenue'] ?? 0, 0, ',', '.') }}
                                        </h3>
                                            {{ number_format($summary['total_revenue'] ?? 0, 0, ',', '.') }}
                                        </h3>
                                    </div>
                                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                                        <i class="fas fa-wallet text-primary"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <span
                                        class="badge bg-{{ ($summary['revenue_change'] ?? 0) >= 0 ? 'success' : 'danger' }}">
                                        {{ ($summary['revenue_change'] ?? 0) >= 0 ? '+' : '' }}
                                        {{ number_format($summary['revenue_change'] ?? 0, 2) }}%
                                        {{ ($summary['revenue_change'] ?? 0) >= 0 ? '+' : '' }}
                                        {{ number_format($summary['revenue_change'] ?? 0, 2) }}%
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
                                        <h3 class="mb-0">{{ $summary['total_transactions'] ?? 0 }}</h3>
                                    </div>
                                    <div class="bg-success bg-opacity-10 p-3 rounded">
                                        <i class="fas fa-shopping-cart text-success"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <span
                                        class="badge bg-{{ ($summary['transactions_change'] ?? 0) >= 0 ? 'success' : 'danger' }}">
                                        {{ ($summary['transactions_change'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($summary['transactions_change'] ?? 0, 2) }}%
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
                                        <h3 class="mb-0">Rp
                                            {{ number_format($summary['average_order_value'] ?? 0, 0, ',', '.') }}</h3>
                                    </div>
                                    <div class="bg-info bg-opacity-10 p-3 rounded">
                                        <i class="fas fa-tag text-info"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <span
                                        class="badge bg-{{ ($summary['aov_change'] ?? 0) >= 0 ? 'success' : 'danger' }}">
                                        {{ ($summary['aov_change'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($summary['aov_change'] ?? 0, 2) }}%
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
                                        <h3 class="mb-0">{{ $summary['total_products_sold'] ?? 0 }}</h3>
                                    </div>
                                    <div class="bg-warning bg-opacity-10 p-3 rounded">
                                        <i class="fas fa-box text-warning"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <span
                                        class="badge bg-{{ ($summary['products_change'] ?? 0) >= 0 ? 'success' : 'danger' }}">
                                        {{ ($summary['products_change'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($summary['products_change'] ?? 0, 2) }}%
                                    </span>
                                    <span class="text-muted small ms-2">vs previous period</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
                                            @foreach ($summary['status_breakdown'] ?? [] as $status)
                                                <tr>
                                                    <td>
                                                        <span
                                                            class="badge {{ $statusClasses[$status->order_status] ?? 'bg-secondary' }}">
                                                            {{ ucfirst($status->order_status) }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">{{ $status->count }}</td>
                                                    <td class="text-end">Rp
                                                        {{ number_format($status->total, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
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
                                            @foreach ($summary['category_breakdown'] ?? [] as $category)
                                                <tr>
                                                    <td>{{ $category->subCategory_name ?? 'Uncategorized' }}</td>
                                                    <td>{{ $category->subCategory_name ?? 'Uncategorized' }}</td>
                                                    <td class="text-end">{{ $category->total_sold }}</td>
                                                    <td class="text-end">Rp
                                                        {{ number_format($category->total_revenue ?? 0, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const summaryTab = document.getElementById('summary-tab');
        const transactionTab = document.getElementById('transaction-tab');
        const form = document.querySelector('form.odr-filter-form');
        const reportTypeInput = form.querySelector('input[name="report_type"]');

        summaryTab.addEventListener('click', function () {
            reportTypeInput.value = 'summary';
            form.submit();
        });

        transactionTab.addEventListener('click', function () {
            reportTypeInput.value = 'transactions';
            form.submit();
        });
    });
</script>


