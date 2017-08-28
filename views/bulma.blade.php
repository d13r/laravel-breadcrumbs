@if (count($breadcrumbs))
  <nav class="breadcrumb" aria-label="breadcrumbs">
    <ul>
      @foreach ($breadcrumbs as $breadcrumb)
        @if ($breadcrumb->url && !$loop->last)
          <li><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
        @elseif ($breadcrumb->url && $loop->last)
          <li class="is-active"><a href="{{ $breadcrumb->url }}" aria-current="page">{{ $breadcrumb->title }}</a></li>
        @else
          <li class="is-active"><a href="#" aria-current="page">{{ $breadcrumb->title }}</a></li>
        @endif
      @endforeach
    </ul>
  </nav>
@endif
