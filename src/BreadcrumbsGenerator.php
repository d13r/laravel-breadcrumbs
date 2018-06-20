<?php

namespace DaveJamesMiller\Breadcrumbs;

use DaveJamesMiller\Breadcrumbs\Exceptions\InvalidBreadcrumbException;
use Illuminate\Support\Collection;

/**
 * Generate a set of breadcrumbs for a page.
 *
 * This is passed as the first parameter to all breadcrumb-generating closures. In the documentation it is named
 * `$breadcrumbs`.
 */
class BreadcrumbsGenerator
{
    /**
     * @var Collection Breadcrumbs currently being generated.
     */
    protected $breadcrumbs;

    /**
     * @var array The registered breadcrumb-generating callbacks.
     */
    protected $callbacks = [];

    /**
     * Generate breadcrumbs.
     *
     * @param array $callbacks The registered breadcrumb-generating closures.
     * @param array $before The registered 'before' callbacks.
     * @param array $after The registered 'after' callbacks.
     * @param string $name The name of the current page.
     * @param array $params The parameters to pass to the closure for the current page.
     * @return Collection The generated breadcrumbs.
     * @throws InvalidBreadcrumbException if the name is (or any ancestor names are) not registered.
     */
    public function generate(array $callbacks, array $before, array $after, string $name, array $params): Collection
    {
        $this->breadcrumbs = new Collection;
        $this->callbacks   = $callbacks;

        foreach ($before as $callback) {
            $callback($this);
        }

        $this->call($name, $params);

        foreach ($after as $callback) {
            $callback($this);
        }

        return $this->breadcrumbs;
    }

    /**
     * Call the closure to generate breadcrumbs for a page.
     *
     * @param string $name The name of the page.
     * @param array $params The parameters to pass to the closure.
     * @throws InvalidBreadcrumbException if the name is not registered.
     */
    protected function call(string $name, array $params): void
    {
        if (! isset($this->callbacks[ $name ])) {
            throw new InvalidBreadcrumbException("Breadcrumb not found with name \"{$name}\"");
        }

        $this->callbacks[$name]($this, ...$params);
    }

    /**
     * Add breadcrumbs for a parent page.
     *
     * Should be called from the closure for a page, before `push()` is called.
     *
     * @param string $name The name of the parent page.
     * @param array ...$params The parameters to pass to the closure.
     * @throws InvalidBreadcrumbException
     */
    public function parent(string $name, ...$params): void
    {
        $this->call($name, $params);
    }

    /**
     * Add a breadcrumb.
     *
     * Should be called from the closure for each page. May be called more than once.
     *
     * @param string $title The title of the page.
     * @param string|null $url The URL of the page.
     * @param array $data Optional associative array of additional data to pass to the view.
     */
    public function push(string $title, string $url = null, array $data = []): void
    {
        $this->breadcrumbs->push((object) array_merge($data, compact('title', 'url')));
    }
}
