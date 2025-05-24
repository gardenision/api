<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\EditRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function user(Request $request)
    {
        $user = $request->user()->load('role.role')->toArray();
        $user['role'] = $user['role']['role'] ?? null;
        
        return response()->json([
            'data' => $user,
        ]);
    }

    /**
     * Edit the specified resource.
     */
    public function edit(EditRequest $request)
    {
        $updated = $request->validated();

        $request->user()->update($updated);

        return response()->json([
            'data' => $request->user(),
        ]);
    }

    /**
     * Delete the specified resource.
     */
    public function delete(Request $request)
    {
        $request->user()->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ]);
    }
}
