@if ($paginator->hasPages())
<nav aria-label="Page navigation">
    <ul class="tt-pagination-list">
        {{-- Previous Page --}}
        @if ($paginator->onFirstPage())
            <li class="tt-page-item disabled"><span class="tt-page-link"><i class="fas fa-chevron-left"></i></span></li>
        @else
            <li class="tt-page-item"><a class="tt-page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="fas fa-chevron-left"></i></a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="tt-page-item disabled"><span class="tt-page-link">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="tt-page-item active"><span class="tt-page-link">{{ $page }}</span></li>
                    @else
                        <li class="tt-page-item"><a class="tt-page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page --}}
        @if ($paginator->hasMorePages())
            <li class="tt-page-item"><a class="tt-page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="fas fa-chevron-right"></i></a></li>
        @else
            <li class="tt-page-item disabled"><span class="tt-page-link"><i class="fas fa-chevron-right"></i></span></li>
        @endif
    </ul>
</nav>
@endif
