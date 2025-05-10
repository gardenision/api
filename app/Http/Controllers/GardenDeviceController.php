<?php

namespace App\Http\Controllers;

use App\Http\Requests\GardenDevice\DestroyRequest;
use App\Http\Requests\GardenDevice\IndexRequest;
use App\Http\Requests\GardenDevice\StoreRequest;
use App\Http\Requests\GardenDevice\UpdateRequest;
use App\Models\Device;
use App\Models\Garden;
use App\Models\GardenDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GardenDeviceController extends Controller
{
    public function index(IndexRequest $request)
    {
        $user = $request->user();
        $gardens = $user->gardens()->with('devices')->get();
        return response()->json($gardens);
    }

    public function store(StoreRequest $request, Garden $garden)
    {
        $user = $request->user();

        DB::beginTransaction();

        try {
            $device = Device::where('serial_number', $request->serial_number)->lockForUpdate()->first();

            $modules = $device->type->modules()->get();

            if (! $modules->count()) {
                throw new \Exception('Device has no modules');
            }

            $garden_device = $garden->devices()->create([
                'device_id' => $device->id,
                'name' => $device->name,
            ]);

            $modules = $modules->toArray();

            $modules = array_map(function ($module) use ($garden_device) {
                return [
                    'garden_device_id' => $garden_device->id,
                    'module_id' => $module['id'],
                    'is_active' => true,
                    'unit_type' => $module['default_unit_type'],
                    'unit_value' => $module['default_unit_value'],
                ];
            }, $modules);

            $garden_device->modules()->insert($modules);

            DB::commit();

            return response()->json($garden_device->load('modules'), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy(DestroyRequest $request, Garden $garden, GardenDevice $garden_device)
    {
        $garden_device->delete();
        return response()->json(null, 204);
    }
}
