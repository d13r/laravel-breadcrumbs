<?php

namespace DaveJamesMiller\Breadcrumbs;

use Closure;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use function Opis\Closure\unserialize;

class BreadcrumbsMiddleware
{
    /**
     * @param Request     $request
     * @param Closure     $next
     * @param string|null $serialize
     *
     * @return mixed
     * @throws \Throwable
     */
    public function handle(Request $request, Closure $next)
    {
        collect(RouteFacade::getRoutes()->getIterator())
            ->filter(function (Route $route) {
                return array_key_exists(self::class, $route->defaults);
            })
            ->each(function (Route $route) {
                $serialize = $route->defaults[self::class];
                $callback = unserialize($serialize);

                Breadcrumbs::for($route->getName(), $callback);
            });


        $request->route()->forgetParameter(self::class);

        return $next($request);
    }

}
