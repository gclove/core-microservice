<?php
	return [
		/*
		|--------------------------------------------------------------------------
		| Providers
		|--------------------------------------------------------------------------
		|
		| Providers to register in app (lumen or laravel)
		|
		*/
		'providers' => Folklore\GraphQL\LumenServiceProvider::class,

		/*
		|--------------------------------------------------------------------------
		| Model and Contracts
		|--------------------------------------------------------------------------
		|
		| Model: load Type graphQL ('name' => PathClass::class)
		| Contracts: load Contracts type of graphQL ('name' => PathClass::class)
		|
		 */
		'model' => [],

		'contracts' => [
			'PaginationMeta' =>  Core\Http\GraphQL\Type\PaginationMetaType::class,
			'Timestamp' =>  Core\Http\GraphQL\Type\TimestampType::class,
		],
	];