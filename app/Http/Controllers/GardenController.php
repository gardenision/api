<?php
namespace App\Http\Controllers;

use App\Events\AddGarden;
use App\Http\Requests\Garden\DestroyRequest;
use App\Http\Requests\Garden\IndexRequest;
use App\Http\Requests\Garden\StoreRequest;
use App\Http\Requests\Garden\UpdateRequest;
use App\Models\Garden;
use Illuminate\Support\Facades\DB;

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
        try {
            DB::beginTransaction();

            $duplicate = Garden::where('name', $request->name)->where('user_id', $request->user()->id)->first();
            if ($duplicate) {
                DB::rollBack();
                return response()->json(['message' => 'Garden name already exists'], 409);
            }
            
            $user = $request->user();

            $garden = Garden::create([
                'name' => $request->name,
                'latitude' => $request->latitude ?? null,
                'longitude' => $request->longitude ?? null,
                'user_id' => $user->id,
            ]);

            event(new AddGarden($garden));

            DB::commit();

            return response()->json($garden, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->log("ERROR GardenController::store : " . json_encode($e));

            throw $e;
        }
    }

    public function show(Garden $garden)
    {
        return response()->json($garden);
    }

    public function update(UpdateRequest $request, Garden $garden)
    {
        $duplicate = Garden::where('name', $request->name)->where('user_id', $request->user()->id)->where('id', '!=', $garden->id)->first();
        if ($duplicate) {
            return response()->json(['message' => 'Garden name already exists'], 409);
        }
        
        $garden->update($request->validated());
        return response()->json($garden);
    }

    // public function destroy(DestroyRequest $request, Garden $garden)
    // {
    //     try {
    //         DB::beginTransaction();

    //         $tmp_garden = $garden;
    //         $garden->delete();
            
    //         event(new AddGarden($tmp_garden));
            
    //         DB::commit();
    //         return response()->json(null, 204);
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         $this->log("ERROR GardenController::destroy : " . json_encode($e));

    //         throw $e;
    //     }
    // }
}
