## Core Microservice Laravel / Lumen
![](https://img.shields.io/badge/version-1.0.1--beta-green.svg)

#### Description
>This package implements all those basic services that any microservice or php application should have: JWT authentication management (for REST API), ACL management, and REST standar responses, cache system files and redis, internal communications status between controller, simplified logger, resources manager and so on.

#### Let's go
    
    composer require fabrizio-cafolla/core-microservice
    
    php artisan microservice:publish
    
**Laravel**
    
###### Add provider in array 'providers' (config/app.php)
    
    Core\Providers\ManagerServiceProvider::class
    
###### Add to (config/auth.php)   

    'service_providers' => [
        'jwt' =>  env('AUTH_PROVIDERS', Tymon\JWTAuth\Providers\LaravelServiceProvider::class)
    ],
    
    'guards' => [
        ...
        
        'api' => [
            'provider' => 'jwt',
            'driver' => 'jwt',
        ],
    ],
    		
    'providers' => [
        ...
        
        'jwt' => [
                'driver' => 'eloquent',
                'model' => env('AUTH_MODEL', App\Models\User::class),
            ],
    ]
    
###### Add to .env file
    
    AUTH_PROVIDERS=Tymon\JWTAuth\Providers\LaravelServiceProvider
    
###### Add namespace of package middleware in App\Http\Kernel.php file: $routeMiddleware
    
    'api.jwt' => \Core\Http\Middleware\JwtMiddleware::class,
    'api.auth' => \Core\Http\Middleware\AuthenticateMiddleware::class,
    
**Lumen**

###### File bootstrap/app.php
    
Add after $app = new Lar... etc

    $app->instance('path.config', app()->basePath() . DIRECTORY_SEPARATOR . 'config');
    $app->instance('path.storage', app()->basePath() . DIRECTORY_SEPARATOR . 'storage');
    
Uncomment

    $app->withFacades();
    $app->withEloquent();
    
Register providers

	$app->register(Core\Providers\ManagerServiceProvider::class);
	
###### Add to .env file (Or change config/auth.php key of array 'providers')

    AUTH_PROVIDERS=Tymon\JWTAuth\Providers\LumenServiceProvider
    
  