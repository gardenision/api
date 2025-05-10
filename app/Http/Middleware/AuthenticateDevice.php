<?php

namespace App\Http\Middleware;

use App\Models\Device;
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

        if (!$request->route('module')) {
            return response()->json(['message' => 'Module not provided'], 401);
        }

        $module = $request->route('module');

        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken || ! $accessToken->tokenable instanceof \App\Models\Device) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        if ($accessToken->tokenable->serial_number !== $request->route('serial_number')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $garden_device_module = GardenDeviceModule::where('module_id', $module->id)->whereHas('garden_device.device', function ($query) use ($module) {
            $query->where('device_type_id', $module->device_type_id);
        })->first();

        if (!$garden_device_module) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // Login the device
        $request->merge(['device' => $accessToken->tokenable, 'garden_device_module' => $garden_device_module]);

        return $next($request);
    }
}
