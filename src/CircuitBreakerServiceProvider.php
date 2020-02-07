<?php

namespace Keepanitreel\CircuitBreaker;
use Illuminate\Support\ServiceProvider;

class CircuitBreakerServiceProvider extends ServiceProvider
{
    public function boot(){

       // $this->loadRoutesFrom(__DIR__.'/routes.php');
//this->publishes(__DIR__.'/database');
       $this->loadMigrationsFrom(__DIR__.'/../database');

    }

    public function register()
    {
        $this->app->singleton('Circuit', function ($app) {
            return new Circuit();
        });
    }

}
