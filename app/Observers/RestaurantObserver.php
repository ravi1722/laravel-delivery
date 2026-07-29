<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class RestaurantObserver
{
    /**
     * Handle the Restaurant "created" event.
     */
    public function created(Restaurant $restaurant): void
    {
        $this->logActivity($restaurant, 'created');
        // Cache::tags(['restaurants'])->flush();
    }

    /**
     * Handle the Restaurant "updated" event.
     */
    public function updated(Restaurant $restaurant): void
    {
        $this->logActivity($restaurant, 'updated', $restaurant->getOriginal());
        // Cache::tags(['restaurants'])->flush();
    }

    /**
     * Handle the Restaurant "deleted" event.
     */
    public function deleted(Restaurant $restaurant): void
    {
        $this->logActivity($restaurant, 'deleted');
        // Cache::tags(['restaurants'])->flush();
    }

    /**
     * Handle the Restaurant "restored" event.
     */
    public function restored(Restaurant $restaurant): void
    {
        //
    }

    /**
     * Handle the Restaurant "force deleted" event.
     */
    public function forceDeleted(Restaurant $restaurant): void
    {
        //
    }

    private function logActivity(Restaurant $restaurant, string $action, array $oldValues = []): void
    {
        ActivityLog::create([
            'model_type'   => Restaurant::class,
            'model_id'     => $restaurant->id,
            'action'       => $action,
            'old_values'   => !empty($oldValues) ? json_encode($oldValues) : null,
            'new_values'   => json_encode($restaurant->toArray()),
            'performed_by' => Auth::user()->id,
            'ip_address'   => request()->ip(),
        ]);
    }
}
