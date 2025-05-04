<?php

namespace App\Http\Controllers;

use App\Http\Requests\Log\IndexRequest;
use App\Http\Requests\Log\StoreRequest;
use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Garden;
use App\Models\GardenDevice;
use App\Models\GardenDeviceModule;
use App\Models\Log;
use App\Models\Module;
use App\Models\Project;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request, Garden $garden, GardenDevice $garden_device, GardenDeviceModule $module)
    {
        $module->unit_value = $request->context['value'];
        $module->save();

        $log = Log::create([
            'loggable_type' => GardenDeviceModule::class,
            'loggable_id' => $module->id,
            'level' => $request->level,
            'context' => $request->context,
        ]);

        return response()->json([
            'module' => $module,
            'log' => $log,
        ]);
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
