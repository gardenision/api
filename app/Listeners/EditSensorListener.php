<?php

namespace App\Listeners;

use App\Events\EditSensor;
use App\Models\Analytic;
use App\Models\GardenDeviceModule;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EditSensorListener extends Listener
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
    public function handle(EditSensor $event): void
    {
        $sensor = $event->garden_device_module;

        $updated_data = $event->updated_data;

        $user_id = $sensor->garden_device->garden->user_id ?? null;

        if (! $user_id) {
            $this->log('ERROR EditSensorListener : User not found - sensor : ' . json_encode($sensor));
            return;
        }

        if ($updated_data['is_active'] !== $sensor->is_active) {
            $total_active_sensors = Analytic::where('type', '=', 'total_active_sensors')->first();

            if (! $total_active_sensors) {
                // count active sensors
                $total_active_sensors = GardenDeviceModule::where('is_active', true)->whereHas('module', function ($query) {
                    $query->where('type', '=', 'sensor');
                })->count();

                $total_active_sensors = Analytic::create([
                    'type' => 'total_active_sensors',
                    'data' => [
                        'value' => $total_active_sensors,
                    ],
                ]);
            }

            $total_inactive_sensors = Analytic::where('type', '=', 'total_inactive_sensors')->first();
            
            if (! $total_inactive_sensors) {
                $total_inactive_sensors = GardenDeviceModule::where('is_active', false)->whereHas('module', function ($query) {
                    $query->where('type', '=', 'sensor');
                })->count();

                $total_inactive_sensors = Analytic::create([
                    'type' => 'total_inactive_sensors',
                    'data' => [
                        'value' => $total_inactive_sensors,
                    ],
                ]);
            }

            $total_user_active_sensors = Analytic::where('type', '=', 'total_user_active_sensors')->where('analyticable_type', '=', User::class)->where('analyticable_id', '=', $user_id)->first();

            if (! $total_user_active_sensors) {
                // count active sensors
                $total_user_active_sensors = GardenDeviceModule::where('is_active', true)
                ->where('garden_device.garden', function ($query) use ($user_id) {
                    $query->where('user_id', '=', $user_id);
                })
                ->whereHas('module', function ($query) {
                    $query->where('type', '=', 'sensor');
                })
                ->count();

                $total_user_active_sensors = Analytic::create([
                    'type' => 'total_user_active_sensors',
                    'data' => [
                        'value' => $total_user_active_sensors,
                    ],
                    'analyticable_type' => User::class,
                    'analyticable_id' => $user_id,
                ]);
            }

            $total_user_inactive_sensors = Analytic::where('type', '=', 'total_user_inactive_sensors')->where('analyticable_type', '=', User::class)->where('analyticable_id', '=', $user_id)->first();

            if (! $total_user_inactive_sensors) {
                // count inactive sensors
                $total_user_inactive_sensors = GardenDeviceModule::where('is_active', false)
                ->whereHas('garden_device.garden', function ($query) use ($user_id) {
                    $query->where('user_id', '=', $user_id);
                })
                ->whereHas('module', function ($query) {
                    $query->where('type', '=', 'sensor');
                })
                ->count();

                $total_user_inactive_sensors = Analytic::create([
                    'type' => 'total_user_inactive_sensors',
                    'data' => [
                        'value' => $total_user_inactive_sensors,
                    ],
                    'analyticable_type' => User::class,
                    'analyticable_id' => $user_id,
                ]);
            }

            if ($updated_data['is_active']) {
                $total_active_sensors->data = ['value' => (int) $total_active_sensors->data['value'] + 1];
                $total_inactive_sensors->data = ['value' => (int) $total_inactive_sensors->data['value'] - 1];

                $total_user_active_sensors->data = ['value' => (int) $total_user_active_sensors->data['value'] + 1];
                $total_user_inactive_sensors->data = ['value' => (int) $total_user_inactive_sensors->data['value'] - 1];
            } else {
                $total_active_sensors->data = ['value' => (int) $total_active_sensors->data['value'] - 1];
                $total_inactive_sensors->data = ['value' => (int) $total_inactive_sensors->data['value'] + 1];

                $total_user_active_sensors->data = ['value' => (int) $total_user_active_sensors->data['value'] - 1];
                $total_user_inactive_sensors->data = ['value' => (int) $total_user_inactive_sensors->data['value'] + 1];
            }
            
            $total_active_sensors->save();
            $total_inactive_sensors->save();

            $total_user_active_sensors->save();
            $total_user_inactive_sensors->save();
        }
    }
}
