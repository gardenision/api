<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceToken\DestroyRequest;
use App\Http\Requests\DeviceToken\IndexRequest;
use App\Http\Requests\DeviceToken\StoreRequest;
use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Project;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class DeviceTokenController extends Controller
{
    public function index(IndexRequest $request, Project $project, DeviceType $device_type, Device $device)
    {
        return response()->json($device->tokens);
    }

    public function store(StoreRequest $request, Project $project, DeviceType $device_type, Device $device)
    {
        $token = $device->createToken('device-token')->plainTextToken;

        return response()->json([
            'message' => 'Token created successfully',
            'token' => $token,
        ], 201);
    }

    public function destroy(DestroyRequest $request, Project $project, DeviceType $device_type, Device $device, PersonalAccessToken $token)
    {
        $token->delete();
        return response()->json(null, 204);
    }
}
