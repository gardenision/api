<?php

namespace App\Listeners;

use App\Events\SubActuator;
use App\Models\Analytic;
use App\Models\GardenDeviceModule;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SubActuatorListener extends Listener
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
    public function handle(SubActuator $event): void
    {
        $actuator = $event->garden_device_module;

        $user_id = $actuator->garden_device->garden->user_id ?? null;

        if (! $user_id) {
            $this->log('ERROR SubActuatorListener : User not found - actuator : ' . json_encode($actuator));
            return;
        }

        $total_actuators = Analytic::where('type', '=', 'total_actuators')->first();

        if (! $total_actuators) {
            // count actuators
            $total_actuators = GardenDeviceModule::where('is', true)->whereHas('module', function ($query) {
                $query->where('type', '=', 'actuator');
            })->count();

            $total_actuators = Analytic::create([
                'type' => 'total_actuators',
                'data' => [
                    'value' => $total_actuators,
                ],
            ]);
        } else {
            $total = (int) $total_actuators->data['value'];
            $total = is_int($total) && $total > 1 ? $total - 1 : 0;
            $total_actuators->data = ['value' => $total];
            $total_actuators->save();
        }

        // total_user_actuators
        $total_user_actuators = Analytic::where('type', '=', 'total_user_actuators')->where('analyticable_type', '=', User::class)->where('analyticable_id', '=', $user_id)->first();

        if (! $total_user_actuators) {
            // count actuators
            $total_user_actuators = GardenDeviceModule::where('is', true)->whereHas('garden_device.garden', function ($query) use ($user_id) {
                $query->where('user_id', '=', $user_id);
            })
            ->whereHas('module', function ($query) {
                $query->where('type', '=', 'actuator');
            })
            ->count();

            $total_user_actuators = Analytic::create([
                'type' => 'total_user_actuators',
                'data' => [
                    'value' => $total_user_actuators,
                ],
            ]);
        } else {
            $total = (int) $total_user_actuators->data['value'];
            $total = is_int($total) && $total > 1 ? $total - 1 : 0;
            $total_user_actuators->data = ['value' => $total];
            $total_user_actuators->save();
        }
    }
}
