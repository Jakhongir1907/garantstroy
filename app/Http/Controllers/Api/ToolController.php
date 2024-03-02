<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreToolRequest;
use App\Http\Resources\FilterToolCollection;
use App\Http\Resources\ReturnResponseResource;
use App\Http\Resources\ShowToolResource;
use App\Http\Resources\ToolCollection;
use App\Models\Tool;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function Symfony\Component\Translation\t;

class ToolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tools = Tool::orderByDesc('created_at')->paginate(10);
        return new ToolCollection($tools);
    }

    public function filterData(Request $request)
    {
        $tools = Tool::orderByDesc('created_at')->with('project')->paginate(10);
        $state = $request->state;
        $project_id = $request->project_id;
        if(!empty($state) && empty($project_id)){
            $tools = Tool::where('state' , $state)->orderByDesc('created_at')->with('project')->get();
        }elseif(empty($state) && !empty($project_id)){
            $tools = Tool::where('project_id' , $project_id)->orderByDesc('created_at')->with('project')->get();
        }elseif(!empty($state) && !empty($project_id)){
            $tools = Tool::where('project_id' , $project_id)->where('state' , $state)->orderByDesc('created_at')->with('project')->get();
        }
        return new FilterToolCollection($tools);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreToolRequest $request)
    {
        return new ShowToolResource(Tool::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tool = Tool::find($id);
        if(!$tool){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => "Record not found!"
            ]);
        }

        return new ShowToolResource($tool);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tool = Tool::find($id);
        if(!$tool){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => "Record not found!"
            ]);
        }
        $tool->update([
            'name' => $request->name  ,
            'price' => $request->price  ,
            'state' => $request->state  ,
            'image_name' => $request->image_name ,
            'image_url' => $request->image_url ,
            'project_id' => $request->project_id
        ]);

        return new ShowToolResource($tool);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tool = Tool::find($id);
        if(!$tool){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => "Record not found!"
            ]);
        }
        $tool->delete();
        return new ReturnResponseResource([
            'code' => 201 ,
            'message' => 'Record has been deleted successfully!'
        ]);
    }
}
