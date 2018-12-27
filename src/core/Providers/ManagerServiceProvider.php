<?php

	namespace Core\Providers;

	use Core\Providers\BaseServiceProvider;

	class ManagerServiceProvider extends BaseServiceProvider
	{
		/**
		 * Register any application services.
		 *
		 * @return void
		 */
		public function register()
		{
			$this->init('configs',array('providers'),true);
			$this->init('alias', config('providers.alias') ?: array());
			$this->init('providers',$this->providersManager());
			$this->init('middleware', config('providers.middleware') ?: array());
			$this->init('routeMiddleware', config('providers.route_middleware') ?: array());
			$this->init('console', array(
				\Core\Console\Commands\PublishConfig::class,
				\Core\Console\Commands\CreateProvider::class,
				\Core\Console\Commands\CreateTransformer::class,
				\Core\Console\Commands\CreateApiController::class,
				\Core\Console\Commands\CreateRepositroy::class,
			));

			$this->run();
		}

		private function providersManager(): array {
			$providers = config('providers.global') ? : array(
				'services' => \Core\Providers\Core\ServicesProvider::class,
				'auth' => \Core\Providers\Core\AuthServiceProvider::class,
			);

			if($mergedProviders = $this->addEnvProviders($providers))
				return $mergedProviders;

			return $providers;
		}
		/**
		 * Register providers only specific env from array in config providers
		 */
		private function addEnvProviders(array $providers): ?array
		{
			/**
			 * Load Develop Providers if env is 'develop'
			 */
			if (app()->environment('develop')) {
				$localProviders = config('providers.develop') ?: array();
				foreach ($localProviders as $localProvider) {
					$this->app->register($localProvider);
				}
				return array_merge($providers, $localProviders);
			}

			/**
			 * Load Staging Providers if env is 'staging'
			 */
			if (app()->environment('staging')) {
				$stagingProviders = config('providers.staging') ?: array();
				foreach ($stagingProviders as $stagingProvider) {
					$this->app->register($stagingProvider);
				}
				return array_merge($providers, $stagingProviders);
			}

			/**
			 * Load Production Providers if env is 'production'
			 */
			if (app()->environment('production')) {
				$productionProviders = config('providers.production') ?: array();
				foreach ($productionProviders as $productionProvider) {
					$this->app->register($productionProvider);
				}
				return array_merge($providers, $productionProviders);
			}

			return null;
		}
	}
