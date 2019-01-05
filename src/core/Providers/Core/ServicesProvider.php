<?php

	namespace Core\Providers\Core;

	use Core\Providers\BaseServiceProvider;

	class ServicesProvider extends BaseServiceProvider
	{
		/**
		 * Register any application services.
		 *
		 * @return void
		 */
		public function register()
		{
			$this->init('configs',array('cache', 'database', 'filesystems', 'permission'),true);

			$this->init('alias',array(
				'ApiService' => \Core\Services\Api\ApiFacade::class,
				'AclService' => \Core\Services\ACL\ACLFacade::class,
				'LogService' => \Core\Services\Log\LogFacade::class,
				'ManagerService' => \Core\Services\Manager\ManagerFacade::class,
				'StatusService' => \Core\Services\Status\StatusFacade::class,
			));

			$this->init('providers',array(
				\ResponseHTTP\Response\Laravel\Providers\ResponseServiceProvider::class,
				\ResourcesManager\ResourcesManagerServiceProviders::class,
				\CacheSystem\CacheServiceProvider::class,
				\Spatie\Permission\PermissionServiceProvider::class,
				\Aws\Laravel\AwsServiceProvider::class,
				\Illuminate\Filesystem\FilesystemServiceProvider::class,
			));

			$this->init('services',array(
				'service.api' => ['class' => 'Core\Services\Api\ApiService', 'type' => 'singleton'],
				'service.acl' => ['class' => 'Core\Services\ACL\ACLService', 'type' => 'singleton'],
				'service.log' => ['class' => 'Core\Services\Log\LogService', 'type' => 'singleton'],
				'service.manager' => ['class' => 'Core\Services\Manager\ManagerService', 'type' => 'singleton'],
				'service.status' => ['class' => 'Core\Services\Status\StatusService', 'type' => 'singleton'],
			));

			$this->init('routeMiddleware',array(
				'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
				'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
				'owner.id' => \Core\Http\Middleware\OwnerMiddleware::class,
			));

			$this->run();
			$this->registerSystem();
		}

		/**
		 * Register system providers Kernel/Console/Filesystem etc..
		 */
		protected function registerSystem(): void
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

			if ($handler = config('providers.handler')) {
				$this->app->singleton(
					\Illuminate\Contracts\Debug\ExceptionHandler::class,
					$handler
				);
			}
		}
	}
