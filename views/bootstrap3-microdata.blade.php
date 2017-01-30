@if ($breadcrumbs)
	<ol itemscope="itemscope" itemtype="http://schema.org/BreadcrumbList" class="breadcrumb">
		@foreach ($breadcrumbs as $breadcrumb)
			@if ($breadcrumb->url && !$breadcrumb->last)
				<li itemprop="itemListElement" itemscope="itemscope" itemtype="http://schema.org/ListItem"><a itemprop="item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
			@else
				<li itemprop="itemListElement" itemscope="itemscope" itemtype="http://schema.org/ListItem" class="active">{{ $breadcrumb->title }}</li>
			@endif
		@endforeach
	</ol>
@endif
