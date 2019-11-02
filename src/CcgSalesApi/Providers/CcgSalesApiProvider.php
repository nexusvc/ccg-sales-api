<?php

namespace Nexusvc\CcgSalesApi\Providers;

use Illuminate\Support\ServiceProvider;
use Nexusvc\CcgSalesApi\CcgSalesApi;

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
        $this->app->alias('Ccg', CcgSalesApi::class );
    }   
}
