@if (is_array($listNavigation))
    @if (count($listNavigation) > 0)
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);"
            aria-label="breadcrumb" class="d-print-none">
            <ol class="breadcrumb">
                @foreach ($listNavigation as $index => $item)
                    <li
                        class="breadcrumb-item {{ $index == 0 ? 'ms-auto' : '' }} {{ $index + 1 >= count($listNavigation) ? 'active' : '' }}">
                        <a href="{{ $item['url'] }}">
                            {!! $item['label'] !!}
                        </a>
                    </li>
                @endforeach
            </ol>
        </nav>
    @endif
@endif
