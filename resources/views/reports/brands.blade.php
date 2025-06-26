@extends('layouts.index')

@section('title', 'Brand Report')

@section('dongol')
    <div class="container-fluid mt-4">
        <h2 class="fw-bold mb-4">Brand Report</h2>

        <div class="bg-white shadow rounded p-4 mb-4">
            <form method="GET">
                <div class="row align-items-end g-3">
                    <div class="col-md-6">
                        <label for="search" class="form-label fw-semibold">Search Brand</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            class="form-control" placeholder="Search brand or category...">
                    </div>
                    <div class="col-md-6 d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                        <a href="{{ route('reports.brands.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="d-flex justify-content-end gap-2 mb-3 flex-wrap">
            <button class="btn btn-secondary" onclick="loadBrandPdfPreview()">
                <i class="fas fa-eye me-1"></i> Preview PDF
            </button>
            <button class="btn btn-danger" onclick="exportBrandPdf()">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </button>
            <button class="btn btn-success" onclick="exportBrandExcel()">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </button>
        </div>

        <div class="bg-white shadow rounded overflow-auto">
            <table class="table table-hover table-bordered align-middle text-center mb-0">
                <thead class="table-light sticky-top">
                    <tr>
                        @php
                            $columns = [
                                'name' => 'Brand Name',
                                'category' => 'Category Name',
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
                    </tr>
                </thead>
                <tbody>
                    @forelse ($brands as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->categoryProduct->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">
                                <div class="text-center py-4">
                                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076507.png" alt="No data"
                                        width="80" class="mb-2 opacity-50">
                                    <div class="text-muted">No brands found</div>
                                    <small class="text-muted">Try adjusting your filters.</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $brands->links() }}
        </div>

        <div id="brand-pdf-preview-container" class="mt-4" style="display: none;">
            <h5 class="mb-2">Preview PDF</h5>
            <iframe id="brand-pdf-preview-frame" style="width: 100%; height: 600px;" frameborder="0"></iframe>
        </div>
    </div>
@endsection

<script>
    function buildBrandPdfUrl(mode) {
        const base = "{{ route('reports.brands.export.pdf') }}";
        const params = new URLSearchParams(window.location.search);
        if (mode === 'preview') {
            params.set('mode', 'preview');
        }
        return `${base}?${params.toString()}`;
    }

    function loadBrandPdfPreview() {
        const frame = document.getElementById('brand-pdf-preview-frame');
        frame.src = buildBrandPdfUrl('preview');
        document.getElementById('brand-pdf-preview-container').style.display = 'block';
    }

    function exportBrandPdf() {
        const url = buildBrandPdfUrl('download');
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'brand-report.pdf');
        document.body.appendChild(link);
        link.click();
        link.remove();
    }

    function exportBrandExcel() {
        const url = new URL("{{ route('reports.brands.export.excel') }}");
        const params = new URLSearchParams(window.location.search);
        url.search = params.toString();

        const link = document.createElement('a');
        link.href = url.toString();
        link.setAttribute('download', 'brand-report.xlsx');
        document.body.appendChild(link);
        link.click();
        link.remove();
    }
</script>
