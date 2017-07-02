<?php

namespace DaveJamesMiller\Breadcrumbs\Exceptions;

use DaveJamesMiller\Breadcrumbs\BreadcrumbsException;

/**
 * Exception that is thrown if the user attempts to register two breadcrumbs with the same name.
 *
 * @see \DaveJamesMiller\Breadcrumbs\BreadcrumbsManager::register()
 */
class DuplicateBreadcrumbException extends BreadcrumbsException
{
}
