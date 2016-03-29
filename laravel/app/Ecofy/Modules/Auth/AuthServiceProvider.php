<?php

namespace App\Ecofy\Modules\Auth;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    //protected $defer = true;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Ecofy\Modules\Auth\AuthServiceContract', function(){
            return new \App\Ecofy\Modules\Auth\AuthService();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
       return ['App\Ecofy\Modules\Auth\AuthServiceContract'];
    }
}
