<nav aria-label="Page navigation" class="d-flex align-items-center justify-content-center">
    <ul class="pagination pagination-rounded justify-content-center">
        {{-- Previous Page Link --}}
        @if ($products->onFirstPage())
            <li class="page-item disabled"><a class="page-link">«</a></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $products->previousPageUrl() }}">«</a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($products->links()->elements[0] as $page => $url)
            @if ($page == $products->currentPage())
                <li class="page-item active"><a class="page-link">{{ $page }}</a></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($products->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $products->nextPageUrl() }}">»</a></li>
        @else
            <li class="page-item disabled"><a class="page-link">»</a></li>
        @endif
    </ul>
</nav>
