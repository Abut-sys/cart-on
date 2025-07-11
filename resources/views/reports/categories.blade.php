@extends('layouts.index')

@section('title', 'Category Report')

@section('dongol')
    <div class="container-fluid mt-4">

        <h2 class="fw-bold mb-4">Category Report</h2>

        <div class="bg-white shadow rounded p-4 mb-4">
            <form method="GET">
                <div class="row align-items-end g-3">
                    <div class="col-md-6">
                        <label for="search" class="form-label fw-semibold">Search Category</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            class="form-control" placeholder="Search category or subcategory...">
                    </div>
                    <div class="col-md-6 d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                        <a href="{{ route('reports.categories.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="d-flex justify-content-end gap-2 mb-3 flex-wrap">
            <button class="btn btn-secondary" onclick="loadCategoryPdfPreview()">
                <i class="fas fa-eye me-1"></i> Preview PDF
            </button>
            <button class="btn btn-danger" onclick="exportCategoryPdf()">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </button>
            <button class="btn btn-success" onclick="exportCategoryExcel()">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </button>
        </div>

        <div class="bg-white shadow rounded overflow-auto">
            <table class="table table-hover table-bordered align-middle text-center mb-0">
                <thead class="table-light sticky-top">
                    <tr>
                        @php
                            $columns = [
                                'name' => 'Category Name',
                                'sub_category' => 'Sub Category',
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
                            <td colspan="2">
                                <div class="text-center py-4">
                                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076507.png" alt="No data"
                                        width="80" class="mb-2 opacity-50">
                                    <div class="text-muted">No categories found</div>
                                    <small class="text-muted">Try adjusting your filters.</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $categories->links() }}
        </div>

        <div id="category-pdf-preview-container" class="mt-4" style="display: none;">
            <h5 class="mb-2">Preview PDF</h5>
            <iframe id="category-pdf-preview-frame" style="width: 100%; height: 600px;" frameborder="0"></iframe>
        </div>
    </div>
@endsection

<script>
    function buildCategoryPdfUrl(mode) {
        const base = "{{ route('reports.categories.export.pdf') }}";
        const params = new URLSearchParams(window.location.search);
        if (mode === 'preview') {
            params.set('mode', 'preview');
        }
        return `${base}?${params.toString()}`;
    }

    function loadCategoryPdfPreview() {
        const frame = document.getElementById('category-pdf-preview-frame');
        frame.src = buildCategoryPdfUrl('preview');
        document.getElementById('category-pdf-preview-container').style.display = 'block';
    }

    function exportCategoryPdf() {
        const url = buildCategoryPdfUrl('download');
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'category-report.pdf');
        document.body.appendChild(link);
        link.click();
        link.remove();
    }

    function exportCategoryExcel() {
        const url = new URL("{{ route('reports.categories.export.excel') }}");
        const params = new URLSearchParams(window.location.search);
        url.search = params.toString();

        const link = document.createElement('a');
        link.href = url.toString();
        link.setAttribute('download', 'category-report.xlsx');
        document.body.appendChild(link);
        link.click();
        link.remove();
    }
</script>
