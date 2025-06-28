<?php
namespace App\Http\Controllers;

use App\Http\Requests\Module\DestroyRequest;
use App\Http\Requests\Module\IndexRequest;
use App\Http\Requests\Module\StoreRequest;
use App\Http\Requests\Module\UpdateRequest;
use App\Models\DeviceType;
use App\Models\Module;
use App\Models\Project;

class ModuleController extends Controller
{
    public function index(IndexRequest $request, Project $project, DeviceType $device_type)
    {
        return response()->json($device_type->modules);
    }

    public function store(StoreRequest $request, Project $project, DeviceType $device_type)
    {
        $duplicate = Module::where('name', $request->name)->where('device_type_id', $device_type->id)->first();
        if ($duplicate) {
            return response()->json(['message' => 'Module already exists'], 409);
        }

        $module = [
            'name' => $request->name,
            'type' => $request->type,
            'device_type_id' => $device_type->id,
            'default_unit_type' => $request->default_unit_type ?? null,
            'default_unit_value' => $request->default_unit_value,
        ];

        $module = Module::create($module);
        return response()->json($module, 201);
    }

    public function show(Module $module)
    {
        return response()->json($module);
    }

    public function update(UpdateRequest $request, Project $project, DeviceType $device_type, Module $module)
    {
        $duplicate = Module::where('name', $request->name)->where('device_type_id', $device_type->id)->where('id', '!=', $module->id)->first();
        if ($duplicate) {
            return response()->json(['message' => 'Module name already exists'], 409);
        }
        
        $module->update($request->validated());
        return response()->json($module);
    }

    // public function destroy(DestroyRequest $request, Module $module)
    // {
    //     $module->delete();
    //     return response()->json(null, 204);
    // }
}
