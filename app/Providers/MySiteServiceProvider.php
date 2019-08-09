<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class MySiteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     * 
     * Bootstrap the application services
     *
     * @return void
     */
    public function register()
    {
        App::bind('mysiteclass', function()
        {
            return new \App\Helpers\MySiteClass;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
