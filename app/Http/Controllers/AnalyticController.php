<?php

namespace App\Http\Controllers;

use App\Http\Requests\Analytic\AdminDashboardRequest;
use App\Http\Requests\Analytic\IndexRequest;
use App\Http\Requests\Analytic\UserDashboardRequest;
use App\Models\Analytic;
use App\Models\Garden;
use App\Models\GardenDevice;
use App\Models\GardenDeviceModule;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;

class AnalyticController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function module(IndexRequest $request, Garden $garden, GardenDevice $garden_device, ?GardenDeviceModule $module = null)
    {
        $user = $request->user();

        if (! $module) {
            $modules = $garden_device->modules()->with('module')->get();
        } else {
            $modules = [$module->with('module')->get()];
        }

        $logs = [
            'sensor' => [],
            'actuator' => [],
        ];

        foreach ($modules as $module) {
            $module_logs = $module->logs();

            $start_date = $request->start_date;
            $end_date = $request->end_date;

            if (! $start_date ) $start_date = now()->subDays(7)->startOfDay();
            if (! $end_date ) $end_date = now()->endOfDay();

            if ($start_date && $end_date) {
                $module_logs = $module_logs->whereBetween('created_at', [$start_date, $end_date]);
            }
            
            $module_logs = $module_logs->get();

            $logs[$module->module->type][] = array_merge($module->toArray(), ['logs' => $module_logs]);
        }

        return response()->json($logs);
    }

    public function device(IndexRequest $request, Garden $garden, ?GardenDevice $garden_device = null)
    {
        $user = $request->user();

        if (! $garden_device) {
            $devices = $garden->devices()->get();
        } else {
            $devices = [$garden_device];
        }

        $logs = [
            'device' => [],
        ];

        foreach ($devices as $device) {
            $device_logs = $device->logs();
            
            $start_date = $request->start_date;
            $end_date = $request->end_date;

            if (! $start_date ) $start_date = now()->subDays(7)->startOfDay();
            if (! $end_date ) $end_date = now()->endOfDay();

            if ($start_date && $end_date) {
                $device_logs = $device_logs->whereBetween('created_at', [$start_date, $end_date]);
            }

            $device_logs = $device_logs->get();

            $logs['device'][] = array_merge($device->toArray(), ['logs' => $device_logs]);
        }

        return response()->json($logs);
    }

    public function adminDashbaord(AdminDashboardRequest $request)
    {
        $data = [
            'total_projects' => 0,
            'total_devices' => 0,
            'total_active_sensors' => 0,
            'total_inactive_sensors' => 0,
            'total_actuators' => 0,
            'total_logs_perday' => 0,
            'total_logs_sensor_perday' => 0,
            'total_logs_actuator_perday' => 0,
            'top_10_devices_last_7_days_perday' => []
        ];

        // total projects
        $data['total_projects'] = Analytic::where('type', '=', 'total_projects')->first()?->data['value'] ?? 0;

        // total devices
        $data['total_devices'] = Analytic::where('type', '=', 'total_devices')->first()?->data['value'] ?? 0;

        // total active sensors
        $data['total_active_sensors'] = Analytic::where('type', '=', 'total_active_sensors')->first()?->data['value'] ?? 0;

        // total inactive sensors
        $data['total_inactive_sensors'] = Analytic::where('type', '=', 'total_inactive_sensors')->first()?->data['value'] ?? 0;

        // total actuators
        $data['total_actuators'] = Analytic::where('type', '=', 'total_actuators')->first()?->data['value'] ?? 0;

        // total logs per day
        $data['total_logs_perday'] = Analytic::where('type', '=', 'total_logs_perday')->where('timestamp', '>=', now()->subDays(7)->startOfDay())->where('timestamp', '<=', now()->endOfDay())->get();
        $data['total_logs_perday'] = $data['total_logs_perday']->map(function ($log) {
            return $log->data['value'];
        });

        // total logs sensor per day
        $data['total_logs_sensor_perday'] = Analytic::where('type', '=', 'total_logs_sensor_perday')->where('timestamp', '>=', now()->subDays(7)->startOfDay())->where('timestamp', '<=', now()->endOfDay())->get();
        $data['total_logs_sensor_perday'] = $data['total_logs_sensor_perday']->map(function ($log) {
            return $log->data['value'];
        });

        // total logs actuator per day
        $data['total_logs_actuator_perday'] = Analytic::where('type', '=', 'total_logs_actuator_perday')->where('timestamp', '>=', now()->subDays(7)->startOfDay())->where('timestamp', '<=', now()->endOfDay())->get();
        $data['total_logs_actuator_perday'] = $data['total_logs_actuator_perday']->map(function ($log) {
            return $log->data['value'];
        });

        // top 10 devices last 7 days per day
        $data['top_10_devices_last_7_days_perday'] = Analytic::where('type', '=', 'top_10_devices_last_7_days_perday')->first()?->data['value'] ?? 0;

        return response()->json($data);
    }

    public function userDashbaord(UserDashboardRequest $request)
    {
        $data = [
            'total_user_gardens' => 0,
            'total_user_devices' => 0,
            'total_user_active_sensors' => 0,
            'total_user_inactive_sensors' => 0,
            'total_user_actuators' => 0,
            'total_user_logs_perday' => 0,
            'total_user_logs_sensor_perday' => 0,
            'total_user_logs_actuator_perday' => 0,
            'top_10_devices_last_7_days_perday' => []
        ];

        // total gardens
        $data['total_user_gardens'] = Analytic::where('type', '=', 'total_user_gardens')->where('analyticable_type', '=', User::class)->where('analyticable_id', '=', $request->user()->id)->first()?->data['value'] ?? 0;

        // total devices
        $data['total_user_devices'] = Analytic::where('type', '=', 'total_user_devices')->where('analyticable_type', '=', User::class)->where('analyticable_id', '=', $request->user()->id)->first()?->data['value'] ?? 0;

        // total active sensors
        $data['total_user_active_sensors'] = Analytic::where('type', '=', 'total_user_active_sensors')->where('analyticable_type', '=', User::class)->where('analyticable_id', '=', $request->user()->id)->first()?->data['value'] ?? 0;

        // total inactive sensors
        $data['total_user_inactive_sensors'] = Analytic::where('type', '=', 'total_user_inactive_sensors')->where('analyticable_type', '=', User::class)->where('analyticable_id', '=', $request->user()->id)->first()?->data['value'] ?? 0;

        // total actuators
        $data['total_user_actuators'] = Analytic::where('type', '=', 'total_user_actuators')->where('analyticable_type', '=', User::class)->where('analyticable_id', '=', $request->user()->id)->first()?->data['value'] ?? 0;

        // total logs per day
        $data['total_user_logs_perday'] = Analytic::where('type', '=', 'total_user_logs_perday')->where('analyticable_type', '=', User::class)->where('analyticable_id', '=', $request->user()->id)->where('timestamp', '>=', now()->subDays(7)->startOfDay())->where('timestamp', '<=', now()->endOfDay())->get();
        $data['total_user_logs_perday'] = $data['total_user_logs_perday']->map(function ($log) {
            return $log->data['value'];
        });

        // total logs sensor per day
        $data['total_user_logs_sensor_perday'] = Analytic::where('type', '=', 'total_user_logs_sensor_perday')->where('analyticable_type', '=', User::class)->where('analyticable_id', '=', $request->user()->id)->where('timestamp', '>=', now()->subDays(7)->startOfDay())->where('timestamp', '<=', now()->endOfDay())->get();
        $data['total_user_logs_sensor_perday'] = $data['total_user_logs_sensor_perday']->map(function ($log) {
            return $log->data['value'];
        });

        // total logs actuator per day
        $data['total_user_logs_actuator_perday'] = Analytic::where('type', '=', 'total_user_logs_actuator_perday')->where('analyticable_type', '=', User::class)->where('analyticable_id', '=', $request->user()->id)->where('timestamp', '>=', now()->subDays(7)->startOfDay())->where('timestamp', '<=', now()->endOfDay())->get();
        $data['total_user_logs_actuator_perday'] = $data['total_user_logs_actuator_perday']->map(function ($log) {
            return $log->data['value'];
        });

        // top 10 devices last 7 days per day
        $data['top_10_devices_last_7_days_perday'] = Analytic::where('type', '=', 'top_10_devices_last_7_days_perday')->where('analyticable_type', '=', User::class)->where('analyticable_id', '=', $request->user()->id)->first()?->data['value'] ?? 0;

        return response()->json($data);
    }
}
