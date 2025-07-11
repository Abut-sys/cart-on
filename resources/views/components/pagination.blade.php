@if ($paginator->hasPages())
<style>
    .custom-pagination {
        display: flex;
        justify-content: center;
        margin-top: 1rem;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .pagination-list {
        list-style: none;
        display: flex;
        gap: 0.5rem;
        padding: 0;
        margin: 0;
    }

    .pagination-list li {
        width: 40px;
        height: 40px;
        border: 2px solid #99bc85;
        border-radius: 50%;
        user-select: none;
        transition: background-color 0.3s, color 0.3s, border-color 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Hapus padding di li, letakkan di a */
    .pagination-list li a {
        display: block;
        width: 100%;
        height: 100%;
        line-height: 36px; /* agar teks vertikal center */
        text-align: center;
        text-decoration: none;
        color: #567d46;
        font-weight: 600;
        font-size: 1rem;
        border-radius: 50%; /* agar a juga bulat */
        user-select: none;
    }

    .pagination-list li a:hover {
        background-color: #99bc85;
        color: white;
        border-color: #99bc85;
    }

    .pagination-list li.active {
        background-color: #99bc85;
        color: white;
        font-weight: 700;
        pointer-events: none;
        border-color: #99bc85;
    }

    .pagination-list li.disabled span {
        color: #ccc;
        cursor: default;
        pointer-events: none;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        border-radius: 50%;
    }
</style>

<nav class="custom-pagination" role="navigation" aria-label="Pagination Navigation">
    <ul class="pagination-list">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                <span aria-hidden="true">&lsaquo;</span>
            </li>
        @else
            <li>
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
            </li>
        @else
            <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                <span aria-hidden="true">&rsaquo;</span>
            </li>
        @endif
    </ul>
</nav>
@endif
