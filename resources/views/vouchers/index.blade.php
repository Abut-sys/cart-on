@extends('layouts.index')

@section('title', 'Voucher')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4 position-relative">
            <h2 class="text-center w-100 fw-bold voucher-index-title">Voucher List</h2>
            <a href="{{ route('vouchers.create') }}" class="voucher-index-btn-add">
                <i class="fas fa-plus"></i> Add Voucher
            </a>
        </div>

        <form action="{{ route('vouchers.index') }}" method="GET" class="mb-3">
            <div class="voucher-index-head-row mb-2">
                <input type="text" name="search" class="voucher-index-search" placeholder="Search anything..."
                    value="{{ request('search') }}">
                <button type="submit" class="voucher-index-btn-search px-3"><i class="fas fa-search"></i></button>
                <a href="{{ route('vouchers.index') }}" class="voucher-index-btn-reset px-3">Reset</a>
            </div>

            <div class="voucher-index-head-row voucher-index-small-filter-row">
                <select name="type" class="form-select form-select-sm voucher-index-filter-small">
                    <option value="">All Types</option>
                    <option value="percentage" {{ request('type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                    <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                </select>

                <select name="status" class="form-select form-select-sm voucher-index-filter-small">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="form-control form-control-sm voucher-index-filter-small">
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="form-control form-control-sm voucher-index-filter-small">
            </div>
        </form>

        <table class="voucher-index-table">
            <thead class="voucher-index-thead">
                <tr>
                    @php
                        $sortableColumns = [
                            'id' => 'No',
                            'code' => 'Code',
                            'start_date' => 'Start Date',
                            'end_date' => 'End Date',
                            'usage_limit' => 'Limit',
                        ];

                        $nonSortableColumns = [
                            'type' => 'Type',
                            'discount_value' => 'Discount',
                            'terms_and_conditions' => 'Terms',
                            'status' => 'Status',
                        ];
                    @endphp

                    @foreach ($sortableColumns as $col => $label)
                        @php
                            $isSorted = request('sort_column') === $col;
                            $dir = $isSorted ? (request('sort_direction') === 'asc' ? 'desc' : 'asc') : 'desc';
                            $icon = $isSorted
                                ? 'fas fa-sort-' . (request('sort_direction') === 'asc' ? 'up' : 'down')
                                : 'fas fa-sort';
                        @endphp
                        <th>
                            <a href="{{ route('vouchers.index', array_merge(request()->query(), ['sort_column' => $col, 'sort_direction' => $dir])) }}"
                                class="text-decoration-none text-white">
                                {{ $label }} <i class="{{ $icon }}"></i>
                            </a>
                        </th>
                    @endforeach

                    <th>
                        @if (request('type'))
                            @php
                                $isSorted = request('sort_column') === 'discount_value';
                                $dir = $isSorted ? (request('sort_direction') === 'asc' ? 'desc' : 'asc') : 'desc';
                                $icon = $isSorted
                                    ? 'fas fa-sort-' . (request('sort_direction') === 'asc' ? 'up' : 'down')
                                    : 'fas fa-sort';
                            @endphp
                            <a href="{{ route('vouchers.index', array_merge(request()->query(), ['sort_column' => 'discount_value', 'sort_direction' => $dir])) }}"
                                class="text-decoration-none text-white">
                                Discount <i class="{{ $icon }}"></i>
                            </a>
                        @else
                            Discount
                        @endif
                    </th>

                    <th>Type</th>
                    <th>Terms</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($vouchers as $voucher)
                    <tr class="voucher-index-row">
                        <td>{{ $voucher->id }}</td>
                        <td>{{ $voucher->code }}</td>
                        <td>{{ $voucher->start_date->format('Y-m-d') }}</td>
                        <td>{{ $voucher->end_date->format('Y-m-d') }}</td>
                        <td>{{ $voucher->usage_limit }}</td>

                        <td>
                            @if ($voucher->type === 'percentage')
                                {{ $voucher->discount_value }}%
                            @else
                                Rp {{ number_format($voucher->discount_value, 0, ',', '.') }}
                            @endif
                        </td>

                        <td>{{ ucfirst($voucher->type) }}</td>
                        <td>{{ $voucher->terms_and_conditions }}</td>
                        <td>
                            <span class="{{ $voucher->status == 'active' ? 'text-success' : 'text-danger' }}">
                                {{ ucfirst($voucher->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('vouchers.edit', $voucher) }}" class="voucher-index-btn-edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('vouchers.destroy', $voucher) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="voucher-index-btn-delete"
                                    onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center py-4 text-muted">
                            <div class="mt-2">No vouchers found.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $vouchers->links() }}
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input, select');

        inputs.forEach(input => {
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    form.submit();
                }
            });

            if (input.tagName === 'SELECT' || input.type === 'date') {
                input.addEventListener('change', function() {
                    form.submit();
                });
            }
        });
    });
</script>
