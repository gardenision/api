<?php

namespace App\Listeners;

use App\Events\AddLog;
use App\Models\Analytic;
use App\Models\GardenDeviceModule;
use App\Models\Log;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddLogListener extends Listener
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
    public function handle(AddLog $event): void
    {
        $log = $event->log;

        if ($log->loggable instanceof GardenDeviceModule) {
            $this->log('INFO AddLogListener : GardenDeviceModule');
            $garden_device_module = $log->loggable;
            $garden_device = $garden_device_module->garden_device;
            $user_id = $garden_device->garden->user_id ?? null;

            if (! $user_id) {
                $this->log('ERROR AddLogListener : User not found - garden_device : ' . json_encode($garden_device));
                return;
            }

            // total_logs_perday
            $total_logs_perday = Analytic::where('type', '=', 'total_logs_perday')
            ->where('created_at', '>=', now()->startOfDay())
            ->where('created_at', '<=', now()->endOfDay())
            ->first();

            if (! $total_logs_perday) {
                // count logs per day
                $total_logs_perday = Log::where('loggable_type', '=', GardenDeviceModule::class)
                ->where('created_at', '>=', now()->startOfDay())
                ->where('created_at', '<=', now()->endOfDay())
                ->count();

                $total_logs_perday = Analytic::create([
                    'type' => 'total_logs_perday',
                    'data' => [
                        'value' => $total_logs_perday,
                    ],
                ]);
            } else {
                $total = (int) $total_logs_perday->data['value'];
                $total = is_int($total) && $total >= 0 ? $total + 1 : 1;
                $total_logs_perday->data = ['value' => $total];
                $total_logs_perday->save();
            }

            // total_user_logs_perday
            $total_user_logs_perday = Analytic::where('type', '=', 'total_user_logs_perday')
            ->where('analyticable_type', '=', User::class)
            ->where('analyticable_id', '=', $user_id)
            ->where('created_at', '>=', now()->startOfDay())
            ->where('created_at', '<=', now()->endOfDay())
            ->first();

            if (! $total_user_logs_perday) {
                // count logs per day
                $total_user_logs_perday = Log::whereHasMorph('loggable', [GardenDeviceModule::class], function($query) use ($user_id) {
                    $query->whereHas('garden_device.garden', function($subQuery) use ($user_id) {
                        $subQuery->where('user_id', '=', $user_id);
                    });
                })
                ->where('created_at', '>=', now()->startOfDay())
                ->where('created_at', '<=', now()->endOfDay())
                ->count();

                $total_user_logs_perday = Analytic::create([
                    'type' => 'total_user_logs_perday',
                    'data' => [
                        'value' => $total_user_logs_perday,
                    ],
                    'analyticable_type' => User::class,
                    'analyticable_id' => $user_id,
                ]);
            } else {
                $total = (int) $total_user_logs_perday->data['value'];
                $total = is_int($total) && $total >= 0 ? $total + 1 : 1;
                $total_user_logs_perday->data = ['value' => $total];
                $total_user_logs_perday->save();
            }

            if ($garden_device_module->module->type === 'sensor') {
                // total_logs_sensor_perday
                $total_logs_sensor_perday = Analytic::where('type', '=', 'total_logs_sensor_perday')
                ->where('created_at', '>=', now()->startOfDay())
                ->where('created_at', '<=', now()->endOfDay())
                ->first();

                if (! $total_logs_sensor_perday) {
                    // count logs per day
                    $total_logs_sensor_perday = Log::whereHasMorph('loggable', [GardenDeviceModule::class], function($query) {
                        $query->whereHas('module', function($subQuery) {
                            $subQuery->where('type', '=', 'sensor');
                        });
                    })
                    ->where('created_at', '>=', now()->startOfDay())
                    ->where('created_at', '<=', now()->endOfDay())
                    ->count();

                    $total_logs_sensor_perday = Analytic::create([
                        'type' => 'total_logs_sensor_perday',
                        'data' => [
                            'value' => $total_logs_sensor_perday,
                        ],
                    ]);
                } else {
                    $total = (int) $total_logs_sensor_perday->data['value'];
                    $total = is_int($total) && $total >= 0 ? $total + 1 : 1;
                    $total_logs_sensor_perday->data = ['value' => $total];
                    $total_logs_sensor_perday->save();
                }
                
                // total_user_logs_sensor_perday
                $total_user_logs_sensor_perday = Analytic::where('type', '=', 'total_user_logs_sensor_perday')
                ->where('analyticable_type', '=', User::class)
                ->where('analyticable_id', '=', $user_id)
                ->where('created_at', '>=', now()->startOfDay())
                ->where('created_at', '<=', now()->endOfDay())
                ->first();

                if (! $total_user_logs_sensor_perday) {
                    // count user logs per day
                    $total_user_logs_sensor_perday = Log::whereHasMorph('loggable', [GardenDeviceModule::class], function($query) use ($user_id) {
                        $query->whereHas('garden_device.garden', function($subQuery) use ($user_id) {
                            $subQuery->where('user_id', '=', $user_id);
                        })
                        ->whereHas('module', function($subQuery) {
                            $subQuery->where('type', '=', 'sensor');
                        });
                    })
                    ->where('created_at', '>=', now()->startOfDay())
                    ->where('created_at', '<=', now()->endOfDay())
                    ->count();

                    $total_user_logs_sensor_perday = Analytic::create([
                        'type' => 'total_user_logs_sensor_perday',
                        'data' => [
                            'value' => $total_user_logs_sensor_perday,
                        ],
                        'analyticable_type' => User::class,
                        'analyticable_id' => $user_id,
                    ]);
                } else {
                    $total = (int) $total_user_logs_sensor_perday->data['value'];
                    $total = is_int($total) && $total >= 0 ? $total + 1 : 1;
                    $total_user_logs_sensor_perday->data = ['value' => $total];
                    $total_user_logs_sensor_perday->save();
                }
            } else if ($garden_device_module->module->type === 'actuator') {

                // total_logs_actuator_perday
                $total_logs_actuator_perday = Analytic::where('type', '=', 'total_logs_actuator_perday')
                ->where('created_at', '>=', now()->startOfDay())
                ->where('created_at', '<=', now()->endOfDay())
                ->first();

                if (! $total_logs_actuator_perday) {
                    // count logs per day
                    $total_logs_actuator_perday = Log::whereHasMorph('loggable', [GardenDeviceModule::class], function($query) {
                        $query->whereHas('module', function($subQuery) {
                            $subQuery->where('type', '=', 'actuator');
                        });
                    })
                    ->where('created_at', '>=', now()->startOfDay())
                    ->where('created_at', '<=', now()->endOfDay())
                    ->count();

                    $total_logs_actuator_perday = Analytic::create([
                        'type' => 'total_logs_actuator_perday',
                        'data' => [
                            'value' => $total_logs_actuator_perday,
                        ],
                    ]);
                } else {
                    $total = (int) $total_logs_actuator_perday->data['value'];
                    $total = is_int($total) && $total >= 0 ? $total + 1 : 1;
                    $total_logs_actuator_perday->data = ['value' => $total];
                    $total_logs_actuator_perday->save();
                }
                
                // total_user_logs_actuator_perday
                $total_user_logs_actuator_perday = Analytic::where('type', '=', 'total_user_logs_actuator_perday')
                ->where('analyticable_type', '=', User::class)
                ->where('analyticable_id', '=', $user_id)
                ->where('created_at', '>=', now()->startOfDay())
                ->where('created_at', '<=', now()->endOfDay())
                ->first();

                if (! $total_user_logs_actuator_perday) {
                    // count logs per day
                    $total_user_logs_actuator_perday = Log::whereHasMorph('loggable', [GardenDeviceModule::class], function($query) use ($user_id) {
                        $query->whereHas('garden_device.garden', function($subQuery) use ($user_id) {
                            $subQuery->where('user_id', '=', $user_id);
                        })
                        ->whereHas('module', function($subQuery) {
                            $subQuery->where('type', '=', 'actuator');
                        });
                    })
                    ->where('created_at', '>=', now()->startOfDay())
                    ->where('created_at', '<=', now()->endOfDay())
                    ->count();

                    $total_user_logs_actuator_perday = Analytic::create([
                        'type' => 'total_user_logs_actuator_perday',
                        'data' => [
                            'value' => $total_user_logs_actuator_perday,
                        ],
                        'analyticable_type' => User::class,
                        'analyticable_id' => $user_id,
                    ]);
                } else {
                    $total = (int) $total_user_logs_actuator_perday->data['value'];
                    $total = is_int($total) && $total >= 0 ? $total + 1 : 1;
                    $total_user_logs_actuator_perday->data = ['value' => $total];
                    $total_user_logs_actuator_perday->save();
                }
            }
        }
    }
}
