<?php

namespace DaveJamesMiller\Breadcrumbs;

// Not available until Laravel 5.8
//use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Route;
use function Opis\Closure\serialize;

/**
 * The Laravel service provider, which registers, configures and bootstraps the package.
 */
class BreadcrumbsServiceProvider extends ServiceProvider //implements DeferrableProvider
{
    public function isDeferred()
    {
        // Remove this and uncomment DeferrableProvider after dropping support
        // for Laravel 5.7 and below
        return true;
    }

    /**
     * Get the services provided for deferred loading.
     *
     * @return array
     */
    public function provides(): array
    {
        return [BreadcrumbsManager::class];
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        // Load the default config values
        $this->mergeConfigFrom(__DIR__ . '/../config/breadcrumbs.php', 'breadcrumbs');

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
        // Register 'breadcrumbs::' view namespace
        $this->loadViewsFrom(__DIR__ . '/../views/', 'breadcrumbs');

        // Publish the config/breadcrumbs.php file
        $this->publishes([
            __DIR__ . '/../config/breadcrumbs.php' => config_path('breadcrumbs.php'),
        ], 'breadcrumbs-config');

        // Load the routes/breadcrumbs.php file
        $this->registerBreadcrumbs();

        if (! Route::hasMacro('breadcrumbs')) {
            Route::macro('breadcrumbs', function (callable $closure) {
                /* @var Router $this */
                $this->defaults(BreadcrumbsMiddleware::class, serialize($closure));

                return $this;
            });
        }

    }

    /**
     * Load the routes/breadcrumbs.php file (if it exists) which registers available breadcrumbs.
     *
     * This method can be overridden in a child class. It is called by the boot() method, which Laravel calls
     * automatically when bootstrapping the application.
     *
     * @return void
     */
    public function registerBreadcrumbs(): void
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
}
