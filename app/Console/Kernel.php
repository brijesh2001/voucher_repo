<?php

namespace App\Console;

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
        //
        Commands\UpdateUnusedVoucher::class,
        Commands\InformVoucherOver::class,
        //Commands\UpdateInvoiceNumber::class
        Commands\ReferFriend::class,
        Commands\SendInvoice::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('update:unusedvoucher')
            ->everyTenMinutes();
        $schedule->command('inform:owner')
            ->everyThirtyMinutes();
        //$schedule->command('update:invoice')
          //  ->everyTenMinutes();
        //$schedule->command('send:referfriend')
           // ->dailyAt('10:00');
        $schedule->command('send:invoice')
            ->dailyAt('11:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
