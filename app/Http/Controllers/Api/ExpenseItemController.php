<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseItemRequest;
use App\Http\Requests\UpdateExpenseItemRequest;
use App\Http\Resources\ExpenseItemcollection;
use App\Http\Resources\ReturnResponseResource;
use App\Http\Resources\ShowExpenseItemResource;
use App\Models\ExpenseItem;
use Illuminate\Http\Request;

class ExpenseItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $expense_id = $request->expense;
        $expenseItems = ExpenseItem::where('expense_id' , $expense_id)->get();
        return new ExpenseItemcollection($expenseItems);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseItemRequest $request)
    {
        return new ShowExpenseItemResource(ExpenseItem::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseItemRequest $request, string $id)
    {
        $expenseItem = ExpenseItem::find($id);
        if(!$expenseItem){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ],404);
        }
        $expenseItem->update([
            'comment' => $request->comment ,
            'date' => $request->date ,
            'summa' => $request->summa,
            'expense_id' => $request->expense_id
        ]);
        return new ShowExpenseItemResource($expenseItem);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expenseItem = ExpenseItem::find($id);
        if(!$expenseItem){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ],404);
        }
        $expenseItem->delete();
        return new ReturnResponseResource([
            'code' => 200 ,
            'message' => 'Record has been deleted successfully!'
        ]);
    }
}
