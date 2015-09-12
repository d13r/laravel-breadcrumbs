@if ($breadcrumbs)
    <ul>
        @foreach ($breadcrumbs as $i => $breadcrumb)
            <li id="breadcrumbs__item-{{$i}}" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"
                @if(!$breadcrumb->last)
                    itemref="breadcrumbs__item-{{$i + 1}}"
                @endif

                @if(!$breadcrumb->first)
                    itemprop="child"
                @endif

                @if($breadcrumb->last)
                    class="breadcrumbs__selected"
                @endif
            >
                <a href="{{$breadcrumb->url}}" itemprop="url">
                    <span itemprop="title">{{$breadcrumb->title}}</span>
                </a>
        @endforeach
    </ul>
@endif
