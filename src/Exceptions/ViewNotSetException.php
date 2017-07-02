<?php

namespace DaveJamesMiller\Breadcrumbs\Exceptions;

use DaveJamesMiller\Breadcrumbs\BreadcrumbsException;

/**
 * Exception that is thrown if the user attempts to render breadcrumbs without setting a view.
 */
class ViewNotSetException extends BreadcrumbsException
{
}
