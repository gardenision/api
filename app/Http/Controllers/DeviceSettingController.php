<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceSetting\DestroyRequest;
use App\Http\Requests\DeviceSetting\IndexRequest;
use App\Http\Requests\DeviceSetting\DeviceRequest;
use App\Http\Requests\DeviceSetting\StoreRequest;
use App\Http\Requests\DeviceSetting\UpdateRequest;
use App\Models\Garden;
use App\Models\GardenDevice;
use App\Models\GardenDeviceModule;
use App\Models\Module;
use App\Models\Setting;

class DeviceSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request, Garden $garden, GardenDevice $garden_device)
    {
        $settings = [
            'device' => $garden_device->settings()->get(),
            'module' => []
        ];
        
        $modules = $garden_device->modules()->get();
        if ($modules) {
            foreach ($modules as $module) {
                $setting = $module->settings()->get();
                if (count($setting) > 0) {
                    $settings['module'][$module->id] = $setting;
                } else if ($module->module->type === 'actuator') {
                    // create default setting
                    $setting = $module->settings()->create([
                        'key' => 'value',
                        'value' => $module->module->default_unit_value,
                        'type' => $module->module->default_unit_type,
                        'active' => false,
                        'last_actived_at' => null,
                        'last_inactived_at' => null,
                    ]);

                    $settings['module'][$module->id] = [$setting];

                    // log
                    $garden_device->logs()->create([
                        'level' => 'setting.create',
                        'context' => $setting->toArray(),
                    ]);
                }
            }
        }

        return response()->json($settings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request, Garden $garden, GardenDevice $garden_device, ?GardenDeviceModule $garden_device_module)
    {
        if ($garden_device_module->toArray() && $garden_device_module->settings()->where('key', $request->key)->first()) {
            return response()->json(['message' => 'Setting module ' . $garden_device_module->module->name . ' with key ' . $request->key . ' already exists'], 409);
        } else if ($garden_device->settings()->where('key', $request->key)->first()) {
            return response()->json(['message' => 'Setting device ' . $garden_device->name . ' with key ' . $request->key . ' already exists'], 409);
        }

        if ($garden_device_module->toArray()) {
            $setting = $garden_device_module->settings()->create($request->validated());
        } else {
            $setting = $garden_device->settings()->create($request->validated());
        }

        // log
        $garden_device->logs()->create([
            'level' => 'setting.create',
            'context' => $setting->toArray(),
        ]);

        return response()->json($setting, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function device(DeviceRequest $request, string $serial_number, ?Module $module)
    {
        $garden_device = $request->garden_device;
        $garden_device_module = $request->garden_device_module;

        if ($garden_device_module) {
            $settings = $garden_device_module->settings()->get();
        } else {
            $settings = [
                'device' => $garden_device->settings()->get(),
                'module' => []
            ];

            $modules = $garden_device->modules()->get();
            if ($modules) {
                foreach ($modules as $module) {
                    $setting = $module->settings()->get();
                    if (count($setting) > 0) {
                        $settings['module'][$module->module->id] = [
                            'module' => $module->module,
                            'settings' => $setting
                        ];
                    }
                }
            }
        }

        return response()->json($settings);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Garden $garden, GardenDevice $garden_device, ?GardenDeviceModule $garden_device_module, string $key)
    {
        $setting = null;
        if ($garden_device_module->toArray()) {
            $setting = $garden_device_module->settings()->where('key', $key)->first();
        } else {
            $setting = $garden_device->settings()->where('key', $key)->first();
        }

        if (!$setting) {
            return response()->json(['message' => 'Setting not found'], 404);
        }

        $setting->update($request->validated());

        // log
        $garden_device->logs()->create([
            'level' => 'setting.update',
            'context' => $setting->toArray(),
        ]);

        return response()->json($setting);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyRequest $request, Garden $garden, GardenDevice $garden_device, ?GardenDeviceModule $garden_device_module, string $key)
    {
        $setting = null;
        if ($garden_device_module->toArray()) {
            $setting = $garden_device_module->settings()->where('key', $key)->first();
        } else {
            $setting = $garden_device->settings()->where('key', $key)->first();
        }

        if (!$setting) {
            return response()->json(['message' => 'Setting not found'], 404);
        }

        // log
        $garden_device->logs()->create([
            'level' => 'setting.delete',
            'context' => $setting->toArray(),
        ]);
        
        $setting->delete();

        return response()->json(null, 204);
    }
}
