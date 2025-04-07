<?php
namespace App\Http\Controllers;

use App\Http\Requests\Garden\DestroyRequest;
use App\Http\Requests\Garden\IndexRequest;
use App\Http\Requests\Garden\StoreRequest;
use App\Http\Requests\Garden\UpdateRequest;
use App\Models\Garden;

class GardenController extends Controller
{
    public function index(IndexRequest $request)
    {
        $user = $request->user();
        $gardens = $user->gardens()->get();
        return response()->json($gardens);
    }

    public function store(StoreRequest $request)
    {
        $user = $request->user();

        $garden = Garden::create([
            'name' => $request->name,
            'latitude' => $request->latitude ?? null,
            'longitude' => $request->longitude ?? null,
            'user_id' => $user->id,
        ]);

        return response()->json($garden, 201);
    }

    public function show(Garden $garden)
    {
        return response()->json($garden);
    }

    public function update(UpdateRequest $request, Garden $garden)
    {
        $garden->update($request->validated());
        return response()->json($garden);
    }

    public function destroy(DestroyRequest $request, Garden $garden)
    {
        $garden->delete();
        return response()->json(null, 204);
    }
}
