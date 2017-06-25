<?php

namespace DaveJamesMiller\Breadcrumbs;

use DaveJamesMiller\Breadcrumbs\Exceptions\UnnamedRouteException;
use Illuminate\Routing\Router;

class CurrentRoute
{
    protected $route;
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function get(): array
    {
        // Manually set route
        if ($this->route) {
            return $this->route;
        }

        // Determine the current route
        /** @var Router $route */
        $route = $this->router->current();

        // No current route
        if (is_null($route)) {
            return ['', []];
        }

        // Convert route to name
        $name = $route->getName();

        if (is_null($name)) {
            $uri = array_first($route->methods()) . ' /' . $route->uri();

            throw new UnnamedRouteException("The current route ($uri) is not named");
        }

        // Get the current route parameters
        $params = array_values($route->parameters());

        return [$name, $params];
    }

    public function set(string $name, array $params) //: void
    {
        $this->route = [$name, $params];
    }

    public function clear() //: void
    {
        $this->route = null;
    }
}
