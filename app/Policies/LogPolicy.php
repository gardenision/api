<?php

namespace App\Policies;

use App\Models\Device;
use App\Models\Garden;
use App\Models\GardenDevice;
use App\Models\GardenDeviceModule;
use App\Models\Log;
use App\Models\Module;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LogPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Log $log): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Device $device, Module $module, GardenDeviceModule $garden_device_module): bool
    {
        if (! $device->type->modules()->find($module->id)) {
            return false;
        }

        if ($garden_device_module->module_id !== $module->id) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Log $log): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Log $log): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Log $log): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Log $log): bool
    {
        return false;
    }
}
