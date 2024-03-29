<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHireWorkerExpenseRequest;
use App\Http\Requests\UpdateHiredWorkerExpenseRequest;
use App\Http\Resources\HiredWorkerExpenseCollection;
use App\Http\Resources\ReturnResponseResource;
use App\Http\Resources\ShowHiredWorkerExpenseResource;
use App\Models\HiredWorker;
use App\Models\HiredWorkerExpense;
use Illuminate\Http\Request;

class HiredWorkerExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalAmount = HiredWorkerExpense::sum('amount');
        return response()->json([
            'message' => 'Yollanma ishchilarga berilgan umumiy pul mablag\'lari. ',
            'total_amount' => $totalAmount
        ]);
    }

    public function getByWorker(string $worker_id){
        $hiredWorker = HiredWorker::find($worker_id);
        if (!$hiredWorker){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }
        $expenses = HiredWorkerExpense::where('hired_worker_id' , $worker_id)->orderByDesc('date')->get();

        return new HiredWorkerExpenseCollection($expenses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHireWorkerExpenseRequest $request)
    {
        $expense = HiredWorkerExpense::create([
            'summa' => $request->summa ,
            'date' => $request->date ,
            'comment' => $request->comment ,
            'hired_worker_id' => $request->hired_worker_id ,
            'currency' => $request->currency ,
            'currency_rate' => $request->currency_rate ,
            'amount' => $request->summa * $request->currency_rate,
        ]);

        return new ShowHiredWorkerExpenseResource($expense);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $expense = HiredWorkerExpense::find($id);
        if(!$expense){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }

       return new ShowHiredWorkerExpenseResource($expense);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHiredWorkerExpenseRequest $request, string $id)
    {
        $expense = HiredWorkerExpense::find($id);
        if(!$expense){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }

        $expense->update([
            'summa' => $request->summa ,
            'date' => $request->date ,
            'comment' => $request->comment ,
            'hired_worker_id' => $request->hired_worker_id ,
            'currency' => $request->currency ,
            'currency_rate' => $request->currency_rate ,
            'amount' => $request->summa * $request->currency_rate,
        ]);
        return new ShowHiredWorkerExpenseResource($expense);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expense = HiredWorkerExpense::find($id);
        if(!$expense){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }

        $expense->delete();
        return new ReturnResponseResource([
            'code' => 201 ,
            'message' => 'Record has been deleted successfully!'
        ]);
    }
}
