<?php

namespace App\Console\Commands;

use App\Models\Restaurant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OpenScheduledRestaurants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restaurants:open-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Open all active restaurants at scheduled time (8 AM)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Only open restaurants that are active (approved)
        // and not suspended
        $count = Restaurant::where('status', 'active')
            ->where('is_open', false)
            ->update(['is_open' => true]);

        // Clear restaurant cache so customers see updated status
        // Cache::tags(['restaurants'])->flush();

        $this->info("Opened {$count} restaurants.");
        Log::info("Scheduled: Opened {$count} restaurants at 8 AM.");
    }
}
