@extends('layouts.index')

@section('content')
    <div class="voucher-index-container mt-4">
        <div class="voucher-index-card shadow-sm">
            <div class="voucher-index-card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Voucher List</h2>
                <a href="{{ route('vouchers.create') }}" class="voucher-index-btn voucher-index-btn-add me-2">
                    <i class="fas fa-plus"></i> New Voucher
                </a>
            </div>
            <div class="voucher-index-card-body">
                <table class="voucher-index-table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Discount</th>
                            <th>Start-Date</th>
                            <th>End-Date</th>
                            <th>Usage-Limit</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vouchers as $voucher)
                            <tr>
                                <td>{{ $voucher->code }}</td>
                                <td>{{ $voucher->discount_value }}%</td>
                                <td>{{ $voucher->start_date }}</td>
                                <td>{{ $voucher->end_date }}</td>
                                <td>{{ $voucher->usage_limit }}</td>
                                <td style="color: {{ $voucher->isActive() ? 'green' : 'red' }}">
                                    {{ $voucher->isActive() ? 'Active' : 'Inactive' }}
                                </td>
                                <td>
                                    <a href="{{ route('vouchers.edit', $voucher) }}" class="voucher-index-btn voucher-index-btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('vouchers.destroy', $voucher) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="voucher-index-btn voucher-index-btn-delete" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
