<?php
namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user)
    {
        return in_array($user->role?->role?->name ?? '', ['admin']);
    }

    public function view(User $user, Project $project)
    {
        return in_array($user->role?->role?->name ?? '', ['admin']);
    }

    public function create(User $user)
    {
        return in_array($user->role?->role?->name ?? '', ['admin']);
    }

    public function update(User $user, Project $project)
    {
        return in_array($user->role?->role?->name ?? '', ['admin']);
    }

    public function delete(User $user, Project $project)
    {
        return in_array($user->role?->role?->name ?? '', ['admin']);
    }
}
