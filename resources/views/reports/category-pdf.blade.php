<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Category Report PDF</title>
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
    <h3 style="text-align:center;">Category Report</h3>
    <table>
        <thead>
            <tr>
                <th>Category Name</th>
                <th>Sub Category</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                @php $count = $category->subCategories->count(); @endphp

                @if ($count > 0)
                    @foreach ($category->subCategories as $i => $sub)
                        <tr>
                            @if ($i === 0)
                                <td rowspan="{{ $count }}">{{ $category->name }}</td>
                            @endif
                            <td>{{ $sub->name }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td class="text-muted">-</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="2">No categories found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
