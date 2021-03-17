<?php

namespace App\Providers;

use App\Charts\Miners\Payouts\AllPayoutsChart;
use App\Charts\Miners\Payouts\MyPayoutsChart;
use App\Charts\MinerType\PriceHistoryChart;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use ConsoleTVs\Charts\Registrar as Charts;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Charts $charts)
    {
        Schema::defaultStringLength(191);

        $charts->register([
            AllPayoutsChart::class,
            MyPayoutsChart::class,
            PriceHistoryChart::class,
        ]);
    }
}
