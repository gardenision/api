<?php
namespace App\Http\Controllers;

use App\Http\Requests\DeviceType\DestroyRequest;
use App\Http\Requests\DeviceType\IndexRequest;
use App\Http\Requests\DeviceType\StoreRequest;
use App\Http\Requests\DeviceType\UpdateRequest;
use App\Models\DeviceType;
use App\Models\Project;

class DeviceTypeController extends Controller
{
    public function index(IndexRequest $request, Project $project)
    {
        return response()->json($project->device_types);
    }

    public function store(StoreRequest $request, Project $project)
    {
        $duplicate = DeviceType::where('name', $request->name)->where('project_id', $project->id)->first();
        if ($duplicate) {
            return response()->json(['message' => 'Device type name already exists'], 409);
        }

        $deviceType = [
            'project_id' => $project->id,
            'name' => $request->name,
        ];

        $deviceType = DeviceType::create($deviceType);
        return response()->json($deviceType, 201);
    }

    public function show(DeviceType $deviceType)
    {
        return response()->json($deviceType);
    }

    public function update(UpdateRequest $request, Project $project, DeviceType $deviceType)
    {
        $duplicate = DeviceType::where('name', $request->name)->where('project_id', $project->id)->where('id', '!=', $deviceType->id)->first();
        if ($duplicate) {
            return response()->json(['message' => 'Device type name already exists'], 409);
        }
        
        $deviceType->update($request->validated());
        return response()->json($deviceType);
    }

    // public function destroy(DestroyRequest $request, Project $project, DeviceType $device_type)
    // {
    //     $device_type->delete();
    //     return response()->json(null, 204);
    // }
}
