<?php

namespace DaveJamesMiller\Breadcrumbs;

use DaveJamesMiller\Breadcrumbs\Exceptions\InvalidViewException;
use Illuminate\Contracts\View\Factory as ViewFactory;

class View
{
    protected $factory;

    public function __construct(ViewFactory $factory)
    {
        $this->factory = $factory;
    }

    public function render(string $view, array $breadcrumbs): string
    {
        if (! $view) {
            throw new InvalidViewException('Breadcrumbs view not specified (check config/breadcrumbs.php)');
        }

        return $this->factory->make($view, compact('breadcrumbs'))->render();
    }
}
