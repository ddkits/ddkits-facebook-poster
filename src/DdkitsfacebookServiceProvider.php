<?php

namespace Ddkits\Ddkitsfacebook;

use Illuminate\Support\ServiceProvider;

class DdkitsfacebookServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/ddkitsconfig.php', 'ddkitsfacebook'
        );
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'ddkitsfacebook');
        $this->publishes([
            __DIR__.'/views' => resource_path('views/vendor/ddkitsfacebook'),
            __DIR__.'/config/ddkitsconfig.php' => base_path('config/ddkitsconfig.php'),
        ]);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->make('Ddkits\Ddkitsfacebook\Controllers\DdkitsfacebookCont');
        $this->mergeConfigFrom(
            __DIR__.'/config/ddkitsconfig.php', 'ddkitsfacebook'
        );
    }
}
