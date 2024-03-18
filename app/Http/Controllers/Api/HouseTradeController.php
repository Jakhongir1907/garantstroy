<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHouseTradeRequest;
use App\Http\Requests\UpdateHouseTradeRequest;
use App\Http\Resources\HouseTradeCollection;
use App\Http\Resources\ReturnResponseResource;
use App\Http\Resources\ShowHouseTradeResource;
use App\Models\HouseTrade;
use Illuminate\Http\Request;

class HouseTradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $houseTrades = HouseTrade::orderByDesc('created_at')->get();
        return new HouseTradeCollection($houseTrades);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHouseTradeRequest $request)
    {
        return new ShowHouseTradeResource(HouseTrade::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $houseTrade = HouseTrade::find($id);
        if(!$houseTrade){
            return new ReturnResponseResource([
               'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }
        return new ShowHouseTradeResource($houseTrade);
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHouseTradeRequest $request, string $id)
    {
        $houseTrade = HouseTrade::find($id);
        if(!$houseTrade){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }
        $houseTrade->update([
            'name' => $request->name ,
            'image_name' => $request->image_name ,
            'image_url' => $request->image_url ,
            'address' => $request->address ,
        ]);
        return new ShowHouseTradeResource($houseTrade);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $houseTrade = HouseTrade::find($id);
        if(!$houseTrade){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }
        if($houseTrade->houseTradeExpenses()->count() > 0){
            return new ReturnResponseResource([
                'code' => 403 ,
                'message' => "You can not delete this item!"
            ], 403);
        }
        $houseTrade->delete();
        return new ReturnResponseResource([
            'code' => 201 ,
            'message' => 'Record has been deleted successfully!'
        ]);
    }
}
