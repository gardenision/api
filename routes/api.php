<?php

use App\Http\Controllers\{
    AuthController,
    RoleController, ProjectController, DeviceTypeController, 
    ModuleController, DeviceController, GardenController,
    GardenDeviceController,
    GardenDeviceTypeController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);

Route::get('/email/verify/{id}/{hash}', [\App\Http\Controllers\AuthController::class, 'verify'])->middleware(['auth:sanctum', 'signed', 'throttle:6,1'])->name('verification.verify')->middleware('throttle:6,1');
Route::post('/email/resend', [\App\Http\Controllers\AuthController::class, 'resend'])->middleware(['auth:sanctum', 'throttle:6,1']);

Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::middleware([
    'auth:sanctum',
    // 'verified'
])->group(function () {
    
    Route::get('/user', [\App\Http\Controllers\UserController::class, 'user']);
    Route::post('/user', [\App\Http\Controllers\UserController::class, 'edit']);
    Route::delete('/user', [\App\Http\Controllers\UserController::class, 'delete']);
    Route::put('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);

    Route::middleware('role:admin')->group(function () {
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('projects', ProjectController::class);
        Route::group(['prefix' => 'projects/{project}'], function () {
            Route::apiResource('device-types', DeviceTypeController::class);
            Route::group(['prefix' => 'device-types/{device_type}'], function () {
                Route::apiResource('modules', ModuleController::class);
                Route::apiResource('devices', DeviceController::class);
            });
        });
    });
    
    Route::middleware('role:user')->group(function () {
        Route::prefix('gardens')->group(function () {
            Route::apiResource('', GardenController::class);
            Route::apiResource('devices', GardenDeviceController::class);
            Route::apiResource('device-types', GardenDeviceTypeController::class);
        });
    });
});
