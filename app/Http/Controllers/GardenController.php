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
        return response()->json(Garden::all());
    }

    public function store(StoreRequest $request)
    {
        $garden = Garden::create($request->validated());
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
