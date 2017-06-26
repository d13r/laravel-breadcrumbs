<?php

namespace DaveJamesMiller\Breadcrumbs;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * The Laravel service provider, which registers, configures and bootstraps the package.
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Get the classes provided for deferred loading.
     *
     * @return array
     */
    public function provides(): array
    {
        return [Manager::class];
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() //: void
    {
        // Register Manager class singleton with the app container
        $this->app->singleton(Manager::class);

        $this->app->resolving(Manager::class, function (Manager $manager) {
            $manager->setView(config('breadcrumbs.view'));
        });

        // Load the default config values
        $configFile = __DIR__ . '/../config/breadcrumbs.php';
        $this->mergeConfigFrom($configFile, 'breadcrumbs');

        // Publish the config/breadcrumbs.php file
        $this->publishes([$configFile => config_path('breadcrumbs.php')], 'config');

        // Register 'breadcrumbs::' view namespace
        $this->loadViewsFrom(__DIR__ . '/../views/', 'breadcrumbs');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() //: void
    {
        // Load the routes/breadcrumbs.php file
        $this->registerBreadcrumbs();
    }

    /**
     * Load the routes/breadcrumbs.php file (if it exists) which registers available breadcrumbs.
     *
     * This method can be overridden in a child class. It is called by the boot() method, which Laravel calls
     * automatically when bootstrapping the application.
     *
     * @return void
     */
    public function registerBreadcrumbs() //: void
    {
        // Load the app breadcrumbs if they're in routes/breadcrumbs.php
        if (file_exists($file = base_path('routes/breadcrumbs.php'))) {
            require $file;
        }
    }
}
