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
                <th>Color</th>
                <th>Size</th>
                <th>Stock</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                @php $count = $product->subVariant->count(); @endphp

                @if ($count > 0)
                    @foreach ($product->subVariant as $i => $variant)
                        <tr>
                            @if ($i === 0)
                                <td rowspan="{{ $count }}">{{ $product->name }}</td>
                                <td rowspan="{{ $count }}">{{ $product->subCategory->name ?? '-' }}</td>
                                <td rowspan="{{ $count }}">{{ $product->brand->name ?? '-' }}</td>
                                <td rowspan="{{ $count }}">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td rowspan="{{ $count }}">{{ $product->sales }}</td>
                                <td rowspan="{{ $count }}">{{ $product->wishlists_count }}</td>
                                <td rowspan="{{ $count }}">{{ number_format($product->rating, 1) }}</td>
                                <td rowspan="{{ $count }}">{{ $product->review_products_count }}</td>
                            @endif

                            <td>{{ $variant->color }}</td>
                            <td>{{ $variant->size }}</td>
                            <td>{{ $variant->stock }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->subCategory->name ?? '-' }}</td>
                        <td>{{ $product->brand->name ?? '-' }}</td>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>{{ $product->sales }}</td>
                        <td>{{ $product->wishlists_count }}</td>
                        <td>{{ number_format($product->rating, 1) }}</td>
                        <td>{{ $product->review_products_count }}</td>
                        <td class="text-muted">-</td>
                        <td class="text-muted">-</td>
                        <td class="text-muted">-</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="11">
                        <div class="text-center py-4">
                            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076507.png" alt="No data"
                                width="80" class="mb-2 opacity-50">
                            <div class="text-muted">No products found</div>
                            <small class="text-muted">Try adjusting your filters.</small>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
