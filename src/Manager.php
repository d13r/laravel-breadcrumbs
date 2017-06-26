<?php

namespace DaveJamesMiller\Breadcrumbs;

use DaveJamesMiller\Breadcrumbs\Exceptions\DuplicateBreadcrumbException;
use DaveJamesMiller\Breadcrumbs\Exceptions\InvalidBreadcrumbException;
use DaveJamesMiller\Breadcrumbs\Exceptions\InvalidViewException;
use DaveJamesMiller\Breadcrumbs\Exceptions\UnnamedRouteException;

/**
 * The main Breadcrumbs singleton class, responsible for registering, generating and rendering breadcrumbs.
 */
class Manager
{
    /**
     * @var CurrentRoute
     */
    protected $currentRoute;

    /**
     * @var Generator
     */
    protected $generator;

    /**
     * @var View
     */
    protected $view;

    /**
     * @var array The registered breadcrumb-generating callbacks.
     */
    protected $callbacks = [];

    /**
     * @var string The name of the view to be used by render().
     */
    protected $viewName;

    public function __construct(CurrentRoute $currentRoute, Generator $generator, View $view)
    {
        $this->generator    = $generator;
        $this->currentRoute = $currentRoute;
        $this->view         = $view;
    }

    /**
     * Register a breadcrumb-generating callback for a page.
     *
     * @param string   $name     The name of the page.
     * @param callable $callback The callback, which should accept a Generator instance as the first parameter and may
     *                           accept additional parameters.
     * @return void
     * @throws DuplicateBreadcrumbException If the given name has already been used.
     */
    public function register(string $name, callable $callback) //: void
    {
        if (isset($this->callbacks[ $name ])) {
            throw new DuplicateBreadcrumbException("Breadcrumb name \"{$name}\" has already been registered");
        }

        $this->callbacks[ $name ] = $callback;
    }

    /**
     * Check if a breadcrumb with the given name exists.
     *
     * If no name is given, defaults to the current route name.
     *
     * @param string|null $name The page name.
     * @return bool Whether there is a registered callback with that name.
     */
    public function exists(string $name = null): bool
    {
        if (is_null($name)) {
            try {
                list($name) = $this->currentRoute->get();
            } catch (UnnamedRouteException $e) {
                return false;
            }
        }

        return isset($this->callbacks[ $name ]);
    }

    /**
     * Generate a set of breadcrumbs for a page.
     *
     * @param string|null $name      The name of the current page.
     * @param mixed       ...$params The parameters to pass to the closure for the current page.
     * @return array The generated breadcrumbs.
     * @throws UnnamedRouteException if no name is given and the current route doesn't have an associated name.
     * @throws InvalidBreadcrumbException if the name is (or any ancestor names are) not registered.
     */
    public function generate(string $name = null, ...$params): array
    {
        if ($name === null) {
            list($name, $params) = $this->currentRoute->get();
        }

        return $this->generator->generate($this->callbacks, $name, $params);
    }

    /**
     * Generate a set of breadcrumbs for a page, with an array of parameters.
     *
     * @param string|null $name   The name of the current page.
     * @param array       $params The parameters to pass to the closure for the current page.
     * @return array The generated breadcrumbs.
     * @throws InvalidBreadcrumbException if the name is (or any ancestor names are) not registered.
     */
    public function generateArray(string $name, array $params = []): array
    {
        return $this->generator->generate($this->callbacks, $name, $params);
    }

    /**
     * Generate a set of breadcrumbs for a page.
     *
     * Returns an empty array if the page doesn't exist or the current route is unnamed, instead of throwing an
     * exception.
     *
     * @param string|null $name      The name of the current page.
     * @param mixed       ...$params The parameters to pass to the closure for the current page.
     * @return array The generated breadcrumbs.
     */
    public function generateIfExists(string $name = null, ...$params): array
    {
        if ($name === null) {
            try {
                list($name, $params) = $this->currentRoute->get();
            } catch (UnnamedRouteException $e) {
                return [];
            }
        }

        try {
            return $this->generator->generate($this->callbacks, $name, $params);
        } catch (InvalidBreadcrumbException $e) {
            return [];
        }
    }

