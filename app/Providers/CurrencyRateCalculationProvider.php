<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\Services\CurrencyRateCalculation;

class CurrencyRateCalculationProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Library\Services\CurrencyRateCalculation', function ($app) {
            return new CurrencyRateCalculation();
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
