@if ($breadcrumbs)
    <nav>
        <div class="nav-wrapper">
            <div class="col s12">
                @foreach ($breadcrumbs as $breadcrumb)
                    @if ($breadcrumb->url && !$breadcrumb->last)
                        <a href="{{ $breadcrumb->url }}" class="breadcrumb">
                            {{ $breadcrumb->title }}
                        </a>
                    @else
                        <a href="#" class="breadcrumb">
                            {{ $breadcrumb->title }}
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </nav>
@endif