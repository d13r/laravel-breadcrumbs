@if ($breadcrumbs)
    <ul class="breadcrumbs">
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($breadcrumb->url && !$breadcrumb->last)
                <li><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
            @else
                <li class="current"><a href="#">{{ $breadcrumb->title }}</a></li>
            @endif
        @endforeach
    </ul>
@endif