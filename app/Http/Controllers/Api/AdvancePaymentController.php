<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdvancePaymentRequest;
use App\Http\Requests\UpdateAdvancePaymentRequest;
use App\Http\Resources\AdvancePaymentCollection;
use App\Http\Resources\ReturnResponseResource;
use App\Http\Resources\ShowAdvancePaymentResource;
use App\Models\AdvancePayment;
use App\Models\WorkerAccount;
use Illuminate\Http\Request;

class AdvancePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $w_account = WorkerAccount::where('status','working')->where('worker_id',$request->worker_id)->latest()->first();
       $advancePayments = [];
        if($w_account){
            $advancePayments = AdvancePayment::where('worker_account_id' ,$w_account->id)->get();
        }

        return new AdvancePaymentCollection($advancePayments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdvancePaymentRequest $request)
    {
        $w_account = WorkerAccount::where('status','working')->where('worker_id',$request->worker_id)->latest()->first();
        if(!$w_account){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => "Worker is not working!"
            ] , 404);
        }
        AdvancePayment::create([
            'amount' => $request->amount,
            'date' => $request->date,
            'type' => $request->type,
            'worker_account_id' => $w_account->id
        ]);
        return new ReturnResponseResource([
            'code' => 200,
            'message' => "Worker payment added successfuly!"
        ] , 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $advancePayment = AdvancePayment::find($id);
        if(!$advancePayment){
            return new ReturnResponseResource([
               'code' => 404 ,
               'message' => 'Record not found!'
            ],404);
        }

        return new ShowAdvancePaymentResource($advancePayment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdvancePaymentRequest $request, string $id)
    {
        $advancePayment = AdvancePayment::find($id);
        if(!$advancePayment){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] ,404);
        }
        $advancePayment->update([
            'amount' => $request->amount,
            'date' => $request->date,
            'type' => $request->type,
        ]);
        return new ShowAdvancePaymentResource($advancePayment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $advancePayment = AdvancePayment::find($id);
        if(!$advancePayment){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] ,404);
        }
        $advancePayment->delete();
        return new ReturnResponseResource([
            'code' => 200,
            'message' => 'Record has been deleted successfully!'
        ] , 200);
    }
}
