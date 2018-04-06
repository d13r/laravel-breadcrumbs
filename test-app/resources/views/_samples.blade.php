{{-- .title.is-4 class required for Bulma --}}

<h2 class="title is-4">Site home</h2>
{{ Breadcrumbs::render('home') }}

<h2 class="title is-4">Blog home</h2>
{{ Breadcrumbs::render('blog') }}

<h2 class="title is-4">Blog category 1</h2>
{{ Breadcrumbs::render('category', new App\Category(1)) }}

<h2 class="title is-4">Blog category 2</h2>
{{ Breadcrumbs::render('category', new App\Category(2)) }}

<h2 class="title is-4">Blog post 1</h2>
{{ Breadcrumbs::render('post', new App\Post(1)) }}

<h2 class="title is-4">Blog post 2</h2>
{{ Breadcrumbs::render('post', new App\Post(2)) }}

<h2 class="title is-4">Text</h2>
{{ Breadcrumbs::render('text') }}

<h2 class="title is-4">Invalid</h2>
{{ Breadcrumbs::render('invalid') }}
