<?php

namespace DaveJamesMiller\Breadcrumbs;

use DaveJamesMiller\Breadcrumbs\Exceptions\InvalidBreadcrumbException;

/**
 * Generate a set of breadcrumbs for a page.
 *
 * This is passed as the first parameter to all breadcrumb-generating closures. In the documentation it is named
 * `$breadcrumbs`.
 */
class Generator
{
    /**
     * @var array Breadcrumbs currently being generated.
     */
    protected $breadcrumbs = [];

    /**
     * @var array The registered breadcrumb-generating callbacks.
     */
    protected $callbacks = [];

    /**
     * Generate breadcrumbs.
     *
     * @param array  $callbacks The registered breadcrumb-generating closures.
     * @param string $name      The name of the current page.
     * @param array  $params    The parameters to pass to the closure for the current page.
     * @return array An array of breadcrumbs.
     * @throws InvalidBreadcrumbException if the name is (or any ancestor names are) not registered.
     */
    public function generate(array $callbacks, string $name, array $params): array
    {
        $this->breadcrumbs = [];
        $this->callbacks   = $callbacks;

        $this->call($name, $params);

        return $this->toArray();
    }

    /**
     * Call the closure to generate breadcrumbs for a page.
     *
     * @param string $name   The name of the page.
     * @param array  $params The parameters to pass to the closure.
     * @throws InvalidBreadcrumbException if the name is not registered.
     */
    protected function call(string $name, array $params) //: void
    {
        if (! isset($this->callbacks[ $name ])) {
            throw new InvalidBreadcrumbException("Breadcrumb not found with name \"{$name}\"");
        }

        array_unshift($params, $this);

        call_user_func_array($this->callbacks[ $name ], $params);
    }

    /**
     * Add breadcrumbs for a parent page.
     *
     * Should be called from the closure for a page, before `push()` is called.
     *
     * @param string $name      The name of the parent page.
     * @param array  ...$params The parameters to pass to the closure.
     * @throws InvalidBreadcrumbException
     */
    public function parent(string $name, ...$params) //: void
    {
        $this->call($name, $params);
    }

    /**
     * Add breadcrumbs for a parent page, with an array of parameters.
     *
     * Should be called from the closure for a page, before `push()` is called.
     *
     * @param string $name   The name of the parent page.
     * @param array  $params The parameters to pass to the closure.
     * @throws InvalidBreadcrumbException
     */
    public function parentArray(string $name, array $params) //: void
    {
        $this->call($name, $params);
    }

    /**
     * Add a breadcrumb.
     *
     * Should be called from the closure for each page. May be called more than once.
     *
     * @param string      $title The title of the page.
     * @param string|null $url   The URL of the page.
     * @param array       $data  Optional associative array of additional data to pass to the view.
     */
    public function push(string $title, string $url = null, array $data = []) //: void
    {
        $this->breadcrumbs[] = (object) array_merge($data, [
            'title' => $title,
            'url'   => $url,
            // These will be altered later where necessary:
            'first' => false,
            'last'  => false,
        ]);
    }

    /**
     * Fetch the generated breadcrumbs array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $breadcrumbs = $this->breadcrumbs;

        // Add first & last indicators
        if ($breadcrumbs) {
            $breadcrumbs[0]->first                        = true;
            $breadcrumbs[ count($breadcrumbs) - 1 ]->last = true;
        }

        return $breadcrumbs;
    }
}
