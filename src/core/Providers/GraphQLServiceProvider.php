<?php

	namespace Core\Providers;

	use GraphQL;
	use Illuminate\Support\ServiceProvider;

	class GraphQLServiceProvider extends ServiceProvider
	{
		/**
		 * Register any application services.
		 *
		 * @return void
		 */
		public function register()
		{
			$this->registerConfig();
			$this->registerProviders();
			$this->registerContractsQL();
			$this->registerTypeQL();
		}

		/**
		 * Load config
		 */
		protected function registerConfig()
		{
			$this->app->configure('graphql');
			$this->app->configure('graphql_type');
		}

		/**
		 * Register providers dependency
		 */
		protected function registerProviders()
		{
			$provider = config('graphql_type.providers');
			$this->app->register($provider);
		}

		/**
		 * Load Type of GraphQL without use config file
		 */
		protected function registerContractsQL()
		{
			$contracts = config('graphql_type.contracts')?:array();
			foreach ($contracts as $key => $contract) {
				GraphQL::addType($contract, $key);
			}
		}

		/**
		 * Load Type of GraphQL without use config file
		 */
		protected function registerTypeQL()
		{
			$models = config('graphql_type.model')?:array();
			foreach ($models as $key => $model) {
				GraphQL::addType($model, $key);
			}

			//load alias TypeRegistry
			class_alias(\Core\Http\GraphQL\TypeRegistry::class, 'TypeRegistry');
		}
	}
