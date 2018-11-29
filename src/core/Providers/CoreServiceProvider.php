<?php

	namespace Core\Providers;

	use Illuminate\Support\ServiceProvider;

	class CoreServiceProvider extends ServiceProvider
	{
		/**
		 * Register any application services.
		 *
		 * @return void
		 */
		public function register()
		{
			$this->registerConfig();
			$this->registerAlias();
			$this->registerSystem();
			$this->registerServices();
			$this->registerMiddleware();
			$this->registerProviders();
			$this->registerConsole();
		}

		/**
		 * Bootstrap publishes
		 *
		 * @return void
		 */
		protected function registerConsole()
		{
			$this->commands(\Core\Console\Commands\PublishCommand::class);
			$this->commands(\Core\Console\Commands\CreateProvider::class);
			$this->commands(\Core\Console\Commands\CreateTransformer::class);
			$this->commands(\Core\Console\Commands\CreateApiController::class);
			$this->commands(\Core\Console\Commands\CreateRepositroy::class);
		}

		/**
		 * Register system providers Kernel/Console/Filesystem etc..
		 */
		protected function registerSystem()
		{
			$this->app->singleton('filesystem', function ($app) {
				return $app->loadComponent(
					'filesystems',
					\Illuminate\Filesystem\FilesystemServiceProvider::class,
					'filesystem'
				);
			});

			$this->app->singleton(
				\Illuminate\Contracts\Console\Kernel::class,
				\App\Console\Kernel::class
			);

			$this->app->singleton(
				\Illuminate\Contracts\Debug\ExceptionHandler::class,
				\ResponseHTTP\Response\Laravel\Exceptions\Handler::class
			);
		}

		/**
		 * Register Services
		 */
		protected function registerServices()
		{
			/**
			 * Service Api
			 */
			$this->app->singleton('service.api', 'Core\Services\Api\ApiService');

			/**
			 * Service ACL
			 */
			$this->app->singleton('service.acl', 'Core\Services\ACL\ACLService');

			/**
			 * Service Log
			 */
			$this->app->singleton('service.log', 'Core\Services\Log\LogService');
		}

		/**
		 * Register middleware
		 */
		protected function registerMiddleware()
		{
			$this->app->routeMiddleware([
				'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
				'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,

				//middleware check if auth user is owner and can use routes
				'owner' => [
					'id' => \Core\Http\Middleware\OwnerMiddleware::class, //Check id route with id auth user
				],
			]);
		}

		/**
		 * Register providers dependency
		 */
		protected function registerProviders(){
			$this->app->register(\ResponseHTTP\Response\Laravel\Providers\HttpServiceProvider::class);
			$this->app->register(\CacheSystem\CacheServiceProvider::class);
			$this->app->register(\Spatie\Permission\PermissionServiceProvider::class);
			$this->app->register(\Aws\Laravel\AwsServiceProvider::class);
			$this->app->register(\Folklore\GraphQL\LumenServiceProvider::class);
			$this->app->register(\Illuminate\Filesystem\FilesystemServiceProvider::class);
		}

		/**
		 * Load alias
		 */
		protected function registerAlias()
		{
			$aliases=[
				'ApiService' => \Core\Services\Api\ApiFacade::class,
				'AclService' => \Core\Services\ACL\ACLFacade::class,
				'LogService' => \Core\Services\Log\LogFacade::class
			];

			foreach ($aliases as $key => $value){
				class_alias($value, $key);
			}
		}

		/**
		 * Load config
		 */
		protected function registerConfig() {
			$this->app->configure('cache');
			$this->app->configure('database');
			$this->app->configure('filesystems');
			$this->app->configure('permission');
		}
	}
