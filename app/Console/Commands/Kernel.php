<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\ProcessCommissions::class,
        \App\Console\Commands\CheckExpiredTransactions::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('transactions:check-expired')
            ->everyTwoMinutes() 
            ->onFailure(function () {
                \Log::error('Failed to run transactions:check-expired command.');
            })
            ->onSuccess(function () {
                \Log::info('Successfully ran transactions:check-expired command.');
            }); 
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}