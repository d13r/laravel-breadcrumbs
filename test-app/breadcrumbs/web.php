<?php

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsManager;

Breadcrumbs::macro('pageTitle', function () {
    $current = Breadcrumbs::current();
    $title = $current ? "{$current->title} – " : '';

    $page = (int) request('page');
    if ($page > 1) {
        $title .= "Page $page – ";
    }

    return $title . 'Laravel Breadcrumbs Test';
});

Breadcrumbs::before(function (BreadcrumbsGenerator $trail) {
    $trail->push('Before');
});

Breadcrumbs::for('home', function (BreadcrumbsGenerator $trail) {
    $trail->push('Home', route('home'), ['custom' => 'Custom data for Home']);
});

Breadcrumbs::for('blog', function (BreadcrumbsGenerator $trail) {
    $trail->parent('home');
    $trail->push('Blog', route('blog'));
});

Breadcrumbs::for('category', function (BreadcrumbsGenerator $trail, $category) {
    $trail->parent('blog');

    foreach ($category->ancestors as $ancestor) {
        $trail->push($ancestor->title, route('category', $ancestor->id));
    }

    $trail->push($category->title, route('category', $category->id));
});

Breadcrumbs::for('post', function (BreadcrumbsGenerator $trail, $post) {
    $trail->parent('category', $post->category);
    $trail->push($post->title, route('post', $post->id), ['image' => asset($post->image)]);
});

Breadcrumbs::for('text', function (BreadcrumbsGenerator $trail) {
    $trail->parent('home');
    $trail->push('Text 1', null, ['custom' => 'Custom data for Text 1']);
    $trail->push('Text 2');
    $trail->push('Text 3');
});

// Make sure $breadcrumbs is set too
/** @var BreadcrumbsManager $breadcrumbs */
$breadcrumbs->for('section-test', function (BreadcrumbsGenerator $trail) {
    $trail->parent('home');
    $trail->push('@section() Test', route('section'));
});

Breadcrumbs::for('server-error', function (BreadcrumbsGenerator $trail) {
    $trail->parent('home');
    $trail->push('Internal Server Error Test');
});

Breadcrumbs::for('errors.404', function (BreadcrumbsGenerator $trail) {
    $trail->parent('home');
    $trail->push('Page Not Found');
});

Breadcrumbs::after(function (BreadcrumbsGenerator $trail) {
    $page = (int) request('page', 1);
    if ($page > 1) {
        $trail->push("Page $page", null, ['current' => false]);
    }
});
