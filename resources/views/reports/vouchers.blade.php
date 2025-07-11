@extends('layouts.index')

@section('title', 'Voucher Report')

@section('dongol')
    <div class="container-fluid mt-4">
        <h2 class="fw-bold mb-4">Voucher Report</h2>

        <div class="bg-white shadow rounded p-4 mb-4">
            <form method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Search...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Type</label>
                        <select name="type" class="form-select">
                            <option value="">All</option>
                            <option value="percentage" {{ request('type') == 'percentage' ? 'selected' : '' }}>Percentage
                            </option>
                            <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                    </div>
                </div>
                <div class="mt-3 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i> Search</button>
                    <a href="{{ route('reports.vouchers.index') }}" class="btn btn-outline-secondary"><i
                            class="fas fa-undo me-1"></i> Reset</a>
                </div>
            </form>
        </div>

        <div class="d-flex justify-content-end gap-2 mb-3 flex-wrap">
            <button class="btn btn-secondary" onclick="loadVoucherPdfPreview()"><i class="fas fa-eye me-1"></i> Preview
                PDF</button>
            <button class="btn btn-danger" onclick="exportVoucherPdf()"><i class="fas fa-file-pdf me-1"></i> Export
                PDF</button>
            <button class="btn btn-success" onclick="exportVoucherExcel()"><i class="fas fa-file-excel me-1"></i> Export
                Excel</button>
        </div>

        <div class="bg-white shadow rounded overflow-auto">
            <table class="table table-bordered text-center align-middle mb-0">
                <thead class="table-light sticky-top">
                    <tr>
                        @php
                            $sortableColumns = [
                                'id' => 'No',
                                'code' => 'Code',
                                'start_date' => 'Start Date',
                                'end_date' => 'End Date',
                                'usage_limit' => 'Limit',
                            ];

                            $currentSort = request('sort_column');
                            $currentDir = request('sort_direction') === 'asc' ? 'desc' : 'asc';
                        @endphp

                        @foreach ($sortableColumns as $col => $label)
                            <th>
                                <a href="{{ route('reports.vouchers.index', array_merge(request()->query(), ['sort_column' => $col, 'sort_direction' => $currentSort === $col ? $currentDir : 'desc'])) }}"
                                    class="text-decoration-none text-dark">
                                    {{ $label }}
                                    @if ($currentSort === $col)
                                        {{ request('sort_direction') === 'asc' ? '↑' : '↓' }}
                                    @endif
                                </a>
                            </th>
                        @endforeach

                        <th>
                            @php
                                $discountSorted = $currentSort === 'discount_value';
                                $discountDir = $discountSorted ? $currentDir : 'desc';
                            @endphp
                            @if (request('type'))
                                <a href="{{ route('reports.vouchers.index', array_merge(request()->query(), ['sort_column' => 'discount_value', 'sort_direction' => $discountDir])) }}"
                                    class="text-decoration-none text-dark">
                                    Discount
                                    @if ($discountSorted)
                                        {{ request('sort_direction') === 'asc' ? '↑' : '↓' }}
                                    @endif
                                </a>
                            @else
                                Discount
                            @endif
                        </th>

                        <th>Type</th>
                        <th>Terms</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vouchers as $voucher)
                        <tr>
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
                                <span class="{{ $voucher->status === 'active' ? 'text-success' : 'text-danger' }}">
                                    {{ ucfirst($voucher->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
                                <div class="mt-2">No vouchers found.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $vouchers->links() }}
        </div>

        <div id="voucher-pdf-preview-container" class="mt-4" style="display: none;">
            <h5 class="mb-2">Preview PDF</h5>
            <iframe id="voucher-pdf-preview-frame" style="width: 100%; height: 600px;" frameborder="0"></iframe>
        </div>
    </div>
@endsection

<script>
    function buildVoucherPdfUrl(mode) {
        const base = "{{ route('reports.vouchers.export.pdf') }}";
        const params = new URLSearchParams(window.location.search);
        if (mode === 'preview') params.set('mode', 'preview');
        return `${base}?${params.toString()}`;
    }

    function loadVoucherPdfPreview() {
        const frame = document.getElementById('voucher-pdf-preview-frame');
        frame.src = buildVoucherPdfUrl('preview');
        document.getElementById('voucher-pdf-preview-container').style.display = 'block';
    }

    function exportVoucherPdf() {
        const link = document.createElement('a');
        link.href = buildVoucherPdfUrl('download');
        link.setAttribute('download', 'voucher-report.pdf');
        document.body.appendChild(link);
        link.click();
        link.remove();
    }

    function exportVoucherExcel() {
        const url = new URL("{{ route('reports.vouchers.export.excel') }}");
        url.search = new URLSearchParams(window.location.search).toString();
        const link = document.createElement('a');
        link.href = url.toString();
        link.setAttribute('download', 'voucher-report.xlsx');
        document.body.appendChild(link);
        link.click();
        link.remove();
    }

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
