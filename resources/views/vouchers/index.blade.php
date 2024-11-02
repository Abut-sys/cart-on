@extends('layouts.index')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center"
                style="background-color: #d3d3d3; color: black;">
                <h2 class="mb-0">Voucher List</h2>
                <a href="{{ route('vouchers.create') }}" class="btn btn-success me-2"
                    style="background-color: #00FF00; color: black;">
                    <i class="fas fa-plus"></i> New Voucher
                </a>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover">
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
                                <td>{{ $voucher->discount_value }}</td>
                                <td>{{ $voucher->start_date }}</td>
                                <td>{{ $voucher->end_date }}</td>
                                <td>{{ $voucher->usage_limit }}</td>
                                <td style="color: {{ $voucher->isActive() ? 'green' : 'red' }}">
                                    {{ $voucher->isActive() ? 'Active' : 'Inactive' }}
                                </td>
                                <td>
                                    <a href="{{ route('vouchers.edit', $voucher) }}" class="btn btn-primary btn-sm"
                                        style="background-color: #0000FF; color: white;" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('vouchers.destroy', $voucher) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            style="background-color: #FF0000; color: white;" title="Delete">
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

    <style>
        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }
    </style>
@endsection
