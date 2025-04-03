<?php
namespace App\Http\Controllers;

use App\Http\Requests\Project\DestroyRequest;
use App\Http\Requests\Project\IndexRequest;
use App\Http\Requests\Project\StoreRequest;
use App\Http\Requests\Project\UpdateRequest;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index(IndexRequest $request)
    {
        return response()->json(Project::all());
    }

    public function store(StoreRequest $request)
    {
        $project = Project::create($request->validated());
        return response()->json($project, 201);
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

    public function destroy(DestroyRequest $request, $project)
    {
        $request->project->delete();
        return response()->json(null, 204);
    }
}
