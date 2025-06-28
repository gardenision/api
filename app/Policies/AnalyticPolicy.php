<?php

namespace App\Policies;

use App\Models\Analytic;
use App\Models\Garden;
use App\Models\GardenDevice;
use App\Models\GardenDeviceModule;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AnalyticPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, string ...$roles): bool
    {
        $role = $user->role?->role?->name;

        if (! in_array($role ?? '', $roles)) return false;

        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Analytic $analytic): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Analytic $analytic): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Analytic $analytic): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Analytic $analytic): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Analytic $analytic): bool
    {
        return false;
    }
}
