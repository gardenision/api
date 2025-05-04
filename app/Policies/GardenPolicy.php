<?php

namespace App\Policies;

use App\Models\Garden;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GardenPolicy
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
    public function view(User $user, Garden $garden): bool
    {
        $role = $user->role?->role?->name;
        if (! in_array($role ?? '', ['admin', 'user'])) return false;
        if (in_array($role ?? '', ['user']) && $garden->user_id != $user->id) return false;
        
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $role = $user->role?->role?->name;
        if (! in_array($role ?? '', ['admin', 'user'])) return false;
        
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Garden $garden): bool
    {
        $role = $user->role?->role?->name;
        if (! in_array($role ?? '', ['admin', 'user'])) return false;
        if (in_array($role ?? '', ['user']) && $garden->user_id != $user->id) return false;
        
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Garden $garden): bool
    {
        $role = $user->role?->role?->name;
        if (! in_array($role ?? '', ['admin', 'user'])) return false;
        if (in_array($role ?? '', ['user']) && $garden->user_id != $user->id) return false;
        
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Garden $garden): bool
    {
        $role = $user->role?->role?->name;
        if (! in_array($role ?? '', ['admin', 'user'])) return false;
        if (in_array($role ?? '', ['user']) && $garden->user_id != $user->id) return false;
        
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Garden $garden): bool
    {
        $role = $user->role?->role?->name;
        if (! in_array($role ?? '', ['admin', 'user'])) return false;
        if (in_array($role ?? '', ['user']) && $garden->user_id != $user->id) return false;
        
        return true;
    }
}
