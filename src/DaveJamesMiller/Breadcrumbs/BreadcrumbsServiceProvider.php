<?php namespace DaveJamesMiller\Breadcrumbs;

use Illuminate\Support\ServiceProvider;

class BreadcrumbsServiceProvider extends ServiceProvider {

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('breadcrumbs');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['breadcrumbs'] = $this->app->share(function($app)
		{
			$breadcrumbs = new BreadcrumbsManager($app['view']);

			$breadcrumbs->setView($app['config']['laravel-breadcrumbs::view']);

			return $breadcrumbs;
		});
	}

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot() {
		// Register the package so the default view can be loaded
		$this->package('davejamesmiller/laravel-breadcrumbs');

		// Load the app breadcrumbs if they're in app/breadcrumbs.php
		if (file_exists($file = $this->app['path'].'/breadcrumbs.php'))
			require $file;
	}

}