    /**
     * Generate a set of breadcrumbs for a page, with an array of parameters.
     *
     * Returns an empty array if the page doesn't exist, instead of throwing an exception.
     *
     * @param string|null $name   The name of the current page.
     * @param array       $params The parameters to pass to the closure for the current page.
     * @return array The generated breadcrumbs.
     */
    public function generateIfExistsArray(string $name, array $params = []): array
    {
        try {
            return $this->generator->generate($this->callbacks, $name, $params);
        } catch (InvalidBreadcrumbException $e) {
            return [];
        }
    }

    /**
     * Render breadcrumbs for a page.
     *
     * @param string|null $name      The name of the current page.
     * @param mixed       ...$params The parameters to pass to the closure for the current page.
     * @return string The generated HTML.
     * @throws InvalidBreadcrumbException if the name is (or any ancestor names are) not registered.
     * @throws UnnamedRouteException if no name is given and the current route doesn't have an associated name.
     * @throws InvalidViewException if no view has been set.
     */
    public function render(string $name = null, ...$params): string
    {
        if ($name === null) {
            list($name, $params) = $this->currentRoute->get();
        }

        $breadcrumbs = $this->generator->generate($this->callbacks, $name, $params);

        return $this->view->render($this->viewName, $breadcrumbs);
    }

    /**
     * Render breadcrumbs for a page, with an array of parameters.
     *
     * @param string|null $name   The name of the current page.
     * @param array       $params The parameters to pass to the closure for the current page.
     * @return string The generated HTML.
     * @throws InvalidBreadcrumbException if the name is (or any ancestor names are) not registered.
     * @throws InvalidViewException if no view has been set.
     */
    public function renderArray(string $name, array $params = []): string
    {
        $breadcrumbs = $this->generator->generate($this->callbacks, $name, $params);

        return $this->view->render($this->viewName, $breadcrumbs);
    }

    /**
     * Render breadcrumbs for a page.
     *
     * Returns an empty string if the page doesn't exist or the current route is unnamed, instead of throwing an
     * exception.
     *
     * @param string|null $name      The name of the current page.
     * @param mixed       ...$params The parameters to pass to the closure for the current page.
     * @return string The generated HTML.
     * @throws InvalidViewException if no view has been set.
     */
    public function renderIfExists(string $name = null, ...$params): string
    {
        if ($name === null) {
            try {
                list($name, $params) = $this->currentRoute->get();
            } catch (UnnamedRouteException $e) {
                return '';
            }
        }

        try {
            $breadcrumbs = $this->generator->generate($this->callbacks, $name, $params);
        } catch (InvalidBreadcrumbException $e) {
            return '';
        }

        return $this->view->render($this->viewName, $breadcrumbs);
    }

    /**
     * Render breadcrumbs for a page, with an array of parameters.
     *
     * Returns an empty string if the page doesn't exist, instead of throwing an exception.
     *
     * @param string|null $name   The name of the current page.
     * @param array       $params The parameters to pass to the closure for the current page.
     * @return string The generated HTML.
     * @throws InvalidViewException if no view has been set.
     */
    public function renderIfExistsArray(string $name, array $params = []): string
    {
        try {
            $breadcrumbs = $this->generator->generate($this->callbacks, $name, $params);
        } catch (InvalidBreadcrumbException $e) {
            return '';
        }

        return $this->view->render($this->viewName, $breadcrumbs);
    }

    /**
     * Set the current route name and parameters to use when calling render() or generate() with no parameters.
     *
     * @param string $name      The name of the current page.
     * @param mixed  ...$params The parameters to pass to the closure for the current page.
     * @return void
     */
    public function setCurrentRoute(string $name, ...$params) //: void
    {
        $this->currentRoute->set($name, $params);
    }

    /**
     * Set the current route name and parameters to use when calling render() or generate() with no parameters, with an
     * array of parameters.
     *
     * @param string $name   The name of the current page.
     * @param array  $params The parameters to pass to the closure for the current page.
     * @return void
     */
    public function setCurrentRouteArray(string $name, array $params = []) //: void
    {
        $this->currentRoute->set($name, $params);
    }

    /**
     * Clear the previously set route name and parameters to use when calling render() or generate() with no parameters.
     *
     * Next time it will revert to the default behaviour of using the current route from Laravel.
     *
     * @return void
     */
    public function clearCurrentRoute() //: void
    {
        $this->currentRoute->clear();
    }

    /**
     * Set the view to use when calling render() (or related methods).
     *
     * @param string $view The view name.
     * @return void
     */
    public function setView(string $view) //: void
    {
        $this->viewName = $view;
    }
}
