<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContractRequest;
use App\Http\Resources\ContrctCollection;
use App\Http\Resources\ReturnResponseResource;
use App\Http\Resources\ShowContractResource;
use App\Models\Contract;
use App\Models\Project;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function filterData(Request $request)
    {
        $contracts = Contract::orderByDesc('created_at')->paginate(10);

        $project_id = $request->project_id;
        $project = Project::find($project_id);
        if($project){
            $contracts = Contract::where('project_id' , $project->id)->orderByDesc('created_at')->get();
        }

        return new ContrctCollection($contracts);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContractRequest $request)
    {
        return new ShowContractResource(Contract::create([
            'block' => $request->block ,
            'currency' => $request->currency ,
            'project_id' => $request->project_id ,
        ]));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contract = Contract::find($id);
        if(!$contract){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }

        return new ShowContractResource($contract);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $contract = Contract::find($id);
        if(!$contract){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }
        $contract->update([
            'block' => $request->block ,
            'currency' => $request->currency ,
            'project_id' => $request->project_id ,
        ]);

        return new ShowContractResource($contract);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contract = Contract::find($id);
        if(!$contract){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }

        $contract->delete();

        return new ReturnResponseResource([
            'code' => 201 ,
            'message' => 'Record has been deleted successfully!'
        ]);

    }
}
