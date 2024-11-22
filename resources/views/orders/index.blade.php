@extends('layouts.index')

@section('title', 'orders')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Order List</h1>

        <!-- Card for Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Amount</th>
                            <th>Payment Status</th>
                            <th>Order Status</th>
                            <th>Total Orders</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $order->unique_order_id }}</td>
                                <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y, H:i') }}</td>
                                <td>{{ number_format($order->amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->payment_status == 'completed' ? 'success' : ($order->payment_status == 'pending' ? 'warning' : 'danger') }} text-white">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $order->order_status == 'delivered' ? 'success' : ($order->order_status == 'shipped' ? 'info' : ($order->order_status == 'pending' ? 'warning' : 'danger')) }} text-white">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                </td>
                                <td>
                                    <strong>{{ $orderCounts[$order->unique_order_id] ?? 0 }}</strong>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="View Order">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="#" class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Edit Order">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="#" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete Order">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <h5>Total Orders: <span class="text-primary">{{ $orders->count() }}</span></h5>
        </div>
    </div>

    <!-- Tooltip JavaScript -->
    @push('scripts')
        <script>
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        </script>
    @endpush
@endsection
