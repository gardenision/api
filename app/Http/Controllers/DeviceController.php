<?php
namespace App\Http\Controllers;

use App\Http\Requests\Device\DestroyRequest;
use App\Http\Requests\Device\IndexRequest;
use App\Http\Requests\Device\StoreRequest;
use App\Http\Requests\Device\UpdateRequest;
use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Project;

class DeviceController extends Controller
{
    public function index(IndexRequest $request)
    {
        return response()->json(Device::all());
    }

    public function store(StoreRequest $request, Project $project, DeviceType $device_type)
    {
        $device = [
            'project_id' => $project->id,
            'device_type_id' => $device_type->id,
            'name' => $request->name,
            'serial_number' => $request->serial_number,
        ];

        $device = Device::create($device);
        return response()->json($device, 201);
    }

    public function show(Device $device)
    {
        return response()->json($device);
    }

    public function update(UpdateRequest $request, Project $project, DeviceType $device_type, Device $device)
    {
        $device->update($request->validated());
        return response()->json($device);
    }

    public function destroy(DestroyRequest $request, Project $project, DeviceType $device_type, Device $device)
    {
        $device->delete();
        return response()->json(null, 204);
    }
}
