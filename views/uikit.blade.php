@if (count($breadcrumbs))
        <ul class="uk-breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)
            <li>
                @if ($breadcrumb->url && !$loop->last)
                    <a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
                @else
                    <span>{{ $breadcrumb->title }}</span>
                @endif
            </li>
        @endforeach
    </ul>
@endif
