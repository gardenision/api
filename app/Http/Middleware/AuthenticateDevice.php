<?php

namespace App\Http\Middleware;

use App\Models\Device;
use App\Models\GardenDevice;
use App\Models\GardenDeviceModule;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateDevice
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        if (!$request->route('serial_number')) {
            return response()->json(['message' => 'Serial number not provided'], 401);
        }

        $serial_number = $request->route('serial_number');

        $module = $request->route('module') ?? null;

        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken || ! $accessToken->tokenable instanceof \App\Models\Device) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        if ($accessToken->tokenable->serial_number !== $serial_number) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data['device'] = $accessToken->tokenable;
        $data['garden_device_module'] = null;

        if ($module) {
            $garden_device_module = GardenDeviceModule::where('module_id', $module->id)->whereHas('garden_device.device', function ($query) use ($module) {
                $query->where('device_type_id', $module->device_type_id);
            })->first();
    
            if (!$garden_device_module) {
                return response()->json(['message' => 'Forbidden'], 403);
            }

            $data['garden_device_module'] = $garden_device_module;
        } else {
            $garden_device = GardenDevice::where('device_id', $data['device']->id)->first();
            $data['garden_device'] = $garden_device;
        }

        // Login the device
        $request->merge($data);

        return $next($request);
    }
}
