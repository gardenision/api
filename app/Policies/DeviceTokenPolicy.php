<?php

namespace App\Policies;

use App\Models\Device;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use App\Models\DeviceToken;

class DeviceTokenPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role?->role?->name ?? '', ['admin']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role?->role?->name ?? '', ['admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DeviceToken $deviceToken): bool
    {
        return in_array($user->role?->role?->name ?? '', ['admin']);
    }
}
