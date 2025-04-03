<?php

namespace App\Policies;

use App\Models\Garden;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GardenPolicy
{
    private $role;

    public function __construct(User $user)
    {
        $this->role = $user->role()->first();
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->role ? in_array($this->role->name, ['admin', 'user']) : false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Garden $garden): bool
    {
        return $this->role ? in_array($this->role->name, ['admin', 'user']) : false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->role ? in_array($this->role->name, ['admin', 'user']) : false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Garden $garden): bool
    {
        return $this->role ? in_array($this->role->name, ['admin', 'user']) : false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Garden $garden): bool
    {
        return $this->role ? in_array($this->role->name, ['admin', 'user']) : false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Garden $garden): bool
    {
        return $this->role ? in_array($this->role->name, ['admin', 'user']) : false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Garden $garden): bool
    {
        return $this->role ? in_array($this->role->name, ['admin', 'user']) : false;
    }
}
