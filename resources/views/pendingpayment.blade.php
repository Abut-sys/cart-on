@extends('layouts.index')

@section('content')
    <div class="ps-account-container">
        <div class="ps-account-layout">
            <!-- Profile Sidebar - Left -->
            <div class="ps-account-sidebar">
                @include('components.profile-sidebar')
            </div>

            <!-- Main Content - Right -->
            <div class="ps-account-content">
                <div class="ps-card">
                    <div class="ps-card-header">
                        <h2 class="ps-card-title">
                            <i class="fas fa-clock ps-nav-icon"></i> Orders Waiting for Payment
                        </h2>
                        <span class="ps-badge">{{ $pendingOrders->total() }} orders</span>
                    </div>

                    <div class="ps-card-body">
                        <div class="ps-table-responsive">
                            <table class="ps-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Order Date</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Shipping</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendingOrders as $order)
                                        <tr>
                                            <td>{{ $order->unique_order_id }}</td>
                                            <td>{{ $order->order_date->format('d M Y H:i') }}</td>
                                            <td>
                                                @if ($order->user)
                                                    {{ $order->user->name }}
                                                @else
                                                    <span class="text-danger">User missing (ID:
                                                        {{ $order->user_id }})</span>
                                                @endif
                                            </td>
                                            <td>{{ number_format($order->amount, 0) }}</td>
                                            <td>{{ $order->shipping_service }}</td>
                                            <td>
                                                <a href="{{ route('orders.show', $order->id) }}"
                                                    class="ps-btn ps-btn-sm ps-btn-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="ps-text-center ps-py-4">
                                                No orders waiting for payment
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="ps-pagination">
                            {{ $pendingOrders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
