@extends('layouts.index')

@section('title', 'Voucher')

@section('content')
    <div class="voucher-create-container mt-4">
        <div class="voucher-create-card shadow-sm">
            <div class="voucher-create-card-header d-flex justify-content-between">
                <h2 class="mb-0">New Voucher</h2>
                <a href="{{ route('vouchers.index') }}" class="voucher-create-btn voucher-create-btn-danger">
                    <i class="fas fa-arrow-left"></i> Return
                </a>
            </div>
            <div class="voucher-create-card-body">
                <form action="{{ route('vouchers.store') }}" method="POST">
                    @csrf

                    <div class="voucher-create-form-group mb-3">
                        <label for="code">Code</label>
                        <input type="text" name="code" id="code" class="voucher-create-form-control" required>
                        @error('code')
                            <div class="voucher-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="voucher-create-form-group mb-3">
                        <label for="discount_value">Discount Value (%)</label>
                        <input type="number" name="discount_value" id="discount_value" class="voucher-create-form-control" min="0" max="100" required>
                        <small class="voucher-create-form-text">Enter a value between 0 and 100.</small>
                        @error('discount_value')
                            <div class="voucher-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="voucher-create-form-group mb-3">
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="voucher-create-form-control" required>
                        @error('start_date')
                            <div class="voucher-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="voucher-create-form-group mb-3">
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="voucher-create-form-control" required>
                        @error('end_date')
                            <div class="voucher-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="voucher-create-form-group mb-3">
                        <label for="Terms_and_Conditions">Terms and Conditions</label>
                        <textarea name="Terms_and_Conditions" id="Terms_and_Conditions" class="voucher-create-form-control"></textarea>
                        @error('Terms_and_Conditions')
                            <div class="voucher-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="voucher-create-form-group mb-3">
                        <label for="usage_limit">Usage Limit</label>
                        <input type="number" name="usage_limit" id="usage_limit" class="voucher-create-form-control" required>
                        @error('usage_limit')
                            <div class="voucher-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="voucher-create-btn voucher-create-btn-success">Create</button>
                </form>
            </div>
        </div>
    </div>
@endsection
