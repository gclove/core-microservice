<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 27/12/18
	 * Time: 14.27
	 */

	namespace Core\Providers;

	use Illuminate\Support\ServiceProvider;

	class BaseServiceProvider extends ServiceProvider
	{
		protected $register = array(
			'configs' => [],
			'alias' => [],
			'providers' => [],
			'middleware' => [],
			'routeMiddleware' => [],
			'services' => [],
			'console' => [],
		);

		protected $sync = array();

		protected function init(string $key, array $data, bool $sync = false): void
		{
			if (!array_key_exists($key, $this->register))
				return;

			//add data
			$this->register[$key] = $data;

			//register sync
			if (true === $sync)
				$this->_lunch($key, true);
		}

		/**
		 * @param string $method
		 * @param bool   $writeSync
		 */
		private function _lunch(string $method, bool $writeSync = false): void
		{
			$writeSync ? $this->sync[$method] = true : NULL;

			$method = 'register' . ucfirst($method);
			$this->{$method}();
		}

		protected function run()
		{
			foreach ($this->register as $key => $value)
				if (NULL != $value && !array_key_exists($key, $this->sync))
					$this->_lunch($key);
		}

		/**
		 * Register config providers
		 */
		protected function registerConfigs(): void
		{
			if ('Lumen' === framework())
				foreach ($this->register['configs'] as $config)
					is_string($config) ? $this->app->configure($config) : NULL;
		}

		/**
		 * Register alias from array in config providers
		 */
		protected function registerAlias(): void
		{
			foreach ($this->register['alias'] as $key => $value)
				class_alias($value, $key);
		}

		/**
		 * Register providers from array in config providers
		 */
		protected function registerProviders(): void
		{
			foreach ($this->register['providers'] as $key => $provider)
				$this->app->register($provider);
		}

		/**
		 * Register services
		 */
		protected function registerServices(): void
		{
			foreach ($this->register['services'] as $key => $service)
				$this->app->{$service['type']}($key, $service['class']); //type: singleton or bind
		}

		/**
		 * Register middleware from array in config providers
		 */
		protected function registerMiddleware(): void
		{
			$this->app->middleware($this->register['middleware']);

		}

		/**
		 * Register middleware from array in config providers
		 */
		protected function registerRouteMiddleware(): void
		{
			if ('Lumen' === framework())
				$this->app->routeMiddleware($this->register['routeMiddleware']);
		}

		/**
		 * Register console commands
		 */
		protected function registerConsole(): void
		{
			foreach ($this->register['console'] as $value)
				$this->commands($value);
		}
	}