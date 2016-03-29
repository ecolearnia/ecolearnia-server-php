<?php

namespace App\Ecofy\Modules\Account;

use Illuminate\Support\ServiceProvider;

class AccountServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Ecofy\Modules\Account\AccountServiceContract', function(){
            return new \App\Ecofy\Modules\Account\AccountService();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
       return ['App\Ecofy\Modules\Account\AccountServiceContract'];
    }
}
