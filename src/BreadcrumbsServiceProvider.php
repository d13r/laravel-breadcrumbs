<?php

namespace DaveJamesMiller\Breadcrumbs;

use Illuminate\Support\ServiceProvider;

/**
 * The Laravel service provider, which registers, configures and bootstraps the package.
 */
class BreadcrumbsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        // Load the default config values
        $this->mergeConfigFrom($this->getConfigFilePath(), 'breadcrumbs');

        // Register Manager class singleton with the app container
        $this->app->singleton(BreadcrumbsManager::class, config('breadcrumbs.manager-class'));

        // Register Generator class so it can be overridden
        $this->app->bind(BreadcrumbsGenerator::class, config('breadcrumbs.generator-class'));
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(): void
    {
        // Load the routes/breadcrumbs.php file
        $this->loadBreadcrumbs();

        // Publish the config/breadcrumbs.php file
        $this->publishes([$this->getConfigFilePath() => config_path('breadcrumbs.php')], 'config');

        // Register 'breadcrumbs::' view namespace
        $this->loadViewsFrom(__DIR__ . '/../views/', 'breadcrumbs');
    }

    /**
     * Get the classes provided for deferred loading.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            BreadcrumbsManager::class,
        ];
    }

    /**
     * Load the routes/breadcrumbs.php file (if it exists) which registers available breadcrumbs.
     *
     * This method can be overridden in a child class. It is called by the boot() method, which Laravel calls
     * automatically when bootstrapping the application.
     *
     * @return void
     */
    protected function loadBreadcrumbs(): void
    {
        // Load the routes/breadcrumbs.php file, or other configured file(s)
        $files = config('breadcrumbs.files');

        if (! $files) {
            return;
        }

        // If it is set to the default value and that file doesn't exist, skip loading it rather than causing an error
        if ($files === base_path('routes/breadcrumbs.php') && ! is_file($files)) {
            return;
        }

        // Support both Breadcrumbs:: and $breadcrumbs-> syntax by making $breadcrumbs variable available
        /** @noinspection PhpUnusedLocalVariableInspection */
        $breadcrumbs = $this->app->make(BreadcrumbsManager::class);

        // Support both a single string filename and an array of filenames (e.g. returned by glob())
        foreach ((array) $files as $file) {
            require $file;
        }
    }

    /**
     * Get the config file path.
     *
     * @return string
     */
    private function getConfigFilePath()
    {
        return __DIR__ . '/../config/breadcrumbs.php';
    }
}
