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
    public function index(IndexRequest $request, Garden $garden, GardenDevice $garden_device, ?GardenDeviceModule $module = null)
    {
        $user = $request->user();

        if (! $module) {
            $modules = $garden_device->modules()->with('module')->get();
        } else {
            $modules = [$module->load('module')];
        }

        $logs = [
            'sensor' => [],
            'actuator' => [],
        ];

        foreach ($modules as $module) {
            $logs[$module->module->type][$module->id] = $module;

            $module_logs = $module->logs();
            if ($request->start_date && $request->end_date) {
                $module_logs = $module_logs->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }
            $module_logs = $module_logs->get();

            $logs[$module->module->type][$module->id]['logs'] = $module_logs;
        }

        return response()->json($logs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
