<?php

	namespace Core\Providers\Core;

	use Core\Providers\BaseServiceProvider;
	use Gate;

	class AuthServiceProvider extends BaseServiceProvider
	{
		public function boot()
		{
			$this->policies();
			$this->define();
		}

		/**
		 * Register Gate policy
		 */
		private function policies()
		{
			$policies = config('permission.policies') ?: array();
			foreach ($policies as $key => $policy)
				Gate::policy($key, $policy);
		}

		/**
		 * Register Gate define
		 */
		private function define()
		{
			$defines = config('permission.defines') ?: array();
			foreach ($defines as $key => $define)
				Gate::define($key, $define);
		}

		/**
		 * Register any application services.
		 *
		 * @return void
		 */
		public function register()
		{
			$this->init('configs',array('auth', 'jwt'),true);

			$this->init('alias',array(
				'JWTAuth' => \Tymon\JWTAuth\Facades\JWTAuth::class,
				'JWTFactory' => \Tymon\JWTAuth\Facades\JWTFactory::class,
				'AuthService' => \Core\Services\Auth\AuthFacade::class,
			));

			$this->init('providers', config('auth.service_providers')?:array());

			$this->init('services',array('service.auth' => ['class' => 'Core\Services\Auth\AuthService', 'type' => 'bind']));

			$this->init('routeMiddleware',array(
				'api.jwt' => \Core\Http\Middleware\JwtMiddleware::class,
				'api.auth' => \Core\Http\Middleware\AuthenticateMiddleware::class,
				'can' => \Illuminate\Auth\Middleware\Authorize::class,
			));

			$this->run();
		}
	}
