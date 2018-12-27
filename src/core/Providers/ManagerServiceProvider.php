<?php

	namespace Core\Providers;

	use Illuminate\Support\ServiceProvider;

	class ManagerServiceProvider extends ServiceProvider
	{
		private static $bootProviders = array(
			'core' => \Core\Providers\CoreServiceProvider::class,
			'auth' => \Core\Providers\AuthServiceProvider::class,
		);

		private static $globalProviders;

		/**
		 * Register any application services.
		 *
		 * @return void
		 */
		public function register()
		{
			$this->registerConfig();
			$this->registerAlias();
			$this->registerProviders();
			$this->registerMiddleware();
			$this->registerConsole();
		}

		/**
		 * Register config providers (or load bootProviders)
		 */
		protected function registerConfig(): void
		{
			$this->app->configure('providers');

			if ($global = config('providers.global')) {
				self::$globalProviders = $global;
			} else {
				self::$globalProviders = self::$bootProviders;
			}
		}

		/**
		 * Register providers from array in config providers
		 */
		protected function registerProviders(): void
		{
			/**
			 * Load Global Providers
			 */
			foreach (self::$globalProviders as $globalProvider)
				$this->app->register($globalProvider);

			$this->registerEnvProviders();
		}

		/**
		 * Register providers only specific env from array in config providers
		 */
		protected function registerEnvProviders()
		{
			/**
			 * Load Develop Providers if env is 'develop'
			 */
			if (app()->environment('develop')) {
				$localProviders = config('providers.develop') ?: array();
				foreach ($localProviders as $localProvider) {
					$this->app->register($localProvider);
				}
				unset($localProviders);
			}

			/**
			 * Load Staging Providers if env is 'staging'
			 */
			if (app()->environment('staging')) {
				$stagingProviders = config('providers.staging') ?: array();
				foreach ($stagingProviders as $stagingProvider) {
					$this->app->register($stagingProvider);
				}
				unset($stagingProviders);
			}

			/**
			 * Load Production Providers if env is 'production'
			 */
			if (app()->environment('production')) {
				$productionProviders = config('providers.production') ?: array();
				foreach ($productionProviders as $productionProvider) {
					$this->app->register($productionProvider);
				}
				unset($productionProviders);
			}
		}

		/**
		 * Register alias from array in config providers
		 */
		protected function registerAlias()
		{
			$aliases = config('providers.alias') ?: array();
			foreach ($aliases as $key => $value) {
				class_alias($value, $key);
			}
		}

		/**
		 * Register middleware from array in config providers
		 */
		protected function registerMiddleware()
		{
			$middlewares = config('providers.middlewares') ?: array();
			foreach ($middlewares as $middleware) {
				$this->app->middleware([
					$middleware,
				]);
			}

			$route_middlewares = config('providers.route_middlewares') ?: array();
			foreach ($route_middlewares as $key => $value) {
				$this->app->routeMiddleware([
					$key => $value,
				]);
			}
		}

		/**
		 * Register console commands
		 */
		protected function registerConsole()
		{
			$this->commands(\Core\Console\Commands\PublishConfig::class);
			$this->commands(\Core\Console\Commands\CreateProvider::class);
			$this->commands(\Core\Console\Commands\CreateTransformer::class);
			$this->commands(\Core\Console\Commands\CreateApiController::class);
			$this->commands(\Core\Console\Commands\CreateRepositroy::class);
		}
	}
