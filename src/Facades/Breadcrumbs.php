<?php

namespace DaveJamesMiller\Breadcrumbs\Facades;

use DaveJamesMiller\Breadcrumbs\BreadcrumbsManager;
use Illuminate\Support\Facades\Facade;

/**
 * Breadcrumbs facade - allows easy access to the Manager instance.
 *
 * @see BreadcrumbsManager
 */
class Breadcrumbs extends Facade
{
    /**
     * Get the name of the class registered in the Application container.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return BreadcrumbsManager::class;
    }
}
