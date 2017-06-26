<?php

namespace DaveJamesMiller\Breadcrumbs\Exceptions;

use DaveJamesMiller\Breadcrumbs\Exception;

/**
 * Exception that is thrown if the user attempts to register two breadcrumbs with the same name.
 *
 * @see \DaveJamesMiller\Breadcrumbs\Manager::register()
 */
class DuplicateBreadcrumbException extends Exception
{
}
