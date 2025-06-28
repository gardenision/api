<?php
namespace App\Http\Controllers;

use App\Events\AddProject;
use App\Events\SubProject;
use App\Http\Requests\Project\DestroyRequest;
use App\Http\Requests\Project\IndexRequest;
use App\Http\Requests\Project\StoreRequest;
use App\Http\Requests\Project\UpdateRequest;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index(IndexRequest $request)
    {
        return response()->json(Project::all());
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $project = Project::create($request->validated());
            event(new AddProject($project));

            DB::commit();
            return response()->json($project, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->log("ERROR ProjectController::store : " . json_encode($e));

            throw $e;
        }
    }

    public function show(Project $project)
    {
        return response()->json($project);
    }

    public function update(UpdateRequest $request, Project $project)
    {
        $project->update($request->validated());
        return response()->json($project);
    }

    // public function destroy(DestroyRequest $request, $project)
    // {
    //     try {
    //         DB::beginTransaction();

    //         $tmp_project = $request->project;

    //         $request->project->delete();

    //         event(new SubProject($tmp_project));
            
    //         DB::commit();
    //         return response()->json(null, 204);
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         $this->log("ERROR ProjectController::destroy : " . json_encode($e));

    //         throw $e;
    //     }
    // }
}
