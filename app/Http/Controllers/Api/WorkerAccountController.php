<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkerAccountRequest;
use App\Http\Requests\UpdateAdvancePaymentRequest;
use App\Http\Requests\UpdateWorkerAccountRequest;
use App\Http\Resources\ReturnResponseResource;
use App\Http\Resources\ShowWorkerAccountResource;
use App\Http\Resources\WorkerAccountCollection;
use App\Models\Worker;
use App\Models\WorkerAccount;
use Illuminate\Http\Request;

class WorkerAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $workerAccounts = WorkerAccount::where('worker_id',$request->worker_id)->latest()->paginate(5);
        return new WorkerAccountCollection($workerAccounts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkerAccountRequest $request)
    {
        $workerAccount = WorkerAccount::where('worker_id', $request->worker_id)->latest()->first();
        if($workerAccount){
            if($workerAccount->status=='working'){
                return new ReturnResponseResource([
                    'code' => 404 ,
                    'message' => 'Bu ishchi haliyam ishlayapdi.Oldin ishdan xaydab yuboring!'
                ]);
            }
        }

        $worker = Worker::find($request->worker_id);
        $workerAccount1 = new WorkerAccount();

        $workerAccount1->worker_id = $request->worker_id;
        $workerAccount1->started_date = $request->started_date;
        $workerAccount1->status = "working";
        $workerAccount1->salary_rate = $worker->salary_rate;

        $workerAccount1->save();

        return $this->show($workerAccount1->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $workerAccount = WorkerAccount::find($id);
        if(!$workerAccount){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found'
            ]);
        }

        return new ShowWorkerAccountResource($workerAccount);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkerAccountRequest $request, string $id)
    {
        $workerAccount = WorkerAccount::find($id);
        if(!$workerAccount){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found'
            ]);
        }
        $workerAccount->update([
            'finished_date' => $request->finished_date ,
            'status' => $request->status
        ]);

        return new ShowWorkerAccountResource($workerAccount);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $workerAccount = WorkerAccount::find($id);
        if(!$workerAccount){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record nout found!'
            ]);
        }
        if($workerAccount->status=="finished" || $workerAccount->status=="payed"){
            return new ReturnResponseResource([
                'code' => 401 ,
                'message' => "You can not delete this item!"
            ] ,401);
        }
        $workerAccount->delete();

        return new ReturnResponseResource([
            'code' => 200 ,
            'message' => 'Item has been deleted successfully!'
        ] , 200);
    }
}
