<?php

namespace DaveJamesMiller\Breadcrumbs;

use DaveJamesMiller\Breadcrumbs\Exceptions\InvalidViewException;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Support\HtmlString;

/**
 * Class that renders views.
 *
 * This is an abstraction over Laravel's `View::make()` for easier unit testing.
 */
class View
{
    /**
     * @var ViewFactory The Laravel view factory instance.
     */
    protected $factory;

    public function __construct(ViewFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Render a view with the given breadcrumbs.
     *
     * @param string $view        The view name.
     * @param array  $breadcrumbs The generated breadcrumbs.
     * @return HtmlString The generated HTML.
     * @throws InvalidViewException if no view has been set.
     */
    public function render(string $view, array $breadcrumbs): HtmlString
    {
        if (! $view) {
            throw new InvalidViewException('Breadcrumbs view not specified (check config/breadcrumbs.php)');
        }

        $html = $this->factory->make($view, compact('breadcrumbs'))->render();

        return new HtmlString($html);
    }
}
