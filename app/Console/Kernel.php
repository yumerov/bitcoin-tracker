<?php

// phpcs:disable Squiz.WhiteSpace.ObjectOperatorSpacing.Before

namespace App\Console;

use App\Console\Commands\PriceSync;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * Holds registered commands
     *
     * @var string[]
     */
    protected $commands = [
        PriceSync::class,
    ];


    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule
            ->command('price:sync')
            ->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
