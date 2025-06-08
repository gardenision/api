<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceSetting\DestroyRequest;
use App\Http\Requests\DeviceSetting\IndexRequest;
use App\Http\Requests\DeviceSetting\ShowNotActiveRequest;
use App\Http\Requests\DeviceSetting\StoreRequest;
use App\Http\Requests\DeviceSetting\UpdateRequest;
use App\Models\Garden;
use App\Models\GardenDevice;
use App\Models\Setting;

class DeviceSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request, Garden $garden, GardenDevice $garden_device)
    {
        return response()->json($garden_device->settings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request, Garden $garden, GardenDevice $garden_device)
    {
        if ($garden_device->settings()->where('key', $request->key)->first()) {
            return response()->json(['message' => 'Setting already exists'], 409);
        }

        $setting = $garden_device->settings()->create($request->validated());

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

    public function show_not_activate(ShowNotActiveRequest $request, string $serial_number)
    {
        $garden_device = $request->garden_device;

        $settings = $garden_device->settings()->where('active', false)->where(function ($query) {
            $query->whereNull('last_actived_at')
                ->orWhereColumn('last_actived_at', '<=', 'updated_at');
        })->get();

        return response()->json($settings);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Garden $garden, GardenDevice $garden_device, Setting $setting)
    {
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
    public function destroy(DestroyRequest $request, Garden $garden, GardenDevice $garden_device, Setting $setting)
    {
        $setting->delete();

        // log
        $garden_device->logs()->create([
            'level' => 'setting.delete',
            'context' => $setting->toArray(),
        ]);

        return response()->json(null, 204);
    }
}
