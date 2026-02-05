<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    // app/Console/Kernel.php

    protected function schedule(Schedule $schedule): void
    {
        // ...
        $schedule->command('pedidos:confirmar-entregues')->dailyAt('02:00'); // Ou a cada 12 horas, etc.
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    protected $commands = [
        \App\Console\Commands\TestPagSeguroV4Real::class,
    ];
}
