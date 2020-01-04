<?php

namespace DaveJamesMiller\Breadcrumbs\Exceptions;

use DaveJamesMiller\Breadcrumbs\BreadcrumbsException;
use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/**
 * Exception that is thrown if the user attempts to generate breadcrumbs for a page that is not registered.
 */
class InvalidBreadcrumbException extends BreadcrumbsException implements ProvidesSolution
{
    private $name;
    private $isRouteBound = false;

    public function __construct($name)
    {
        parent::__construct("Breadcrumb not found with name \"{$name}\"");

        $this->name = $name;
    }

    public function setIsRouteBound()
    {
        $this->isRouteBound = true;
    }

    public function getSolution(): Solution
    {
        // Determine the breadcrumbs file name
        $files = (array)config('breadcrumbs.files');

        if (count($files) === 1) {
            $file = Str::replaceFirst(base_path() . DIRECTORY_SEPARATOR, '', $files[0]);
        } else {
            $file = 'one of the files defined in config/breadcrumbs.php';
        }

        // Determine the current route name
        $route = Route::current();
        $routeName = $route ? $route->getName() : null;
        if ($routeName) {
            $url = "route('{$this->name}')";
        } else {
            $url = "url('" . Request::path() . "')";
        }

        $links = [];
        $links['Defining breadcrumbs'] = 'https://github.com/davejamesmiller/laravel-breadcrumbs#defining-breadcrumbs';

        if ($this->isRouteBound) {
            $links['Route-bound breadcrumbs'] = 'https://github.com/davejamesmiller/laravel-breadcrumbs#route-bound-breadcrumbs';
        }

        $links['Silencing breadcrumb exceptions'] = 'https://github.com/davejamesmiller/laravel-breadcrumbs#configuration-file';
        $links['Laravel Breadcrumbs documentation'] = 'https://github.com/davejamesmiller/laravel-breadcrumbs#laravel-breadcrumbs';

        return BaseSolution::create("Add this to $file")
            ->setSolutionDescription("
```php
Breadcrumbs::for('{$this->name}', function (\$trail) {
    \$trail->push('Title Here', $url);
});
```")
            ->setDocumentationLinks($links);
    }
}
