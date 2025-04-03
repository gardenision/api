<?php

namespace App\Policies;

use App\Models\Device;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DevicePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return in_array($user->role?->role?->name ?? '', ['admin']);
    }

    public function view(User $user, Device $device)
    {
        return in_array($user->role?->role?->name ?? '', ['admin']);
    }

    public function create(User $user)
    {
        return in_array($user->role?->role?->name ?? '', ['admin']);
    }

    public function update(User $user, Device $device)
    {
        return in_array($user->role?->role?->name ?? '', ['admin']);
    }

    public function delete(User $user, Device $device)
    {
        return in_array($user->role?->role?->name ?? '', ['admin']);
    }
}
