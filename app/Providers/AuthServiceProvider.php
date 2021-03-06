<?php

namespace App\Providers;

use App\Company;
use App\Customer;
use App\Invoice;
use App\InvoiceItemName;
use App\Policies\CustomerPolice;
use App\Policies\InvoiceItemPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\ProfilePolice;
use App\Policies\UserPolicy;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Invoice::class => InvoicePolicy::class,
        Company::class => ProfilePolice::class,
        Customer::class => CustomerPolice::class,
        InvoiceItemName::class => InvoiceItemPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //Passport::routes();
    }
}
