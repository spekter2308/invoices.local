<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class StatementServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\StatementServiceInterface', 'App\Services\StatementService');
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
