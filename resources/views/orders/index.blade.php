@extends('layouts.index')

@section('title', 'Orders')

@section('content')
    <div class="container-fluid mt-4">
        <div class="order-index-head-row mb-4">
            <h2 class="text-center w-100">Order List</h2>
        </div>

        @if (session('success'))
            <div id="success-alert" class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ route('orders.index') }}" class="mb-4">
            <div class="order-index-head-row">
                <div class="order-index-left-col col-md-4 me-2">
                    <input type="text" name="search" class="order-index-search"
                        placeholder="Search by ID, Date, Status, etc" value="{{ request('search') }}">
                </div>
                <div class="order-index-right-col d-flex gap-2">
                    <button type="submit" class="order-index-btn-filter">Search</button>
                    <a href="{{ route('orders.index') }}" class="order-index-btn-reset">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive mt-4">
            <table class="order-index-table text-center">
                <thead class="order-index-thead-light">
                    <tr>
                        @php
                            $columns = [
                                'id' => 'No',
                                'unique_order_id' => 'Order ID',
                                'order_date' => 'Order Date',
                                'amount' => 'Amount',
                                'payment_status' => 'Payment Status',
                                'order_status' => 'Order Status',
                                'tracking_number' => 'Resi',
                            ];
                        @endphp

                        @foreach ($columns as $col => $label)
                            @php
                                $isSorted = request('sort_column') === $col;
                                $dir = $isSorted ? (request('sort_direction') === 'asc' ? 'desc' : 'asc') : 'desc';
                                $icon = $isSorted
                                    ? 'fas fa-sort-' . (request('sort_direction') === 'asc' ? 'up' : 'down')
                                    : 'fas fa-sort';
                            @endphp
                            <th>
                                <a href="{{ route('orders.index', array_merge(request()->query(), ['sort_column' => $col, 'sort_direction' => $dir])) }}"
                                    class="text-decoration-none text-white">
                                    {{ $label }} <i class="{{ $icon }}"></i>
                                </a>
                            </th>
                        @endforeach

                        <th>Total Orders</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="order-index-row">
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->unique_order_id }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y, H:i') }}</td>
                            <td>{{ number_format($order->amount, 2) }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ match ($order->payment_status->value) {
                                        \App\Enums\PaymentStatusEnum::Completed => 'success',
                                        \App\Enums\PaymentStatusEnum::Pending => 'warning',
                                        \App\Enums\PaymentStatusEnum::Failed => 'danger',
                                        default => 'secondary',
                                    } }}">
                                    {{ $order->payment_status->description }}
                                </span>
                            </td>
                            <td>
                                <span
                                    class="badge bg-{{ match ($order->order_status->value) {
                                        \App\Enums\OrderStatusEnum::Delivered => 'success',
                                        \App\Enums\OrderStatusEnum::Shipped => 'info',
                                        \App\Enums\OrderStatusEnum::Pending => 'warning',
                                        \App\Enums\OrderStatusEnum::Canceled => 'danger',
                                        default => 'secondary',
                                    } }}">
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

                                    <select name="payment_status"
                                        class="form-select form-select-sm mb-1 order-index-select">
                                        @foreach (\App\Enums\PaymentStatusEnum::asSelectArray() as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ $order->payment_status->value === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <select name="order_status"
                                        class="form-select form-select-sm mb-1 order-status-select order-index-select">
                                        @foreach (\App\Enums\OrderStatusEnum::asSelectArray() as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ $order->order_status->value === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <input type="text" name="tracking_number"
                                        class="form-control form-control-sm tracking-number-input mb-1 order-index-search"
                                        placeholder="Tracking Number"
                                        style="display: {{ $order->order_status->value === 'shipped' ? 'block' : 'none' }};"
                                        value="{{ $order->tracking_number }}">

                                    <button type="submit" class="order-index-btn-edit-order">
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
