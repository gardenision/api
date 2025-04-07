<?php

namespace App\Policies;

use App\Models\Garden;
use App\Models\GardenDevice;
use App\Models\GardenDeviceModule;
use App\Models\Module;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GardenDeviceModulePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role?->role?->name ?? '', ['admin', 'user']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Garden $garden, GardenDevice $garden_device, GardenDeviceModule $garden_device_module): bool
    {
        $role = $user->role?->role?->name;
        if (! in_array($role ?? '', ['admin', 'user'])) return false;
        if (in_array($role ?? '', ['user']) && $garden->user_id != $user->id && $garden_device->garden_id != $garden->id && $garden_device_module->garden_device_id != $garden_device->id) return false;
        
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Garden $garden, Module $module, GardenDevice $garden_device): bool
    {
        $role = $user->role?->role?->name;
        if (! in_array($role ?? '', ['admin', 'user'])) return false;
        if (in_array($role ?? '', ['user']) && $garden->user_id != $user->id && $garden_device->garden_id != $garden->id && $module->device_type_id != $garden_device->device->device_type_id) return false;
        
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Garden $garden, GardenDevice $garden_device, GardenDeviceModule $garden_device_module): bool
    {
         $role = $user->role?->role?->name;
        if (! in_array($role ?? '', ['admin', 'user'])) return false;
        if (in_array($role ?? '', ['user']) && $garden->user_id != $user->id && $garden_device->garden_id != $garden->id && $garden_device_module->garden_device_id != $garden_device->id) return false;
        
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Garden $garden, GardenDevice $garden_device, GardenDeviceModule $garden_device_module): bool
    {
         $role = $user->role?->role?->name;
        if (! in_array($role ?? '', ['admin', 'user'])) return false;
        if (in_array($role ?? '', ['user']) && $garden->user_id != $user->id && $garden_device->garden_id != $garden->id && $garden_device_module->garden_device_id != $garden_device->id) return false;
        
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Garden $garden, GardenDevice $garden_device, GardenDeviceModule $garden_device_module): bool
    {
         $role = $user->role?->role?->name;
        if (! in_array($role ?? '', ['admin', 'user'])) return false;
        if (in_array($role ?? '', ['user']) && $garden->user_id != $user->id && $garden_device->garden_id != $garden->id && $garden_device_module->garden_device_id != $garden_device->id) return false;
        
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Garden $garden, GardenDevice $garden_device, GardenDeviceModule $garden_device_module): bool
    {
         $role = $user->role?->role?->name;
        if (! in_array($role ?? '', ['admin', 'user'])) return false;
        if (in_array($role ?? '', ['user']) && $garden->user_id != $user->id && $garden_device->garden_id != $garden->id && $garden_device_module->garden_device_id != $garden_device->id) return false;
        
        return true;
    }
}
