<?php

namespace App\Console;

use App\Console\Commands\Bitcoin\UpdateMarketValue;
use App\Console\Commands\CloudAtCost\ImportBuildMachines;
use App\Console\Commands\CloudAtCost\ImportPrices;
use App\Console\Commands\CloudAtCost\ImportWalletBalances;
use App\Console\Commands\CloudAtCost\RetrieveMachines;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        UpdateMarketValue::class,
        ImportPrices::class,
        ImportBuildMachines::class,
        ImportWalletBalances::class,
        RetrieveMachines::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(UpdateMarketValue::class)
            ->everySixHours();
        $schedule->command(ImportPrices::class)
            ->hourly();
        $schedule->command(ImportBuildMachines::class)
            ->daily();
        $schedule->command(ImportWalletBalances::class)
            ->everySixHours();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
