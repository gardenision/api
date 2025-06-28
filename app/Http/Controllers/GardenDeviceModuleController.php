<?php

namespace App\Http\Controllers;

use App\Events\AddActuator;
use App\Events\AddSensor;
use App\Events\EditSensor;
use App\Events\SubActuator;
use App\Events\SubSensor;
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
    public function index(IndexRequest $request, Garden $garden, GardenDevice $garden_device, Module $module)
    {
        return response()->json(array_merge($garden_device->load(['modules', 'modules.module'])->toArray(), ['garden' => array_merge($garden->toArray(), ['user' => $request->user()->toArray()])]));
    }

    public function store(StoreRequest $request, Garden $garden, GardenDevice $garden_device, Module $module)
    {
        try {
            DB::beginTransaction();

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
    
            if ($garden_device_module->module->type === 'sensor') {
                event(new AddSensor($garden_device_module));
            } else if ($garden_device_module->module->type === 'actuator') {
                event(new AddActuator($garden_device_module));
            }
    
            DB::commit();

            return response()->json($garden_device_module, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->log("ERROR GardenDeviceModuleController::store : " . json_encode($e));

            throw $e;
        }
    }

    public function update(UpdateRequest $request, Garden $garden, GardenDevice $garden_device, GardenDeviceModule $garden_device_module)
    {
        try {
            DB::beginTransaction();

            if (
                $garden_device_module->module->type === 'sensor' &&
                $request->has('is_active') &&
                $garden_device_module->is_active !== $request->is_active
            ) {
                event(new EditSensor($garden_device_module, ['is_active' => $request->is_active]));
            }

            $garden_device_module->update($request->validated());
            $garden_device_module->logs()->create([
                'level' => 'info',
                'context' => [
                    'value' => $garden_device_module->unit_value
                ],
            ]);

            DB::commit();
    
            return response()->json($garden_device_module);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->log("ERROR GardenDeviceModuleController::update : " . json_encode($e));

            throw $e;
        }
    }

    // public function destroy(DestroyRequest $request, Garden $garden, GardenDevice $garden_device, GardenDeviceModule $garden_device_module)
    // {
    //     try {
    //         DB::beginTransaction();

    //         $tmp_garden_device_module = $garden_device_module;

    //         $garden_device_module->delete();
            
    //         if ($tmp_garden_device_module->module->type === 'sensor') {
    //             event(new SubSensor($tmp_garden_device_module));
    //         } else if ($tmp_garden_device_module->module->type === 'actuator') {
    //             event(new SubActuator($tmp_garden_device_module));
    //         }
            
    //         DB::commit();

    //         return response()->json(null, 204);
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         $this->log("ERROR GardenDeviceModuleController::destroy : " . json_encode($e));

    //         throw $e;
    //     }
    // }
}
