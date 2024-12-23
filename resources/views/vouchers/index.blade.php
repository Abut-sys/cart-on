@extends('layouts.index')

@section('title', 'Voucher')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4 position-relative">
            <h2 class="text-center w-100 fw-bold">Voucher List</h2>
            <a href="{{ route('vouchers.create') }}" class="voucher-index-btn voucher-index-btn-add">
                <i class="fas fa-plus"></i> Add Voucher
            </a>
        </div>

        <form action="{{ route('vouchers.index') }}" method="GET" class="voucher-index-search-form mb-4">
            <div class="d-flex">
                <!-- Input untuk Kode Voucher -->
                <input type="text" name="code" class="voucher-index-form-control me-2" placeholder="Search by Code"
                    value="{{ request('code') }}">

                <!-- Dropdown untuk Status -->
                <select name="status" class="voucher-index-form-control me-2">
                    <option value="">Select Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <!-- Tombol Pencarian -->
                <button type="submit" class="voucher-index-btn voucher-index-btn-search">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>

        <!-- Voucher List -->
        <div class="voucher-index-container">
            @foreach ($vouchers as $voucher)
                <div class="voucher-index-row {{ $voucher->status == 'inactive' ? 'voucher-inactive-bg' : '' }}">
                    <div class="voucher-index-item">
                        <strong>Code:</strong> {{ $voucher->code }}
                    </div>
                    <div class="voucher-index-item">
                        <strong>Discount:</strong> {{ $voucher->discount_value }}%
                    </div>
                    <div class="voucher-index-item">
                        <strong>Terms and Conditions:</strong>
                    </div>
                    <div class="voucher-index-item">
                        <strong>Start Date:</strong> {{ $voucher->start_date->format('Y-m-d') }}
                    </div>
                    <div class="voucher-index-item">
                        <strong>End Date:</strong> {{ $voucher->end_date->format('Y-m-d') }}
                    </div>
                    <div class="voucher-index-item">
                        <strong>Usage Limit:</strong> {{ $voucher->usage_limit }}
                    </div>
                    <div class="voucher-index-item">
                        <strong>Status:</strong>
                        <span class="{{ $voucher->status == 'active' ? 'text-success' : 'text-danger' }}">
                            {{ ucfirst($voucher->status) }}
                        </span>
                    </div>
                    <div class="voucher-index-actions">
                        <a href="{{ route('vouchers.edit', $voucher) }}" class="voucher-index-btn voucher-index-btn-edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('vouchers.destroy', $voucher) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="voucher-index-btn voucher-index-btn-delete">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
