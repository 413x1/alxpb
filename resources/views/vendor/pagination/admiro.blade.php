@if ($paginator->hasPages())
    @section('datatable-css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/theme/css/vendors/js-datatables/style.css') }}">
    @endsection

    <div class="datatable-bottom">
        <div class="datatable-info">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} entries
        </div>

        <nav class="datatable-pagination">
            <ul class="datatable-pagination-list">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="datatable-pagination-list-item datatable-hidden datatable-disabled">
                        <a data-page="1" class="datatable-pagination-list-item-link">&lt;</a>
                    </li>
                @else
                    <li class="datatable-pagination-list-item">
                        <a data-page="{{ $paginator->currentPage() - 1 }}"
                           href="{{ $paginator->previousPageUrl() }}"
                           class="datatable-pagination-list-item-link">&lt;</a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="datatable-pagination-list-item datatable-disabled">
                            <a class="datatable-pagination-list-item-link">{{ $element }}</a>
                        </li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="datatable-pagination-list-item datatable-active">
                                    <a data-page="{{ $page }}" class="datatable-pagination-list-item-link">{{ $page }}</a>
                                </li>
                            @else
                                <li class="datatable-pagination-list-item">
                                    <a data-page="{{ $page }}"
                                       href="{{ $url }}"
                                       class="datatable-pagination-list-item-link">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="datatable-pagination-list-item">
                        <a data-page="{{ $paginator->currentPage() + 1 }}"
                           href="{{ $paginator->nextPageUrl() }}"
                           class="datatable-pagination-list-item-link">&gt;</a>
                    </li>
                @else
                    <li class="datatable-pagination-list-item datatable-hidden datatable-disabled">
                        <a data-page="{{ $paginator->lastPage() }}"
                           class="datatable-pagination-list-item-link">&gt;</a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif
