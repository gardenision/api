<?php

use App\Http\Controllers\{
    AnalyticController,
    AuthController,
    RoleController, ProjectController, DeviceTypeController, 
    ModuleController, DeviceController, DeviceSettingController, DeviceTokenController, GardenController,
    GardenDeviceController,
    GardenDeviceModuleController,
    GardenDeviceTypeController,
    LogController,
    SettingController
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
        Route::get('project', [AnalyticController::class, 'adminDashbaord'])->name('admin.dashboard');
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('projects', ProjectController::class)->except(['destroy']);
        Route::group(['prefix' => 'projects/{project}'], function () {
            Route::apiResource('device-types', DeviceTypeController::class)->except(['destroy']);
            Route::group(['prefix' => 'device-types/{device_type}'], function () {
                Route::apiResource('modules', ModuleController::class)->except(['destroy']);
                Route::apiResource('devices', DeviceController::class)->except(['destroy']);
                Route::group(['prefix' => 'devices/{device}'], function () {
                    Route::apiResource('tokens', DeviceTokenController::class)->except(['show', 'update']);
                });
            });
        });
    });
    
    Route::middleware('role:user,admin')->group(function () {
        Route::get('garden', [AnalyticController::class, 'userDashbaord'])->name('user.dashboard');
        Route::apiResource('gardens', GardenController::class)->except(['show', 'destroy']);
        Route::prefix('gardens')->group(function () {
            Route::prefix('{garden}')->group(function () {
                Route::prefix('devices')->group(function () {
                    Route::apiResource('', GardenDeviceController::class)->except(['show', 'update', 'destroy']);
                    Route::prefix('{garden_device}')->group(function () {
                        Route::prefix('modules')->group(function () {
                            Route::get('analytics', [AnalyticController::class, 'module']);
                            Route::prefix('use/{module}')->group(function () {
                                Route::post('', [GardenDeviceModuleController::class, 'store']);
                            });
                            Route::prefix('{garden_device_module}')->group(function () {
                                Route::put('', [GardenDeviceModuleController::class, 'update']);
                            });
                            Route::apiResource('', GardenDeviceModuleController::class)->except(['store', 'show', 'destroy']);
                        });
                        Route::apiResource('settings', DeviceSettingController::class)->except(['show']);
                        Route::get('analytics', [AnalyticController::class, 'device']);
                    });
                });
            });
        });
    });

    Route::get('device/{serial_number}/settings', [DeviceSettingController::class, 'show_not_activate'])->middleware('auth.device');
    Route::post('device/{serial_number}/logs', [LogController::class, 'store'])->middleware('auth.device');
    Route::post('device/{serial_number}/module/{module}/logs', [LogController::class, 'store'])->middleware('auth.device');
});
