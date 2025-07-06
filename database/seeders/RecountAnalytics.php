<?php

namespace Database\Seeders;

use App\Models\Analytic;
use App\Models\Garden;
use App\Models\GardenDevice;
use App\Models\GardenDeviceModule;
use App\Models\Log;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecountAnalytics extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // total_projects
        $total_projects = Project::count();

        $analytic_total_projects = Analytic::where('type', '=', 'total_projects')->get();
        if ( $analytic_total_projects ) {
            $analytic_total_projects->each(function ($analytic) {
                $analytic->delete();
            });
        }

        Analytic::create([
            'type' => 'total_projects',
            'data' => [
                'value' => $total_projects,
            ],
        ]);

        // total_devices
        $total_devices = GardenDevice::count();

        $analytic_total_devices = Analytic::where('type', '=', 'total_devices')->get();
        if ( $analytic_total_devices ) {
            $analytic_total_devices->each(function ($analytic) {
                $analytic->delete();
            });
        }

        Analytic::create([
            'type' => 'total_devices',
            'data' => [
                'value' => $total_devices,
            ],
        ]);

        // total_active_sensors
        $total_active_sensors = GardenDeviceModule::whereHas('module', function ($query) {
            $query->where('type', '=', 'sensor');
        })
        ->where('is_active', true)->count();

        $analytic_total_active_sensors = Analytic::where('type', '=', 'total_active_sensors')->get();
        if ( $analytic_total_active_sensors ) {
            $analytic_total_active_sensors->each(function ($analytic) {
                $analytic->delete();
            });
        }

        Analytic::create([
            'type' => 'total_active_sensors',
            'data' => [
                'value' => $total_active_sensors,
            ],
        ]);

        // total_inactive_sensors
        $total_inactive_sensors = GardenDeviceModule::whereHas('module', function ($query) {
            $query->where('type', '=', 'sensor');
        })
        ->where('is_active', false)->count();

        $analytic_total_inactive_sensors = Analytic::where('type', '=', 'total_inactive_sensors')->get();
        if ( $analytic_total_inactive_sensors ) {
            $analytic_total_inactive_sensors->each(function ($analytic) {
                $analytic->delete();
            });
        }

        Analytic::create([
            'type' => 'total_inactive_sensors',
            'data' => [
                'value' => $total_inactive_sensors,
            ],
        ]);

        // total_actuators
        $total_actuators = GardenDeviceModule::whereHas('module', function ($query) {
            $query->where('type', '=', 'actuator');
        })
        ->count();

        $analytic_total_actuators = Analytic::where('type', '=', 'total_actuators')->get();
        if ( $analytic_total_actuators ) {
            $analytic_total_actuators->each(function ($analytic) {
                $analytic->delete();
            });
        }

        Analytic::create([
            'type' => 'total_actuators',
            'data' => [
                'value' => $total_actuators,
            ],
        ]);

        // total_logs_perday
        $total_logs_perday = Log::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
        ->groupBy(DB::raw('DATE(created_at)'))
        ->get();

        $analytic_total_logs_perday = Analytic::where('type', '=', 'total_logs_perday')->get();
        if ( $analytic_total_logs_perday ) {
            $analytic_total_logs_perday->each(function ($analytic) {
                $analytic->delete();
            });
        }

        $total_logs_perday = $total_logs_perday ? $total_logs_perday->toArray() : [];
        $total_logs_perday = array_map(function ($log) {
            return [
                'type' => 'total_logs_perday',
                'data' => json_encode([
                    'value' => $log['total'],
                ]),
                'timestamp' => date('Y-m-d 00:00:00', strtotime($log['date'])),
                'created_at' => date('Y-m-d 00:00:00', strtotime($log['date'])),
            ];
        }, $total_logs_perday);
        Analytic::insert($total_logs_perday);

        // total_logs_active_sensor_perday
        $total_logs_active_sensor_perday = Log::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
        ->whereHasMorph('loggable', [GardenDeviceModule::class], function($query) {
            $query->whereHas('module', function($subQuery) {
                $subQuery->where('type', '=', 'sensor');
            })
            ->where('is_active', true);
        })
        ->groupBy(DB::raw('DATE(created_at)'))
        ->get();

        $analytic_total_logs_active_sensor_perday = Analytic::where('type', '=', 'total_logs_active_sensor_perday')->get();
        if ( $analytic_total_logs_active_sensor_perday ) {
            $analytic_total_logs_active_sensor_perday->each(function ($analytic) {
                $analytic->delete();
            });
        }

        $total_logs_active_sensor_perday = $total_logs_active_sensor_perday ? $total_logs_active_sensor_perday->toArray() : [];
        $total_logs_active_sensor_perday = array_map(function ($log) {
            return [
                'type' => 'total_logs_active_sensor_perday',
                'data' => json_encode([
                    'value' => $log['total'],
                ]),
                'timestamp' => date('Y-m-d 00:00:00', strtotime($log['date'])),
                'created_at' => date('Y-m-d 00:00:00', strtotime($log['date'])),
            ];
        }, $total_logs_active_sensor_perday);
        Analytic::insert($total_logs_active_sensor_perday);

        // total_logs_inactive_sensor_perday
        $total_logs_inactive_sensor_perday = Log::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
        ->whereHasMorph('loggable', [GardenDeviceModule::class], function($query) {
            $query->whereHas('module', function($subQuery) {
                $subQuery->where('type', '=', 'sensor');
            })
            ->where('is_active', false);
        })
        ->groupBy(DB::raw('DATE(created_at)'))
        ->get();

        $analytic_total_logs_inactive_sensor_perday = Analytic::where('type', '=', 'total_logs_inactive_sensor_perday')->get();
        if ( $analytic_total_logs_inactive_sensor_perday ) {
            $analytic_total_logs_inactive_sensor_perday->each(function ($analytic) {
                $analytic->delete();
            });
        }

        $total_logs_inactive_sensor_perday = $total_logs_inactive_sensor_perday ? $total_logs_inactive_sensor_perday->toArray() : [];
        $total_logs_inactive_sensor_perday = array_map(function ($log) {
            return [
                'type' => 'total_logs_inactive_sensor_perday',
                'data' => [
                    'value' => $log['total'],
                ],
                'timestamp' => date('Y-m-d 00:00:00', strtotime($log['date'])),
                'created_at' => date('Y-m-d 00:00:00', strtotime($log['date'])),
            ];
        }, $total_logs_inactive_sensor_perday);
        Analytic::insert($total_logs_inactive_sensor_perday);

        // total_logs_actuator_perday
        $total_logs_actuator_perday = Log::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
        ->whereHasMorph('loggable', [GardenDeviceModule::class], function($query) {
            $query->whereHas('module', function($subQuery) {
                $subQuery->where('type', '=', 'actuator');
            });
        })
        ->groupBy(DB::raw('DATE(created_at)'))
        ->get();

        $total_logs_actuator_perday = $total_logs_actuator_perday ? $total_logs_actuator_perday->toArray() : [];
        $total_logs_actuator_perday = array_map(function ($log) {
            return [
                'type' => 'total_logs_actuator_perday',
                'data' => json_encode([
                    'value' => $log['total'],
                ]),
                'timestamp' => date('Y-m-d 00:00:00', strtotime($log['date'])),
                'created_at' => date('Y-m-d 00:00:00', strtotime($log['date'])),
            ];
        }, $total_logs_actuator_perday);
        Analytic::insert($total_logs_actuator_perday);

        // get all users
        $users = User::all();

        foreach ($users as $user) {
            // total_user_gardens
            $total_user_gardens = Garden::where('user_id', '=', $user->id)->count();

            $analytic_total_user_gardens = Analytic::where('type', '=', 'total_user_gardens')->where('analyticable_type', '=', User::class)->where('analyticable_id', '=', $user->id)->get();
            if ( $analytic_total_user_gardens ) {
                $analytic_total_user_gardens->each(function ($analytic) {
                    $analytic->delete();
                });
            }

            $total_user_gardens = [
                'type' => 'total_user_gardens',
                'data' => [
                    'value' => $total_user_gardens,
                ],
                'analyticable_type' => User::class,
                'analyticable_id' => $user->id,
            ];
            Analytic::create($total_user_gardens);

            // total_user_devices
            $total_user_devices = GardenDevice::whereHas('garden', function ($query) use ($user) {
                $query->where('user_id', '=', $user->id);
            })->count();

            $analytic_total_user_devices = Analytic::where('type', '=', 'total_user_devices')->where('analyticable_type', '=', User::class)->where('analyticable_id', '=', $user->id)->get();
            if ( $analytic_total_user_devices ) {
                $analytic_total_user_devices->each(function ($analytic) {
                    $analytic->delete();
                });
            }

            $total_user_devices = [
                'type' => 'total_user_devices',
                'data' => [
                    'value' => $total_user_devices,
                ],
                'analyticable_type' => User::class,
                'analyticable_id' => $user->id,
            ];
            Analytic::create($total_user_devices);

            // total_user_active_sensors
            $total_user_active_sensors = GardenDeviceModule::whereHas('garden_device.garden', function ($query) use ($user) {
                $query->where('user_id', '=', $user->id);
            })
            ->whereHas('module.type', function ($query) {
                $query->where('type', '=', 'sensor');
            })
            ->where('is_active', true)->count();

            $analytic_total_user_active_sensors = Analytic::where('type', '=', 'total_user_active_sensors')->where('analyticable_type', '=', User::class)->where('analyticable_id', '=', $user->id)->get();
            if ( $analytic_total_user_active_sensors ) {
                $analytic_total_user_active_sensors->each(function ($analytic) {
                    $analytic->delete();
                });
            }

            $total_user_active_sensors = [
                'type' => 'total_user_active_sensors',
                'data' => [
                    'value' => $total_user_active_sensors,
                ],
                'analyticable_type' => User::class,
                'analyticable_id' => $user->id,
            ];
            Analytic::create($total_user_active_sensors);

            // total_user_inactive_sensors
            $total_user_inactive_sensors = GardenDeviceModule::whereHas('garden_device.garden', function ($query) use ($user) {
                $query->where('user_id', '=', $user->id);
            })
            ->whereHas('module.type', function ($query) {
                $query->where('type', '=', 'sensor');
            })
            ->where('is_active', false)->count();

            $analytic_total_user_inactive_sensors = Analytic::where('type', '=', 'total_user_inactive_sensors')->where('analyticable_type', '=', User::class)->where('analyticable_id', '=', $user->id)->get();
            if ( $analytic_total_user_inactive_sensors ) {
                $analytic_total_user_inactive_sensors->each(function ($analytic) {
                    $analytic->delete();
                });
            }

            $total_user_inactive_sensors = [
                'type' => 'total_user_inactive_sensors',
                'data' => [
                    'value' => $total_user_inactive_sensors,
                ],
                'analyticable_type' => User::class,
                'analyticable_id' => $user->id,
            ];
            Analytic::create($total_user_inactive_sensors);

            // total_user_actuators
            $total_user_actuators = GardenDeviceModule::whereHas('garden_device.garden', function ($query) use ($user) {
                $query->where('user_id', '=', $user->id);
            })
            ->whereHas('module.type', function ($query) {
                $query->where('type', '=', 'actuator');
            })
            ->count();

            $analytic_total_user_actuators = Analytic::where('type', '=', 'total_user_actuators')->where('analyticable_type', '=', User::class)->where('analyticable_id', '=', $user->id)->get();
            if ( $analytic_total_user_actuators ) {
                $analytic_total_user_actuators->each(function ($analytic) {
                    $analytic->delete();
                });
            }

            $total_user_actuators = [
                'type' => 'total_user_actuators',
                'data' => [
                    'value' => $total_user_actuators,
                ],
                'analyticable_type' => User::class,
                'analyticable_id' => $user->id,
            ];
            Analytic::create($total_user_actuators);

            // total_user_logs_perday
            $total_user_logs_perday = Log::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->whereHasMorph('loggable', [GardenDeviceModule::class], function($query) use ($user) {
                $query->whereHas('garden_device.garden', function($subQuery) use ($user) {
                    $subQuery->where('user_id', '=', $user->id);
                })
                ->whereHas('module', function($subQuery) {
                    $subQuery->where('type', '=', 'sensor');
                });
            })
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();

            $analytic_total_user_logs_perday = Analytic::where('type', '=', 'total_user_logs_perday')->where('analyticable_type', '=', User::class)->where('analyticable_id', '=', $user->id)->get();
            if ( $analytic_total_user_logs_perday ) {
                $analytic_total_user_logs_perday->each(function ($analytic) {
                    $analytic->delete();
                });
            }

            $total_user_logs_perday = $total_user_logs_perday ? $total_user_logs_perday->toArray() : [];
            $total_user_logs_perday = array_map(function ($log) use ($user) {
                return [
                    'type' => 'total_user_logs_perday',
                    'data' => json_encode([
                        'value' => $log['total'],
                    ]),
                    'analyticable_type' => User::class,
                    'analyticable_id' => $user->id,
                    'timestamp' => date('Y-m-d 00:00:00', strtotime($log['date'])),
                    'created_at' => date('Y-m-d 00:00:00', strtotime($log['date'])),
                ];
            }, $total_user_logs_perday);
            Analytic::insert($total_user_logs_perday);

            // total_user_logs_sensor_perday
            $total_user_logs_sensor_perday = Log::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->whereHasMorph('loggable', [GardenDeviceModule::class], function($query) use ($user) {
                $query->whereHas('garden_device.garden', function($subQuery) use ($user) {
                    $subQuery->where('user_id', '=', $user->id);
                })
                ->whereHas('module', function($subQuery) {
                    $subQuery->where('type', '=', 'sensor');
                });
            })
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();

            $analytic_total_user_logs_sensor_perday = Analytic::where('type', '=', 'total_user_logs_sensor_perday')->where('analyticable_type', '=', User::class)->where('analyticable_id', '=', $user->id)->get();
            if ( $analytic_total_user_logs_sensor_perday ) {
                $analytic_total_user_logs_sensor_perday->each(function ($analytic) {
                    $analytic->delete();
                });
            }

            $total_user_logs_sensor_perday = $total_user_logs_sensor_perday ? $total_user_logs_sensor_perday->toArray() : [];
            $total_user_logs_sensor_perday = array_map(function ($log) use ($user) {
                return [
                    'type' => 'total_user_logs_sensor_perday',
                    'data' => json_encode([
                        'value' => $log['total'],
                    ]),
                    'analyticable_type' => User::class,
                    'analyticable_id' => $user->id,
                    'timestamp' => date('Y-m-d 00:00:00', strtotime($log['date'])),
                    'created_at' => date('Y-m-d 00:00:00', strtotime($log['date'])),
                ];
            }, $total_user_logs_sensor_perday);
            Analytic::insert($total_user_logs_sensor_perday);

            // total_user_logs_actuator_perday
            $total_user_logs_actuator_perday = Log::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->whereHasMorph('loggable', [GardenDeviceModule::class], function($query) use ($user) {
                $query->whereHas('garden_device.garden', function($subQuery) use ($user) {
                    $subQuery->where('user_id', '=', $user->id);
                })
                ->whereHas('module', function($subQuery) {
                    $subQuery->where('type', '=', 'actuator');
                });
            })
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();

            $analytic_total_user_logs_actuator_perday = Analytic::where('type', '=', 'total_user_logs_actuator_perday')->where('analyticable_type', '=', User::class)->where('analyticable_id', '=', $user->id)->get();
            if ( $analytic_total_user_logs_actuator_perday ) {
                $analytic_total_user_logs_actuator_perday->each(function ($analytic) {
                    $analytic->delete();
                });
            }

            $total_user_logs_actuator_perday = $total_user_logs_actuator_perday ? $total_user_logs_actuator_perday->toArray() : [];
            $total_user_logs_actuator_perday = array_map(function ($log) use ($user) {
                return [
                    'type' => 'total_user_logs_actuator_perday',
                    'data' => json_encode([
                        'value' => $log['total'],
                    ]),
                    'analyticable_type' => User::class,
                    'analyticable_id' => $user->id,
                    'timestamp' => date('Y-m-d 00:00:00', strtotime($log['date'])),
                    'created_at' => date('Y-m-d 00:00:00', strtotime($log['date'])),
                ];
            }, $total_user_logs_actuator_perday);
            Analytic::insert($total_user_logs_actuator_perday);
        }
    }
}
