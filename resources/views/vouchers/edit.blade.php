@extends('layouts.index')

@section('title', 'Edit Voucher')

@section('content')
    <div class="voucher-edit-container mt-5">
        <div class="voucher-edit-card shadow-lg">
            <div class="voucher-edit-card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0 fw-bold">Edit Voucher</h2>
                <a href="{{ route('vouchers.index') }}" class="voucher-edit-btn voucher-edit-btn-return">
                    <i class="fas fa-arrow-left"></i> Return
                </a>
            </div>
            <div class="voucher-edit-card-body">
                <form action="{{ route('vouchers.update', $voucher) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="voucher-edit-form-group mb-4">
                        <label for="code" class="voucher-edit-form-label">Voucher Code</label>
                        <input type="text" name="code" id="code" class="voucher-edit-form-control"
                            value="{{ old('code', $voucher->code) }}" required>
                        @error('code')
                            <div class="voucher-edit-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="voucher-edit-form-group mb-4">
                        <label for="discount_value" class="voucher-edit-form-label">Discount Value (%)</label>
                        <input type="number" name="discount_value" id="discount_value" class="voucher-edit-form-control"
                            min="0" max="100" value="{{ old('discount_value', $voucher->discount_value) }}" required>
                        <small class="voucher-edit-form-text">Enter a value between 0 and 100.</small>
                        @error('discount_value')
                            <div class="voucher-edit-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="voucher-edit-form-group mb-4">
                        <label for="start_date" class="voucher-edit-form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="voucher-edit-form-control"
                            value="{{ old('start_date', $voucher->start_date) }}" required>
                        @error('start_date')
                            <div class="voucher-edit-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="voucher-edit-form-group mb-4">
                        <label for="end_date" class="voucher-edit-form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="voucher-edit-form-control"
                            value="{{ old('end_date', $voucher->end_date) }}" required>
                        @error('end_date')
                            <div class="voucher-edit-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="voucher-edit-form-group mb-4">
                        <label for="Terms_and_Conditions" class="voucher-edit-form-label">Terms and Conditions</label>
                        <textarea name="Terms_and_Conditions" id="Terms_and_Conditions" class="voucher-edit-form-control">{{ old('Terms_and_Conditions', $voucher->Terms_and_Conditions) }}</textarea>
                        @error('Terms_and_Conditions')
                            <div class="voucher-edit-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="voucher-edit-form-group mb-4">
                        <label for="usage_limit" class="voucher-edit-form-label">Usage Limit</label>
                        <input type="number" name="usage_limit" id="usage_limit" class="voucher-edit-form-control"
                            value="{{ old('usage_limit', $voucher->usage_limit) }}" required>
                        @error('usage_limit')
                            <div class="voucher-edit-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="voucher-edit-btn voucher-edit-btn-success w-100">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
@endsection
