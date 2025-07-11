<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Voucher Report</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #eee;
        }

        .text-success {
            color: green;
        }

        .text-danger {
            color: red;
        }
    </style>
</head>

<body>
    <h2>Voucher Report</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Code</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Limit</th>
                <th>Discount</th>
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
                    <td class="{{ $voucher->status === 'active' ? 'text-success' : 'text-danger' }}">
                        {{ ucfirst($voucher->status) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">No vouchers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
