<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHiredWorkerRequest;
use App\Http\Requests\UpdateHiredWorkerRequest;
use App\Http\Resources\HiredWorkerCollection;
use App\Http\Resources\ReturnResponseResource;
use App\Http\Resources\ShowHiredWorkerResource;
use App\Models\HiredWorker;
use App\Models\Project;
use Illuminate\Http\Request;

class HiredWorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hiredWorkers = HiredWorker::orderByDesc('created_at')->paginate(10);

        return new HiredWorkerCollection($hiredWorkers);
    }
    public function filterData(Request $request)
    {
        $hiredWorkers = HiredWorker::orderByDesc('created_at')->paginate(10);
        $project_id = $request->project_id;
        $project = Project::find($project_id);
        if($project){
            $hiredWorkers = HiredWorker::where('project_id' , $project->id)->orderByDesc('created_at')->get();
        }
        return new HiredWorkerCollection($hiredWorkers);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHiredWorkerRequest $request)
    {
        $hiredWorker = HiredWorker::create([
            'name' => $request->name ,
            'phone_number' => $request->phone_number ,
            'comment' => $request->comment ,
            'project_id' => $request->project_id ,
        ]);
        return new ShowHiredWorkerResource($hiredWorker);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $hiredWorker = HiredWorker::find($id);
        if(!$hiredWorker){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }

        return new ShowHiredWorkerResource($hiredWorker);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHiredWorkerRequest $request, string $id)
    {
        $hiredWorker = HiredWorker::find($id);
        if(!$hiredWorker){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }
        $hiredWorker->update([
            'name' => $request->name ,
            'phone_number' => $request->phone_number ,
            'comment' => $request->comment ,
            'project_id' => $request->project_id ,
        ]);
        return new ShowHiredWorkerResource($hiredWorker);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $hiredWorker = HiredWorker::find($id);
        if(!$hiredWorker){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }
        $hiredWorker->delete();
        return new ReturnResponseResource([
            'code' => 201 ,
            'message' => 'Record has been deleted successfully!'
        ]);
    }
}
