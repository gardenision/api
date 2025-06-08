<?php

namespace App\Http\Controllers;

use App\Http\Requests\Analytic\IndexRequest;
use App\Models\Garden;
use App\Models\GardenDevice;
use App\Models\GardenDeviceModule;
use App\Models\Module;
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
            if ($request->start_date && $request->end_date) {
                $module_logs = $module_logs->whereBetween('created_at', [$request->start_date, $request->end_date]);
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
            if ($request->start_date && $request->end_date) {
                $device_logs = $device_logs->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }
            $device_logs = $device_logs->get();

            $logs['device'][] = array_merge($device->toArray(), ['logs' => $device_logs]);
        }

        return response()->json($logs);
    }
}
