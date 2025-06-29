@extends('layouts.index')

@section('title', 'Voucher')

@section('content')
    <div class="voucher-create-container mt-5">
        <div class="voucher-create-card shadow-lg">
            <div class="voucher-create-card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0 fw-bold">Create New Voucher</h2>
                <a href="{{ route('vouchers.index') }}" class="voucher-create-btn voucher-create-btn-return">
                    <i class="fas fa-arrow-left"></i> Return
                </a>
            </div>
            <div class="voucher-create-card-body">
                <form action="{{ route('vouchers.store') }}" method="POST">
                    @csrf

                    <div class="voucher-create-form-group mb-4">
                        <label for="code" class="voucher-create-form-label">Voucher Code</label>
                        <input type="text" name="code" id="code" class="voucher-create-form-control" required>
                        @error('code')
                            <div class="voucher-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- New field: Type --}}
                    <div class="voucher-create-form-group mb-4">
                        <label for="type" class="voucher-create-form-label">Discount Type</label>
                        <select name="type" id="type" class="voucher-create-form-control" required>
                            <option value="percentage" selected>Percentage (%)</option>
                            <option value="fixed">Fixed Amount (Rp)</option>
                        </select>
                        @error('type')
                            <div class="voucher-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="voucher-create-form-group mb-4">
                        <label for="discount_value" class="voucher-create-form-label" id="discount_value_label">
                            Discount Value (%)
                        </label>
                        <input type="number" name="discount_value" id="discount_value" class="voucher-create-form-control"
                            min="0" max="100" required>
                        <small class="voucher-create-form-text" id="discount_value_hint">
                            Enter a value between 0 and 100.
                        </small>
                        @error('discount_value')
                            <div class="voucher-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="voucher-create-form-group mb-4">
                        <label for="start_date" class="voucher-create-form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date"
                            class="voucher-create-form-control date-picker" required>
                        @error('start_date')
                            <div class="voucher-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="voucher-create-form-group mb-4">
                        <label for="end_date" class="voucher-create-form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="voucher-create-form-control date-picker"
                            required>
                        @error('end_date')
                            <div class="voucher-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="voucher-create-form-group mb-4">
                        <label for="terms_and_conditions" class="voucher-create-form-label">Terms and Conditions</label>
                        <textarea name="terms_and_conditions" id="terms_and_conditions" class="voucher-create-form-control"></textarea>
                        @error('terms_and_conditions')
                            <div class="voucher-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="voucher-create-form-group mb-4">
                        <label for="usage_limit" class="voucher-create-form-label">Usage Limit</label>
                        <input type="number" name="usage_limit" id="usage_limit" class="voucher-create-form-control"
                            required>
                        @error('usage_limit')
                            <div class="voucher-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="voucher-create-form-group mb-4">
                        <label for="max_per_user" class="voucher-create-form-label">Max Per User</label>
                        <input type="number" name="max_per_user" id="max_per_user" class="voucher-create-form-control"
                            min="1" required>
                        <small class="voucher-create-form-text">
                            Maximum times a single user can claim this voucher.
                        </small>
                        @error('max_per_user')
                            <div class="voucher-create-alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="voucher-create-btn voucher-create-btn-success w-100">
                        Create Voucher
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi Flatpickr pada elemen dengan kelas .date-picker
            flatpickr('.date-picker', {
                dateFormat: 'Y-m-d',
                minDate: 'today',
                disableMobile: true,
            });

            const typeSelect = document.getElementById('type');
            const discountLabel = document.getElementById('discount_value_label');
            const discountHint = document.getElementById('discount_value_hint');
            const discountInput = document.getElementById('discount_value');

            function updateDiscountLabel() {
                if (typeSelect.value === 'percentage') {
                    discountLabel.innerText = 'Discount Value (%)';
                    discountHint.innerText = 'Enter a value between 0 and 100.';
                    discountInput.min = 0;
                    discountInput.max = 100;
                } else {
                    discountLabel.innerText = 'Discount Value (Rp)';
                    discountHint.innerText = 'Enter a fixed amount in Rupiah.';
                    discountInput.removeAttribute('max');
                    discountInput.min = 0;
                }
            }

            typeSelect.addEventListener('change', updateDiscountLabel);
            updateDiscountLabel();
        });
    </script>
@endsection
