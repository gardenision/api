<?php
namespace App\Policies;

use App\Models\Module;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModulePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return in_array($user->role?->role?->name ?? '', ['admin']);
    }

    public function view(User $user, Module $module)
    {
        return in_array($user->role?->role?->name ?? '', ['admin']);
    }

    public function create(User $user)
    {
        return in_array($user->role?->role?->name ?? '', ['admin']);
    }

    public function update(User $user, Module $module)
    {
        return in_array($user->role?->role?->name ?? '', ['admin']);
    }

    public function delete(User $user, Module $module)
    {
        return in_array($user->role?->role?->name ?? '', ['admin']);
    }
}
