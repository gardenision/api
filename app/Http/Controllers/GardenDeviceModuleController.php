<?php

namespace App\Http\Controllers;

use App\Http\Requests\GardenDeviceModule\DestroyRequest;
use App\Http\Requests\GardenDeviceModule\IndexRequest;
use App\Http\Requests\GardenDeviceModule\StoreRequest;
use App\Http\Requests\GardenDeviceModule\UpdateRequest;
use App\Models\Garden;
use App\Models\GardenDevice;
use App\Models\GardenDeviceModule;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GardenDeviceModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request, Garden $garden, GardenDevice $garden_device)
    {
        return response()->json(array_merge($garden_device->load('modules')->toArray(), ['garden' => array_merge($garden->toArray(), ['user' => $request->user()->toArray()])]));
    }

    public function store(StoreRequest $request, Garden $garden, GardenDevice $garden_device, Module $module)
    {
        $garden_device_module = $garden_device->modules()->create([
            'module_id' => $module->id,
            'is_active' => true,
            'unit_value' => $module->default_unit_value ?? null,
            'unit_type' => $module->default_unit_type,
        ]);

        $garden_device_module->logs()->create([
            'level' => 'info',
            'context' => [
                'value' => $garden_device_module->unit_value
            ],
        ]);

        return response()->json($garden_device_module, 201);
    }

    public function update(UpdateRequest $request, Garden $garden, GardenDevice $garden_device, GardenDeviceModule $garden_device_module)
    {
        $garden_device_module->update($request->validated());
        $garden_device_module->logs()->create([
            'level' => 'info',
            'context' => [
                'value' => $garden_device_module->unit_value
            ],
        ]);

        return response()->json($garden_device_module);
    }

    public function destroy(DestroyRequest $request, Garden $garden, GardenDevice $garden_device, GardenDeviceModule $garden_device_module)
    {
        $garden_device_module->delete();
        return response()->json(null, 204);
    }
}
