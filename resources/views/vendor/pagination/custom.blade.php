@if ($paginator->hasPages())
    <nav class="custom-pagination-wrapper" role="navigation" aria-label="Pagination Navigation">
        <div class="custom-pagination">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="page-btn disabled">‹</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="page-btn" rel="prev">‹</a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- Dots --}}
                @if (is_string($element))
                    <span class="page-btn dots">{{ $element }}</span>
                @endif

                {{-- Page Numbers --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="page-btn active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="page-btn" rel="next">›</a>
            @else
                <span class="page-btn disabled">›</span>
            @endif

        </div>
    </nav>
@endif