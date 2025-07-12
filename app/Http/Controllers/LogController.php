<?php

namespace App\Http\Controllers;

use App\Events\AddLog;
use App\Events\GardenDeviceModuleUpdated;
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
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request, string $serial_number, ?Module $module)
    {
        try {
            DB::beginTransaction();

            $garden_device = $request->garden_device ?? null;
            $garden_device_module = $request->garden_device_module ?? null;

            // initialize where
            $where = [];
    
            // initialize response
            $response = [];
    
            if ($garden_device_module) {
                $where['loggable_type'] = GardenDeviceModule::class;
                $where['loggable_id'] = $garden_device_module->id;
    
                $garden_device_module->unit_value = $request->context['value'];
                $garden_device_module->save();

                event(new GardenDeviceModuleUpdated($garden_device_module->toArray(), $garden_device_module->garden_device->garden->user_id));
    
                $response['module'] = $garden_device_module->load('module');
            } else if ($garden_device) {
                $where['loggable_type'] = GardenDevice::class;
                $where['loggable_id'] = $garden_device->id;
    
                if ($request->level !== 'info') {
    
                    // when data is setting, we need to update the setting
                    if ($request->level === 'setting.update' && is_array($request->context)) {
                        $this->updateSetting($garden_device, $request->context);
                    }
    
                    $where['level'] = 'info';
    
                }
            } else {
                DB::rollBack();
                return response()->json(['message' => 'Device not found'], 404);
            }
    
            $updated_data = array_merge($where, [
                'level' => $request->level,
                'context' => $request->context,
            ]);
    
            $log = Log::create($updated_data);
    
            $response['log'] = $log;
            
            event(new AddLog($log));

            DB::commit();

            return response()->json($response);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->log("ERROR LogController::store : " . json_encode($e));
            
            throw $e;
        }
    }

    private function updateSetting(GardenDevice $garden_device, array $context)
    {
        foreach ($context as $key => $value) {
            if (is_string($key)) {
                $setting = $garden_device->settings()->where(['key' => $key])->first();
                if ($setting) {
                    $updated_data_setting = [];
                    $updated_data_setting_allowed = [
                        'value',
                        'active',
                    ];

                    foreach ($updated_data_setting_allowed as $updated_data_setting_allowed_key) {
                        if (isset($value[$updated_data_setting_allowed_key])) {
                            if ($updated_data_setting_allowed_key === 'active' && $value[$updated_data_setting_allowed_key] === true) {
                                $updated_data_setting['last_actived_at'] = now();
                            } else if ($updated_data_setting_allowed_key === 'active' && $value[$updated_data_setting_allowed_key] === false) {
                                $updated_data_setting['last_inactived_at'] = now();
                            }

                            $updated_data_setting[$updated_data_setting_allowed_key] = $value[$updated_data_setting_allowed_key];
                        }
                    }

                    if (count($updated_data_setting)) {
                        $setting = $setting->update($updated_data_setting);
                    }
                }
            }
        }
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
