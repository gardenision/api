<?php
namespace App\Http\Controllers;

use App\Http\Requests\Role\DestroyRequest;
use App\Http\Requests\Role\IndexRequest;
use App\Http\Requests\Role\StoreRequest;
use App\Http\Requests\Role\UpdateRequest;
use App\Models\Role;

class RoleController extends Controller
{
    public function index(IndexRequest $request)
    {
        return response()->json(Role::all());
    }

    public function store(StoreRequest $request)
    {
        $role = Role::create($request->validated());
        return response()->json($role, 201);
    }

    public function show(Role $role)
    {
        return response()->json($role);
    }

    public function update(UpdateRequest $request, Role $role)
    {
        $role->update($request->validated());
        return response()->json($role);
    }

    public function destroy(DestroyRequest $request, Role $role)
    {
        $role->delete();
        return response()->json(null, 204);
    }
}
