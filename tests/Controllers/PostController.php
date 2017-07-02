<?php

namespace BreadcrumbsTests\Controllers;

use Breadcrumbs;
use BreadcrumbsTests\Models\Post;

class PostController
{
    public function edit(Post $post)
    {
        return Breadcrumbs::render();
    }
}
