<?php

namespace App\Listeners;

use App\Events\SubDevice;
use App\Models\Analytic;
use App\Models\GardenDevice;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SubDeviceListener extends Listener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SubDevice $event): void
    {
        $user_id = $event->garden_device->garden->user_id ?? null;
        if (! $user_id) {
            $this->log('ERROR SubDeviceListener : User not found - device : ' . json_encode($event->garden_device));
            return;
        }
        
        $total_devices = Analytic::where('type', '=', 'total_devices')->first();
        
        if (! $total_devices) {
            // count projects
            $total_devices = GardenDevice::count();

            $total_devices = Analytic::create([
                'type' => 'total_devices',
                'data' => [
                    'value' => $total_devices,
                ],
            ]);
        } else {
            $total = (int) $total_devices->data['value'];
            $total = is_int($total) && $total > 1 ? $total - 1 : 0;
            $total_devices->data = ['value' => $total];
            $total_devices->save();
        }

        // total_user_devices
        $total_user_devices = Analytic::where('type', '=', 'total_user_devices')->where('analyticable_type', '=', User::class)->where('analyticable_id', '=', $user_id)->first();

        if (! $total_user_devices) {
            // count devices
            $total_user_devices = GardenDevice::where('garden.user_id', '=', $user_id)->count();

            $total_user_devices = Analytic::create([
                'type' => 'total_user_devices',
                'data' => [
                    'value' => $total_user_devices,
                ],
            ]);
        }
    }
}
