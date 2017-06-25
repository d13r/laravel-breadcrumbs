<?php

namespace DaveJamesMiller\Breadcrumbs;

class Manager
{
    protected $currentRoute;
    protected $generator;
    protected $view;

    protected $callbacks = [];
    protected $viewName;
    protected $currentRouteManual;

    public function __construct(CurrentRoute $currentRoute, Generator $generator, View $view)
    {
        $this->generator    = $generator;
        $this->currentRoute = $currentRoute;
        $this->view         = $view;
    }

    public function register(string $name, callable $callback) //: void
    {
        if (isset($this->callbacks[ $name ])) {
            throw new Exception("Breadcrumb name \"{$name}\" has already been registered");
        }

        $this->callbacks[ $name ] = $callback;
    }

    public function exists(string $name = null): bool
    {
        if (is_null($name)) {
            try {
                list($name) = $this->currentRoute->get();
            } catch (Exception $e) {
                return false;
            }
        }

        return isset($this->callbacks[ $name ]);
    }

    public function generate(string $name = null, ...$params): array
    {
        if ($name === null) {
            list($name, $params) = $this->currentRoute->get();
        }

        return $this->generator->generate($this->callbacks, $name, $params);
    }

    public function generateArray(string $name, array $params = []): array
    {
        return $this->generator->generate($this->callbacks, $name, $params);
    }

    public function generateIfExists(string $name = null, ...$params): array
    {
        if ($name === null) {
            try {
                list($name, $params) = $this->currentRoute->get();
            } catch (Exception $e) {
                return [];
            }
        }

        if (! $this->exists($name)) {
            return [];
        }

        return $this->generator->generate($this->callbacks, $name, $params);
    }

    public function generateIfExistsArray(string $name, array $params = []): array
    {
        if (! $this->exists($name)) {
            return [];
        }

        return $this->generator->generate($this->callbacks, $name, $params);
    }

    public function render(string $name = null, ...$params): string
    {
        if ($name === null) {
            list($name, $params) = $this->currentRoute->get();
        }

        $breadcrumbs = $this->generator->generate($this->callbacks, $name, $params);

        return $this->view->render($this->viewName, $breadcrumbs);
    }

    public function renderArray(string $name, array $params = []): string
    {
        $breadcrumbs = $this->generator->generate($this->callbacks, $name, $params);

        return $this->view->render($this->viewName, $breadcrumbs);
    }

    public function renderIfExists(string $name = null, ...$params): string
    {
        if ($name === null) {
            try {
                list($name, $params) = $this->currentRoute->get();
            } catch (Exception $e) {
                return '';
            }
        }

        if (! $this->exists($name)) {
            return '';
        }

        $breadcrumbs = $this->generator->generate($this->callbacks, $name, $params);

        return $this->view->render($this->viewName, $breadcrumbs);
    }

    public function renderIfExistsArray(string $name, array $params = []): string
    {
        if (! $this->exists($name)) {
            return '';
        }

        $breadcrumbs = $this->generator->generate($this->callbacks, $name, $params);

        return $this->view->render($this->viewName, $breadcrumbs);
    }

    public function setCurrentRoute(string $name, ...$params) //: void
    {
        $this->currentRoute->set($name, $params);
    }

    public function setCurrentRouteArray(string $name, array $params = []) //: void
    {
        $this->currentRoute->set($name, $params);
    }

    public function clearCurrentRoute() //: void
    {
        $this->currentRoute->clear();
    }

    public function setView($view) //: void
    {
        $this->viewName = $view;
    }
}
