<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ReturnResponseResource;
use App\Http\Resources\ShowProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::orderByDesc('created_at')->get();
        return new ProjectCollection($projects);
    }

    public function allData(){
        $projects = Project::select('id' , 'name')->get();

        return response()->json([
            'data' => $projects
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        return new ShowProjectResource(Project::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::find($id);
        if(!$project){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }

        return new ShowProjectResource($project);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, string $id)
    {
        $project = Project::find($id);
        if(!$project){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }

        $project->update([
            'name' => $request->name ,
            'state' => $request->state ,
            'image_name' => $request->image_name ,
            'image_url' => $request->image_url ,
        ]);

        return new ShowProjectResource($project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::find($id);
        if(!$project){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }
        $project->delete();
        return new ReturnResponseResource([
            'code' => 201 ,
            'message' => 'Record has been deleted successfully!'
        ]);
    }
}
