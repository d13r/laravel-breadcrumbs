<?php

namespace DaveJamesMiller\Breadcrumbs\Exceptions;

use DaveJamesMiller\Breadcrumbs\BreadcrumbsException;
use Facade\IgnitionContracts\Solution;
use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;
use Illuminate\Support\Str;

/**
 * Exception that is thrown if the user attempts to register two breadcrumbs with the same name.
 *
 * @see \DaveJamesMiller\Breadcrumbs\BreadcrumbsManager::register()
 */
class DuplicateBreadcrumbException extends BreadcrumbsException implements ProvidesSolution
{
    private $name;

    public function __construct($name)
    {
        parent::__construct("Breadcrumb name \"{$name}\" has already been registered");

        $this->name = $name;
    }

    public function getSolution(): Solution
    {
        // Determine the breadcrumbs file name(s)
        $files = (array)config('breadcrumbs.files');

        $basePath = base_path() . DIRECTORY_SEPARATOR;
        foreach ($files as &$file) {
            $file = Str::replaceFirst($basePath, '', $file);
        }

        if (count($files) === 1) {
            $description = "Look in `$files[0]` for multiple breadcrumbs named `{$this->name}`.";
        } else {
            $description = "Look in the following files for multiple breadcrumbs named `{$this->name}`:\n\n- `" . implode("`\n -`", $files) . '`';
        }

        $links = [];
        $links['Defining breadcrumbs'] = 'https://github.com/davejamesmiller/laravel-breadcrumbs#defining-breadcrumbs';
        $links['Laravel Breadcrumbs documentation'] = 'https://github.com/davejamesmiller/laravel-breadcrumbs#laravel-breadcrumbs';

        return BaseSolution::create('Remove the duplicate breadcrumb')
            ->setSolutionDescription($description)
            ->setDocumentationLinks($links);
    }
}
