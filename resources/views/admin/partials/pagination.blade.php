@if ($paginator->hasPages())
    <div class="d-flex align-items-center justify-content-end">
        <div class="me-3">
            {{ $paginator->firstItem() }} – {{ $paginator->lastItem() }} of {{ $paginator->total() }}
        </div>

        <nav role="navigation" aria-label="Pagination Navigation">
            <a
                href="{{ $paginator->previousPageUrl() }}"
                class="btn btn-link link-secondary{{ $paginator->onFirstPage() ? ' disabled' : '' }}"
            >
                <i class="bi bi-chevron-left"></i>
            </a>

            <a
                href="{{ $paginator->nextPageUrl() }}"
                class="btn btn-link link-secondary{{ $paginator->hasMorePages() ? '' : ' disabled' }}"
            >
                <i class="bi bi-chevron-right"></i>
            </a>
        </nav>
    </div>
@endif
