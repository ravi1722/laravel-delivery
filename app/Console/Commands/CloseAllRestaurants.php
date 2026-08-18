<?php

namespace App\Console\Commands;

use App\Models\Restaurant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CloseAllRestaurants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restaurants:close-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close all restaurants at midnight';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = Restaurant::where('is_open', true)->update(['is_open' => false]);
        // Clear restaurant cache
        // Cache::tags(['restaurants'])->flush();
        $this->info("Closed {$count} restaurants.");
        Log::info("Scheduled: Closed {$count} restaurants at midnight.");
    }
}
