<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Daftarkan semua scheduled commands.
     */
    protected function schedule(Schedule $schedule): void
    {
        // ✅ Auto-cancel orders after 5 minutes if not accepted by seller
        $schedule->command('order:auto-cancel')->everyFiveMinutes();
    }

    /**
     * Daftarkan semua commands.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
