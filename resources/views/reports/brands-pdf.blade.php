<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Brand Report PDF</title>
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
    </style>
</head>

<body>
    <h3 style="text-align:center;">Brand Report</h3>
    <table>
        <thead>
            <tr>
                <th>Brand Name</th>
                <th>Category Name</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($brands as $brand)
                <tr>
                    <td>{{ $brand->name }}</td>
                    <td>{{ $brand->categoryProduct->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">No brands found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
