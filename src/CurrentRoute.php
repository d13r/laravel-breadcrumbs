<?php

namespace DaveJamesMiller\Breadcrumbs;

use DaveJamesMiller\Breadcrumbs\Exceptions\UnnamedRouteException;
use Illuminate\Routing\Router;

/**
 * Class representing the current route.
 */
class CurrentRoute
{
    /**
     * @var Router The Laravel Router instance for the current request.
     */
    protected $router;

    /**
     * @var array The manually set current route.
     */
    protected $route;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Get the current route name and parameters.
     *
     * This may be the route set manually with the set() method, but normally is the route retrieved from the Laravel
     * Router.
     *
     * #### Example
     * ```php
     * list($name, $params) = $currentRouter->get();
     * ```
     *
     * @return array A two-element array consisting of the route name (string) and any parameters (array).
     * @throws UnnamedRouteException if the current route doesn't have an associated name.
     */
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

    /**
     * Set the current route name and parameters.
     *
     * @param string $name   Route name.
     * @param array  $params Optional array of parameters.
     */
    public function set(string $name, array $params = []) //: void
    {
        $this->route = [$name, $params];
    }

    /**
     * Clear the current route name.
     *
     * Next time `get()` is called it will revert to the default behaviour of reading from the Router.
     */
    public function clear() //: void
    {
        $this->route = null;
    }
}
