<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContractRequest;
use App\Http\Requests\UpdateContractFloorRequest;
use App\Http\Resources\ContractFloorCollection;
use App\Http\Resources\ReturnResponseResource;
use App\Http\Resources\ShowContractFloorResource;
use App\Models\Contract;
use App\Models\ContractFloor;
use Illuminate\Http\Request;

class ContractFloorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function filterData(string $contract_id)
    {
        $contract = Contract::find($contract_id);
        if (!$contract){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }
        $floors = ContractFloor::where('contract_id' , $contract_id)->orderBy('created_at')->get();

        return new ContractFloorCollection($floors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContractRequest $request)
    {
        return new ShowContractFloorResource(ContractFloor::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $floor = ContractFloor::find($id);
        if(!$floor){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }

        return new ShowContractFloorResource($floor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContractFloorRequest $request, string $id)
    {
        $floor = ContractFloor::find($id);
        if(!$floor){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }

        $floor->update([
            'price' => $request->price ,
            'floor' => $request->floor ,
            'square' => $request->square ,
            'contract_id' => $request->contract_id ,
        ]);

        return new ShowContractFloorResource($floor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $floor = ContractFloor::find($id);
        if(!$floor){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }

        $floor->delete();

        return new ReturnResponseResource([
            'code' => 201 ,
            'message' => 'Record has been deleted successfully!'
        ]);
    }
}
