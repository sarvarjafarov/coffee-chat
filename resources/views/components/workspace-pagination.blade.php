@php
    $canPaginate = $paginator instanceof \Illuminate\Pagination\LengthAwarePaginator
        || $paginator instanceof \Illuminate\Pagination\Paginator;
@endphp

@if ($canPaginate && $paginator->hasPages())
    @php($totalPages = $paginator->lastPage())
    <div class="workspace-pagination">
        <div class="workspace-pagination__meta">
            Showing
            <strong>{{ $paginator->firstItem() }}</strong>
            to
            <strong>{{ $paginator->lastItem() }}</strong>
            of
            <strong>{{ $paginator->total() }}</strong>
            results
        </div>
        <div class="workspace-pagination__controls">
            @if ($paginator->onFirstPage())
                <span class="page-btn disabled">
                    <span class="mdi mdi-arrow-left"></span>
                    Previous
                </span>
            @else
                <a class="page-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                    <span class="mdi mdi-arrow-left"></span>
                    Previous
                </a>
            @endif

            <div class="page-numbers">
                @for ($page = 1; $page <= $totalPages; $page++)
                    @if ($page == $paginator->currentPage())
                        <span class="page-number active">{{ $page }}</span>
                    @else
                        <a class="page-number" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                    @endif
                @endfor
            </div>

            @if ($paginator->hasMorePages())
                <a class="page-btn" href="{{ $paginator->nextPageUrl() }}" rel="next">
                    Next
                    <span class="mdi mdi-arrow-right"></span>
                </a>
            @else
                <span class="page-btn disabled">
                    Next
                    <span class="mdi mdi-arrow-right"></span>
                </span>
            @endif
        </div>
    </div>
@endif
