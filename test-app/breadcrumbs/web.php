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

Breadcrumbs::before(function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('Before');
});

Breadcrumbs::register('home', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('Home', route('home'), ['custom' => 'Custom data for Home']);
});

Breadcrumbs::register('blog', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Blog', route('blog'));
});

Breadcrumbs::register('category', function (BreadcrumbsGenerator $breadcrumbs, $category) {
    $breadcrumbs->parent('blog');

    foreach ($category->ancestors as $ancestor) {
        $breadcrumbs->push($ancestor->title, route('category', $ancestor->id));
    }

    $breadcrumbs->push($category->title, route('category', $category->id));
});

Breadcrumbs::register('post', function (BreadcrumbsGenerator $breadcrumbs, $post) {
    $breadcrumbs->parent('category', $post->category);
    $breadcrumbs->push($post->title, route('post', $post->id), ['image' => asset($post->image)]);
});

Breadcrumbs::register('text', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Text 1', null, ['custom' => 'Custom data for Text 1']);
    $breadcrumbs->push('Text 2');
    $breadcrumbs->push('Text 3');
});

// Make sure $breadcrumbs is set too
/** @var BreadcrumbsManager $breadcrumbs */
$breadcrumbs->register('section-test', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('@section() Test', route('section'));
});

Breadcrumbs::register('server-error', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Internal Server Error Test');
});

Breadcrumbs::register('errors.404', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Page Not Found');
});

Breadcrumbs::after(function (BreadcrumbsGenerator $breadcrumbs) {
    $page = (int) request('page', 1);
    if ($page > 1) {
        $breadcrumbs->push("Page $page", null, ['current' => false]);
    }
});
