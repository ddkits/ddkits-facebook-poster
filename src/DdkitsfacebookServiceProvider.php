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
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'ddkitsfacebook');
        // $this->publishes([
        //     __DIR__.'/views' => resource_path('views/ddkits/ddkitsfacebook'),
        // ]);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->make('Ddkits\Ddkitsfacebook\Controllers\DdkitsfacebookCont');
    }
}
