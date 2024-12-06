@extends('layouts.index')

@section('title', 'Orders')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4 position-relative">
            <h2 class="text-center w-100 fw-bold">Order List</h2>
        </div>

        <div class="order-index-form">
            <form method="GET" action="{{ route('orders.index') }}" class="mb-4">
                <div class="order-index-head-row d-flex justify-content-between align-items-center">
                    <div class="order-index-left-col d-flex">
                        <div class="order-index-col-md-4 me-2">
                            <input type="text" name="search" class="form-control order-index-search pe-5" placeholder="Search by ID or Status" value="{{ request('search') }}">
                        </div>
                        <div class="order-index-col-md-2 me-2">
                            <div class="d-flex align-items-center position-relative">
                                <select name="sort_id" class="form-select order-index-select pe-3">
                                    <option value="">Sort ID</option>
                                    <option value="asc" {{ request('sort_id') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                    <option value="desc" {{ request('sort_id') == 'desc' ? 'selected' : '' }}>Descending</option>
                                </select>
                                <i class="fas fa-sort-down position-absolute end-0 me-2 order-index-sort-icon"></i>
                            </div>
                        </div>
                        <div class="order-index-col-md-2">
                            <div class="d-flex align-items-center position-relative">
                                <select name="sort_date" class="form-select order-index-select pe-5">
                                    <option value="">Sort Date</option>
                                    <option value="asc" {{ request('sort_date') == 'asc' ? 'selected' : '' }}>Oldest</option>
                                    <option value="desc" {{ request('sort_date') == 'desc' ? 'selected' : '' }}>Newest</option>
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
                                <span class="badge bg-{{ $order->payment_status == 'completed' ? 'success' : ($order->payment_status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $order->order_status == 'delivered' ? 'success' : ($order->order_status == 'shipped' ? 'info' : ($order->order_status == 'pending' ? 'warning' : 'danger')) }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </td>
                            <td><strong>{{ $orderCounts[$order->unique_order_id] ?? 0 }}</strong></td>
                            <td>
                                <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-warning order-index-btn-edit-order">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger order-index-btn-delete-order">
                                        <i class="fas fa-trash-alt"></i>
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
@endsection
