<?php

namespace App\Policies;

use App\Models\Device;
use App\Models\Setting;
use App\Models\User;

class DeviceSettingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Device $device): bool
    {
        $role = $user->role?->role?->name;
        if (! in_array($role ?? '', ['admin', 'user'])) return false;
        if (in_array($role ?? '', ['user']) && $device->user_id != $user->id) return false;
        
        return true;
    }

    public function viewAnyNotActive(Device $device): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Setting $setting): bool
    {
        $role = $user->role?->role?->name;
        if (! in_array($role ?? '', ['admin', 'user'])) return false;
        if (in_array($role ?? '', ['user']) && $setting->tokenable->user_id != $user->id) return false;
        
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Device $device): bool
    {
        $role = $user->role?->role?->name;
        if (! in_array($role ?? '', ['admin', 'user'])) return false;
        if (in_array($role ?? '', ['user']) && $device->user_id != $user->id) return false;
        
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Setting $setting): bool
    {
        $role = $user->role?->role?->name;
        if (! in_array($role ?? '', ['admin', 'user'])) return false;
        if (in_array($role ?? '', ['user']) && $setting->settingable->user_id != $user->id) return false;
        
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Setting $setting): bool
    {
        $role = $user->role?->role?->name;
        if (! in_array($role ?? '', ['admin', 'user'])) return false;
        if (in_array($role ?? '', ['user']) && $setting->settingable->user_id != $user->id) return false;
        
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Setting $setting): bool
    {
        $role = $user->role?->role?->name;
        if (! in_array($role ?? '', ['admin', 'user'])) return false;
        if (in_array($role ?? '', ['user']) && $setting->settingable->user_id != $user->id) return false;
        
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Setting $setting): bool
    {
        $role = $user->role?->role?->name;
        if (! in_array($role ?? '', ['admin', 'user'])) return false;
        if (in_array($role ?? '', ['user']) && $setting->settingable->user_id != $user->id) return false;
        
        return true;
    }
}
