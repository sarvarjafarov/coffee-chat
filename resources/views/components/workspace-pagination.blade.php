@php
    $isPaginator = $paginator instanceof \Illuminate\Pagination\LengthAwarePaginator
        || $paginator instanceof \Illuminate\Pagination\Paginator;
    $elements = $isPaginator ? $paginator->elements() : [];
@endphp

@if ($isPaginator && $paginator->hasPages())
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
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="page-number disabled">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="page-number active">{{ $page }}</span>
                            @else
                                <a class="page-number" href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
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
