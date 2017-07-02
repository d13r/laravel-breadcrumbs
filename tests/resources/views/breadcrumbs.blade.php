@if (count($breadcrumbs))

    <ol>
        @foreach ($breadcrumbs as $breadcrumb)

            @if ($loop->last)
                <li class="current">{{ $breadcrumb->title }}</li>
            @elseif ($breadcrumb->url)
                <li><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
            @else
                <li>{{ $breadcrumb->title }}</li>
            @endif

        @endforeach
    </ol>

@else

    <p>No breadcrumbs</p>

@endif
