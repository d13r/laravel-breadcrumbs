<?php

namespace DaveJamesMiller\Breadcrumbs;

use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * Breadcrumbs facade - allows easy access to the Manager instance.
 *
 * @see Manager
 */
class Facade extends BaseFacade
{
    /**
     * Get the name of the class registered in the Application container.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return Manager::class;
    }
}
