<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Product Report PDF</title>
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
        th, td {
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
    <h3 style="text-align:center;">Product Report</h3>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Sales</th>
                <th>Wishlist</th>
                <th>Rating</th>
                <th>Review</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->subCategory->name ?? '-' }}</td>
                <td>{{ $product->brand->name ?? '-' }}</td>
                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                <td>{{ $product->sales }}</td>
                <td>{{ $product->wishlists_count }}</td>
                <td>{{ number_format($product->rating, 1) }}</td>
                <td>{{ $product->review_products_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
