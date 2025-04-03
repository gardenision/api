<?php
namespace App\Policies;

use App\Models\DeviceType;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeviceTypePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return in_array($user->role?->role?->name ?? '', ['admin']);
    }

    public function view(User $user, DeviceType $deviceType)
    {
        return in_array($user->role?->role?->name ?? '', ['admin']);
    }

    public function create(User $user)
    {
        return in_array($user->role?->role?->name ?? '', ['admin']);
    }

    public function update(User $user, DeviceType $deviceType)
    {
        return in_array($user->role?->role?->name ?? '', ['admin']);
    }

    public function delete(User $user, DeviceType $deviceType)
    {
        return in_array($user->role?->role?->name ?? '', ['admin']);
    }
}
