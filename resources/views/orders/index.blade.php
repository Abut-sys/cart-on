@extends('layouts.index')

@section('title', 'Orders')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4 position-relative">
            <h2 class="text-center w-100 fw-bold">Order List</h2>
        </div>

        @if (session('success'))
            <div id="success-alert" class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="order-index-form">
            <form method="GET" action="{{ route('orders.index') }}" class="mb-4">
                <div class="order-index-head-row d-flex justify-content-between align-items-center">
                    <div class="order-index-left-col d-flex">
                        <div class="order-index-col-md-4 me-2">
                            <input type="text" name="search" class="form-control order-index-search pe-5"
                                placeholder="Search by ID or Status" value="{{ request('search') }}">
                        </div>
                        <div class="order-index-col-md-2 me-2">
                            <div class="d-flex align-items-center position-relative">
                                <select name="sort_id" class="form-select order-index-select pe-3">
                                    <option value disabled selected>Sort ID</option>
                                    <option value="asc" {{ request('sort_id') == 'asc' ? 'selected' : '' }}>ASC</option>
                                    <option value="desc" {{ request('sort_id') == 'desc' ? 'selected' : '' }}>DESC
                                    </option>
                                </select>
                                <i class="fas fa-sort-down position-absolute end-0 me-2 order-index-sort-icon"></i>
                            </div>
                        </div>
                        <div class="order-index-col-md-2">
                            <div class="d-flex align-items-center position-relative">
                                <select name="sort_date" class="form-select order-index-select pe-5">
                                    <option value disabled selected>Sort Date</option>
                                    <option value="asc" {{ request('sort_date') == 'asc' ? 'selected' : '' }}>Oldest
                                    </option>
                                    <option value="desc" {{ request('sort_date') == 'desc' ? 'selected' : '' }}>Newest
                                    </option>
                                </select>
                                <i class="fas fa-sort-numeric-down position-absolute end-0 me-2 order-index-sort-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="order-index-right-col">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn order-index-btn-filter">Search</button>
                            <a href="{{ route('orders.index') }}" class="btn order-index-btn-reset ms-2">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive mt-4">
            <table class="table order-index-table">
                <thead class="order-index-thead-light">
                    <tr>
                        <th>No</th>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th>Resi</th>
                        <th>Total Orders</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="order-index-row">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $order->unique_order_id }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y, H:i') }}</td>
                            <td>{{ number_format($order->amount, 2) }}</td>
                            <td>
                                @php
                                    $paymentColor = match ($order->payment_status->value) {
                                        \App\Enums\PaymentStatusEnum::Completed => 'success',
                                        \App\Enums\PaymentStatusEnum::Pending => 'warning',
                                        \App\Enums\PaymentStatusEnum::Failed => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $paymentColor }}">
                                    {{ $order->payment_status->description }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $orderColor = match ($order->order_status->value) {
                                        \App\Enums\OrderStatusEnum::Delivered => 'success',
                                        \App\Enums\OrderStatusEnum::Shipped => 'info',
                                        \App\Enums\OrderStatusEnum::Pending => 'warning',
                                        \App\Enums\OrderStatusEnum::Canceled => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $orderColor }}">
                                    {{ $order->order_status->description }}
                                </span>
                            </td>
                            <td>
                                @if ($order->tracking_number)
                                    <span class="badge bg-secondary">{{ $order->tracking_number }}</span>
                                @else
                                    <span class="badge bg-light text-dark">No Tracking</span>
                                @endif
                            </td>
                            <td><strong>{{ $orderCounts[$order->unique_order_id] ?? 0 }}</strong></td>
                            <td>
                                <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST"
                                    class="d-flex flex-column gap-1 order-form">
                                    @csrf
                                    @method('PUT')

                                    <select name="payment_status" class="form-select form-select-sm mb-1">
                                        @foreach (\App\Enums\PaymentStatusEnum::asSelectArray() as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ $order->payment_status->value === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <select name="order_status" class="form-select form-select-sm mb-1 order-status-select">
                                        @foreach (\App\Enums\OrderStatusEnum::asSelectArray() as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ $order->order_status->value === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <input type="text" name="tracking_number"
                                        class="form-control form-control-sm tracking-number-input mb-1"
                                        placeholder="Tracking Number"
                                        style="display: {{ $order->order_status->value === 'shipped' ? 'block' : 'none' }};"
                                        value="{{ $order->tracking_number }}">

                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <nav>
                {{ $orders->withQueryString()->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    </div>

    <script>
        document.querySelectorAll('.order-form').forEach(form => {
            const statusSelect = form.querySelector('.order-status-select');
            const trackingInput = form.querySelector('.tracking-number-input');

            const toggleTrackingInput = () => {
                if (statusSelect.value === 'shipped') {
                    trackingInput.style.display = 'block';
                } else {
                    trackingInput.style.display = 'none';
                    trackingInput.value = '';
                }
            };

            statusSelect.addEventListener('change', toggleTrackingInput);
            toggleTrackingInput();
        });

        setTimeout(() => {
            const alert = document.getElementById('success-alert');
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 10000);
    </script>
@endsection
