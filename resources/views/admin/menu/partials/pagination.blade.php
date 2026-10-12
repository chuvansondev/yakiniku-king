@if ($paginator->hasPages())
    <nav class="admin-list-pagination" aria-label="{{ __('admin_lists.pagination') }}">
        <p class="admin-list-pagination-summary">
            {{ __('admin_lists.showing') }} {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}
            {{ __('admin_lists.of') }} {{ $paginator->total() }}
        </p>

        <div class="admin-list-pagination-links">
            @if ($paginator->onFirstPage())
                <span class="is-disabled" aria-disabled="true">{{ __('admin_lists.previous') }}</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev">{{ __('admin_lists.previous') }}</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="admin-list-pagination-ellipsis" aria-hidden="true">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page === $paginator->currentPage())
                            <span class="is-current" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next">{{ __('admin_lists.next') }}</a>
            @else
                <span class="is-disabled" aria-disabled="true">{{ __('admin_lists.next') }}</span>
            @endif
        </div>
    </nav>
@endif
