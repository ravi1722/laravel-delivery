<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote');

// Auto-expire old pending orders — every 30 minutes
Schedule::command('orders:expire-pending')->everyThirtyMinutes();

// Close restaurants at midnight automatically
Schedule::command('restaurants:close-all')->dailyAt('00:00');

// Open restaurants at 8am automatically
// Schedule::command('restaurants:open-scheduled')->dailyAt('08:00');

// Send daily report to admin — every morning at 7am
Schedule::command('reports:send-daily')->dailyAt('07:00');

// Clean old notifications — weekly
Schedule::command('notifications:prune --hours=720')->weekly();

// Clean old telescope entries — daily
Schedule::command('telescope:prune')->daily();