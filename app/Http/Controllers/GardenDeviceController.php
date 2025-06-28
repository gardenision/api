<?php

namespace App\Http\Controllers;

use App\Events\AddActuator;
use App\Events\AddDevice;
use App\Events\AddSensor;
use App\Events\SubActuator;
use App\Events\SubDevice;
use App\Events\SubSensor;
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
    public function index(IndexRequest $request, Garden $garden)
    {
        $garden['devices'] = $garden->devices()->get();
        return response()->json($garden);
    }

    public function store(StoreRequest $request, Garden $garden)
    {
        $user = $request->user();

        DB::beginTransaction();

        try {
            $device = Device::where('serial_number', $request->serial_number)->lockForUpdate()->first();

            $modules = $device->type->modules()->get();

            if (! $modules->count()) {
                DB::rollBack();
                return response()->json(['message' => 'Device has no modules'], 409);
            }

            $garden_device = $garden->devices()->create([
                'device_id' => $device->id,
                'name' => $device->name,
            ]);

            event(new AddDevice($garden_device));

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

            $modules = $garden_device->modules()->get();

            foreach ($modules as $module) {
                if ($module->module->type === 'sensor') {
                    event(new AddSensor($module));
                } else if ($module->module->type === 'actuator') {
                    event(new AddActuator($module));
                }
            }

            DB::commit();

            return response()->json(array_merge($garden_device->toArray(), ['modules' => $modules]), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // public function destroy(DestroyRequest $request, Garden $garden, GardenDevice $garden_device)
    // {
    //     try {
    //         DB::beginTransaction();

    //         $tmp_garden_device = $garden_device;
    //         $garden_device->delete();
            
    //         event(new SubDevice($tmp_garden_device));

    //         $modules = $garden_device->modules()->get();

    //         foreach ($modules as $module) {
    //             if ($module->module->type === 'sensor') {
    //                 event(new SubSensor($module));
    //             } else if ($module->module->type === 'actuator') {
    //                 event(new SubActuator($module));
    //             }
    //         }
            
    //         DB::commit();
    //         return response()->json(null, 204);
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         $this->log("ERROR GardenDeviceController::destroy : " . json_encode($e));

    //         throw $e;
    //     }
    // }
}
