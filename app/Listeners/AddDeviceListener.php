<?php

namespace App\Listeners;

use App\Events\AddDevice;
use App\Models\Analytic;
use App\Models\GardenDevice;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddDeviceListener extends Listener
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
    public function handle(AddDevice $event): void
    {
        $user_id = $event->garden_device->garden->user_id ?? null;
        if (! $user_id) {
            $this->log('ERROR AddDeviceListener : User not found - device : ' . json_encode($event->garden_device));
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
            $total = is_int($total) ? $total + 1 : 1;
            $total_devices->data = ['value' => $total];
            $total_devices->save();
        }

        // total_user_devices
        $total_user_devices = Analytic::where('type', '=', 'total_user_devices')->where('analyticable_type', '=', User::class)->where('analyticable_id', '=', $user_id)->first();

        if (! $total_user_devices) {
            // count devices
            $total_user_devices = GardenDevice::whereHas('garden', function ($query) use ($user_id) {
                $query->where('user_id', '=', $user_id);
            })->count();

            $total_user_devices = Analytic::create([
                'type' => 'total_user_devices',
                'data' => [
                    'value' => $total_user_devices,
                ],
                'analyticable_type' => User::class,
                'analyticable_id' => $user_id,
            ]);
        } else {
            $total = (int) $total_user_devices->data['value'];
            $total = is_int($total) && $total >= 0 ? $total + 1 : 1;
            $total_user_devices->data = ['value' => $total];
            $total_user_devices->save();
        }
    }
}
