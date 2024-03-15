<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDayOffRequest;
use App\Http\Requests\UpdateDayOffRequest;
use App\Http\Resources\DayOffCollection;
use App\Http\Resources\ReturnResponseResource;
use App\Models\DayOff;
use App\Models\WorkerAccount;
use Illuminate\Http\Request;

class DayOffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $w_account = WorkerAccount::where('status','working')->where('worker_id',$request->worker_id)->latest()->first();
        $dayOffs = DayOff::where('worker_account_id', $w_account->id)->get();
        return new DayOffCollection($dayOffs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDayOffRequest $request)
    {
        $w_account = WorkerAccount::where('status','working')->where('worker_id',$request->worker_id)->latest()->first();
        if(!$w_account){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => "Worker is not working!"
            ] , 404);
        }
        DayOff::create([
            'date' => $request->date,
            'quantity' => $request->quantity,
            'worker_account_id' => $w_account->id,
        ]);
        return new ReturnResponseResource([
            'code' => 200,
            'message' => "Worker day off added successfuly!"
        ] , 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDayOffRequest $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dayOff = DayOff::find($id);
        if(!$dayOff){
            return new ReturnResponseResource([
               'code' => 404 ,
               'message' => 'Record not found!'
            ] ,404);
        }
        $dayOff->delete();
        return new ReturnResponseResource([
            'code' => 200 ,
            'message' => 'Record has been deleted successfully!'
        ]);
    }
}
