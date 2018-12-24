<?php

	namespace Core\Providers;

	use Gate;
	use Illuminate\Support\ServiceProvider;

	class AuthServiceProvider extends ServiceProvider
	{
		public function boot()
		{
			$this->policies();
			$this->define();
		}

		/**
		 * Register any application services.
		 *
		 * @return void
		 */
		public function register()
		{
			$this->registerConfig();
			$this->registerAlias();
			$this->registerMiddleware();
			$this->registerServices();
			$this->registerProviders();
		}

		/**
		 * Load config
		 */
		private function registerConfig()
		{
			$this->app->configure('auth');
			$this->app->configure('jwt');
		}

		/**
		 * Register Gate policy
		 */
		private function policies()
		{
			$policies = config('permission.policies')?:array();
			foreach ($policies as $key => $policy)
				Gate::policy($key, $policy);
		}

		/**
		 * Register Gate define
		 */
		private function define()
		{
			$defines = config('permission.defines')?:array();
			foreach ($defines as $key => $define)
				Gate::define($key, $define);
		}

		/**
		 * Load alias
		 */
		private function registerAlias()
		{
			$aliases = [
				'JWTAuth' => \Tymon\JWTAuth\Facades\JWTAuth::class,
				'JWTFactory' => \Tymon\JWTAuth\Facades\JWTFactory::class,
				'AuthService' => \Core\Services\Auth\AuthFacade::class,
			];

			foreach ($aliases as $key => $value) {
				class_alias($value, $key);
			}
		}

		/**
		 * Register middleware
		 */
		private function registerMiddleware()
		{
			$this->app->routeMiddleware([
				'api.jwt' => \Core\Http\Middleware\JwtMiddleware::class,
				'api.auth' => \Core\Http\Middleware\AuthenticateMiddleware::class,
				'can' => \Illuminate\Auth\Middleware\Authorize::class,
			]);
		}

		/**
		 * Register Services
		 */
		private function registerServices()
		{
			/**
			 * Service User Auth
			 */
			$this->app->bind('service.auth', 'Core\Services\Auth\AuthService');
		}

		/**
		 * Register providers dependency
		 */
		private function registerProviders()
		{
			$this->app->register(\Tymon\JWTAuth\Providers\LumenServiceProvider::class);
		}
	}
