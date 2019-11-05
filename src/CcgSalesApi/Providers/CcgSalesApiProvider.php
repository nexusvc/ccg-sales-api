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
        $this->app->alias('Ccg', CCG::class );
    }   
}
