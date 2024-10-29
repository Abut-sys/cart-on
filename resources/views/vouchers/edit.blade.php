@extends('layouts.index')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between" style="background-color: #d3d3d3;">
                <!-- Light gray header -->
                <h2 class="mb-0" style="color: black;">Edit Voucher</h2> <!-- Black header text -->
                <a href="{{ route('vouchers.index') }}" class="btn btn-danger"
                    style="background-color: #ff0000; color: black;">
                    <i class="fas fa-arrow-left"></i> Return
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('vouchers.update', $voucher) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="code">Code</label>
                        <input type="text" name="code" id="code" class="form-control"
                            value="{{ old('code', $voucher->code) }}" required>
                        @error('code')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="discount_value">Discount Value (%)</label>
                        <input type="number" name="discount_value" id="discount_value" class="form-control" min="0"
                            max="100" value="{{ old('discount_value', $voucher->discount_value) }}" required>
                        <small class="form-text text-muted">Enter a value between 0 and 100.</small>
                        @error('discount_value')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control"
                            value="{{ old('start_date', $voucher->start_date) }}" required>
                        @error('start_date')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control"
                            value="{{ old('end_date', $voucher->end_date) }}" required>
                        @error('end_date')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="Terms_and_Conditions">Terms and Conditions</label>
                        <textarea name="Terms_and_Conditions" id="Terms_and_Conditions" class="form-control" required>{{ old('Terms_and_Conditions', $voucher->Terms_and_Conditions) }}</textarea>
                        @error('Terms_and_Conditions')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="usage_limit">Usage Limit</label>
                        <input type="number" name="usage_limit" id="usage_limit" class="form-control"
                            value="{{ old('usage_limit', $voucher->usage_limit) }}" required>
                        @error('usage_limit')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success" style="background-color: #00FF00; color: black;">
                        Update Voucher
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
