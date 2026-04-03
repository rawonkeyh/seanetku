@if ($paginator->hasPages())
    <div class="pagination-wrap">
        <div class="pagination-meta">
            Menampilkan {{ $paginator->firstItem() ?? 0 }}-{{ $paginator->lastItem() ?? 0 }} dari {{ $paginator->total() }} data
        </div>

        <nav class="pagination" role="navigation" aria-label="Pagination Navigation">
            @if ($paginator->onFirstPage())
                <span class="page-link disabled" aria-disabled="true" aria-label="Previous">&laquo;</span>
            @else
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">&laquo;</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="page-link disabled" aria-disabled="true">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="page-link active" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">&raquo;</a>
            @else
                <span class="page-link disabled" aria-disabled="true" aria-label="Next">&raquo;</span>
            @endif
        </nav>
    </div>
@endif
