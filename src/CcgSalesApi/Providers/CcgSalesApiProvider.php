<?php

namespace Nexusvc\CcgSalesApi\Providers;

use Illuminate\Support\ServiceProvider;
use Nexusvc\CcgSalesApi\CCG;

class CcgSalesApiProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('CCG', function($app) {
            return new CCG;
        });
    } 

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('ccg');
    }
}
