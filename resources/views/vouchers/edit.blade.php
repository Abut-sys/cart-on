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
                            min="0" max="100" value="{{ old('discount_value', $voucher->discount_value) }}"
                            required>
                        <small class="voucher-edit-form-text">Enter a value between 0 and 100.</small>
                        @error('discount_value')
                            <div class="voucher-edit-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Start Date with Flatpickr -->
                    <div class="voucher-edit-form-group mb-4">
                        <label for="start_date" class="voucher-edit-form-label">Start Date</label>
                        <input type="text" name="start_date" id="start_date"
                            class="voucher-edit-form-control date-picker"
                            value="{{ old('start_date', \Carbon\Carbon::parse($voucher->start_date)->format('Y-m-d')) }}"
                            required>
                        @error('start_date')
                            <div class="voucher-edit-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- End Date with Flatpickr -->
                    <div class="voucher-edit-form-group mb-4">
                        <label for="end_date" class="voucher-edit-form-label">End Date</label>
                        <input type="text" name="end_date" id="end_date" class="voucher-edit-form-control date-picker"
                            value="{{ old('end_date', \Carbon\Carbon::parse($voucher->end_date)->format('Y-m-d')) }}"
                            required>
                        @error('end_date')
                            <div class="voucher-edit-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="voucher-edit-form-group mb-4">
                        <label for="terms_and_conditions" class="voucher-edit-form-label">Terms and Conditions</label>
                        <textarea name="terms_and_conditions" id="terms_and_conditions" class="voucher-edit-form-control">{{ old('terms_and_conditions', $voucher->terms_and_conditions) }}</textarea>
                        @error('terms_and_conditions')
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

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Inisialisasi Flatpickr pada elemen dengan kelas .date-picker
                flatpickr('.date-picker', {
                    dateFormat: 'Y-m-d', // Format tanggal (tahun-bulan-hari)
                    minDate: 'today', // Menghindari tanggal masa depan
                    disableMobile: true, // Menghindari penggunaan Flatpickr di perangkat mobile
                });
            });
        </script>
@endsection
