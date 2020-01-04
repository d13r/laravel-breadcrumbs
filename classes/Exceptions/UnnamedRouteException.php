<?php

namespace DaveJamesMiller\Breadcrumbs\Exceptions;

use DaveJamesMiller\Breadcrumbs\BreadcrumbsException;
use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;
use Illuminate\Routing\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

/**
 * Exception that is thrown if the user attempt to render breadcrumbs for the current route but the current route
 * doesn't have a name.
 */
class UnnamedRouteException extends BreadcrumbsException implements ProvidesSolution
{
    /**
     * @var Route
     */
    private $route;

    public function __construct(Route $route)
    {
        $uri = Arr::first($route->methods()) . ' /' . ltrim($route->uri(), '/');

        parent::__construct("The current route ($uri) is not named");

        $this->route = $route;
    }

    public function getSolution(): Solution
    {
        $method = strtolower(Arr::first($this->route->methods()));
        $uri = $this->route->uri();
        $action = $this->route->getActionName();

        if ($action === '\Illuminate\Routing\ViewController') {
            $method = 'view';
            $action = "'" . ($this->route->defaults['view'] ?? 'view-name') . "'";
        } elseif ($action === 'Closure') {
            $action = "function() {\n    ...\n}";
        } else {
            $action = "'" . Str::replaceFirst(App::getNamespace() . 'Http\Controllers\\', '', $action) . "'";
        }

        $links = [];
        $links['Route-bound breadcrumbs'] = 'https://github.com/davejamesmiller/laravel-breadcrumbs#route-bound-breadcrumbs';
        $links['Silencing breadcrumb exceptions'] = 'https://github.com/davejamesmiller/laravel-breadcrumbs#configuration-file';
        $links['Laravel Breadcrumbs documentation'] = 'https://github.com/davejamesmiller/laravel-breadcrumbs#laravel-breadcrumbs';

        return BaseSolution::create('Give the route a name')
            ->setSolutionDescription("For example:


```php
Route::$method('$uri', $action)->name('sample-name');
```")
            ->setDocumentationLinks($links);
    }
}
