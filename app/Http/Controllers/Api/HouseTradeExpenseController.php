<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHouseTradeExpenseRequest;
use App\Http\Requests\UpdateHouseTradeExpenseRequest;
use App\Http\Resources\HouseTradeExpenseCollection;
use App\Http\Resources\ReturnResponseResource;
use App\Http\Resources\ShowTradeHouseExpenseResource;
use App\Models\HouseTrade;
use App\Models\HouseTradeExpense;
use Illuminate\Http\Request;

class HouseTradeExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function getByHouseTrade(string $trade_id){
        $houseTrade = HouseTrade::find($trade_id);
        if(!$houseTrade){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }

        $houseTradeExpenses = HouseTradeExpense::where('house_trade_id' , $trade_id)->orderByDesc('date')->get();

        return new HouseTradeExpenseCollection($houseTradeExpenses);
    }
    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHouseTradeExpenseRequest $request)
    {
        $houseTradeExpense = HouseTradeExpense::create([
            'summa' => $request->summa ,
            'date' => $request->date ,
            'comment' => $request->comment ,
            'currency' => $request->currency ,
            'currency_rate' => $request->currency_rate ,
            'amount' => $request->summa * $request->currency_rate,
            'house_trade_id' => $request->house_trade_id ,
        ]);

        return new ShowTradeHouseExpenseResource($houseTradeExpense);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $houseTradeExpense = HouseTradeExpense::find($id);

        if(!$houseTradeExpense){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }
        return new ShowTradeHouseExpenseResource($houseTradeExpense);
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHouseTradeExpenseRequest $request, string $id)
    {
        $houseTradeExpense = HouseTradeExpense::find($id);

        if(!$houseTradeExpense){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }
        $houseTradeExpense->update([
            'summa' => $request->summa ,
            'date' => $request->date ,
            'comment' => $request->comment ,
            'currency' => $request->currency ,
            'currency_rate' => $request->currency_rate ,
            'amount' => $request->summa * $request->currency_rate,
            'house_trade_id' => $request->house_trade_id ,
        ]);

        return new ShowTradeHouseExpenseResource($houseTradeExpense);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $houseTradeExpense = HouseTradeExpense::find($id);

        if(!$houseTradeExpense){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }
        $houseTradeExpense->delete();

        return new ReturnResponseResource([
            'code' => 201 ,
            'message' => 'Record has been deleted successfully!'
        ]);
    }
}
