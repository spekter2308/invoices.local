<?php

namespace App\Providers;

use App\Company;
use App\Customer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        //\View::composer('*', function ($view) {
            /*$companies = \Cache::rememberForever('companies', function () {
                return Company::get();
            });
            $customers = \Cache::rememberForever('customers', function () {
                return Customer::get();
            });*/
            /*$companies = \Session('companies', function () {
                return Company::get();
            });
            $customers = \Session('customers', function () {
                return Customer::get();
            });

            $view->with([
                'companies' => $companies,
                'customers' => $customers
            ]);
        });*/
    }
}
