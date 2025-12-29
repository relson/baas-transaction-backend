<?php

namespace App\Providers;

use L5Swagger\L5SwaggerServiceProvider;
use L5Swagger\Console\GenerateDocsCommand;
use L5Swagger\ConfigFactory;
use L5Swagger\GeneratorFactory;

class SwaggerServiceProvider extends L5SwaggerServiceProvider
{
    public function register()
    {
        $this->app->singleton(ConfigFactory::class, function ($app) {
            return new ConfigFactory($app->make('config'));
        });
    
        $this->app->singleton(GeneratorFactory::class, function ($app) {
            return new GeneratorFactory($app->make(ConfigFactory::class));
        });
    
        $this->commands(GenerateDocsCommand::class);
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Do nothing to prevent route registration
    }
}
