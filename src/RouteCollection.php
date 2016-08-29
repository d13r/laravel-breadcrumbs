<?php

namespace DaveJamesMiller\Breadcrumbs;

use Illuminate\Routing\Route;
use Illuminate\Support\Collection;

class RouteCollection extends Collection
{

    /**
     * RouteCollection constructor.
     *
     * @param \Illuminate\Routing\RouteCollection $collection
     */
    public function __construct(\Illuminate\Routing\RouteCollection $collection)
    {
        $items = $this->parseRoutes($collection);

        parent::__construct($items);
    }

    private function parseRoutes($collection)
    {
        $result = [];

        foreach ($collection as $route) {
            if($info = $this->getRouteInformation($route)) {
                $result[] = $info;
            }
        }

        return $result;
    }

    protected function getRouteInformation(Route $route)
    {
        $name = $route->getName();
        $breadcrumb = array_get($route->getAction(), 'breadcrumb', null);

        if ($breadcrumb === null) {
            return null;
        }

        return compact('name', 'breadcrumb');
    }
}