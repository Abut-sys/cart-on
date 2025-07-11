@extends('layouts.index')

@section('title', 'Product Report')

@section('dongol')
    <div class="container-fluid mt-4">

        <h2 class="fw-bold mb-4">Product Report</h2>

        <div class="bg-white shadow rounded p-4 mb-4">
            <form method="GET">
                <div class="row align-items-end g-3">
                    <div class="col-md-6">
                        <label for="search" class="form-label fw-semibold">Search Product</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            class="form-control" placeholder="Search product name, brand, or category...">
                    </div>
                    <div class="col-md-6 d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                        <a href="{{ route('reports.products.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="d-flex justify-content-end gap-2 mb-3 flex-wrap">
            <button class="btn btn-secondary" onclick="loadPdfPreview()">
                <i class="fas fa-eye me-1"></i> Preview PDF
            </button>
            <button class="btn btn-danger" onclick="exportPdf()">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </button>
            <button class="btn btn-success" onclick="exportExcel()">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </button>
        </div>

        <div class="bg-white shadow rounded overflow-auto">
            <table class="table table-hover table-bordered align-middle text-center mb-0">
                <thead class="table-light sticky-top">
                    <tr>
                        @php
                            $columns = [
                                'name' => 'Name',
                                'sub_category' => 'Category',
                                'brand' => 'Brand',
                                'price' => 'Price',
                                'sales' => 'Sales',
                                'wishlists_count' => 'Wishlist',
                                'rating' => 'Rating',
                                'review_products_count' => 'Review',
                            ];
                            $currentSort = request('sort_column');
                            $currentDir = request('sort_direction') === 'asc' ? 'desc' : 'asc';
                        @endphp

                        @foreach ($columns as $key => $label)
                            <th class="text-nowrap">
                                <a href="{{ request()->fullUrlWithQuery(['sort_column' => $key, 'sort_direction' => $currentSort === $key ? $currentDir : 'desc']) }}"
                                    class="text-decoration-none text-dark">
                                    {{ $label }}
                                    @if ($currentSort === $key)
                                        {!! request('sort_direction') === 'asc' ? '↑' : '↓' !!}
                                    @endif
                                </a>
                            </th>
                        @endforeach

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
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>

        <div id="pdf-preview-container" class="mt-4" style="display: none;">
            <h5 class="mb-2">Preview PDF</h5>
            <iframe id="pdf-preview-frame" style="width: 100%; height: 600px;" frameborder="0"></iframe>
        </div>
    </div>
@endsection

<script>
    function buildPdfUrl(mode) {
        const base = "{{ route('reports.products.export.pdf') }}";
        const params = new URLSearchParams(window.location.search);
        if (mode === 'preview') {
            params.set('mode', 'preview');
        }
        return `${base}?${params.toString()}`;
    }

    function loadPdfPreview() {
        const frame = document.getElementById('pdf-preview-frame');
        frame.src = buildPdfUrl('preview');
        document.getElementById('pdf-preview-container').style.display = 'block';
    }

    function exportPdf() {
        const url = buildPdfUrl('download');
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'product-report.pdf');
        document.body.appendChild(link);
        link.click();
        link.remove();
    }

    function exportExcel() {
        const url = new URL("{{ route('reports.products.export.excel') }}");
        const params = new URLSearchParams(window.location.search);
        url.search = params.toString();

        const link = document.createElement('a');
        link.href = url.toString();
        link.setAttribute('download', 'product-report.xlsx');
        document.body.appendChild(link);
        link.click();
        link.remove();
    }
</script>
