<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkerRequest;
use App\Http\Requests\UpdateWorkerRequest;
use App\Http\Resources\ReturnResponseResource;
use App\Http\Resources\ShowWorkerAccountResource;
use App\Http\Resources\ShowWorkerResource;
use App\Http\Resources\WorkerAccountCollection;
use App\Http\Resources\WorkerCollection;
use App\Models\AdvancePayment;
use App\Models\DayOff;
use App\Models\Worker;
use App\Models\WorkerAccount;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $projectId = $request->project_id;
        $position = $request->position;
        $search = $request->search;
        if(empty($projectId) && empty($position) && empty($search)){
            $workers = Worker::orderByDesc('created_at')->paginate(10);
        }else{
            $workers = Worker::when($projectId, function ($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })->when($position, function ($query) use ($position) {
                    $query->where('position', '>=', $position);
                })
                ->when($search, function ($query) use ($search) {
                    $query->where('date', 'LIKE', '%' . $search . '%');
                })
                ->get();
        }

        return new WorkerCollection($workers);
    }

    public function salary(Request $request){
        $workers = $request->workers;
        $started_date = $request->started_date;
        $finished_date = $request->finished_date;
        $workerAccounts = WorkerAccount::when($workers, function ($query) use ($workers){
            $query->whereIn('worker_id',$workers);
        })->when($started_date, function ($query) use ($started_date){
            $query->where('started_date','<=',$started_date);
        })/*->when($finished_date, function ($query) use ($finished_date){
            $query->where('finished_date', '>=',$finished_date);
        })*/
        ->get();
        return new WorkerAccountCollection($workerAccounts);
    }

    public function start_work(Request $request){
        $this->validate($request, [
            'worker_id' => 'required|exists:workers,id',
            'started_date' => 'required|date_format:Y-m-d',
            'status' => 'required|in:working,finished,payed',
        ]);
        $worker = Worker::find($request->worker_id);
        WorkerAccount::create([
            'worker_id' => $request->worker_id,
            'started_date' => $request->started_date,
            'status' => $request->status,
            'salary_rate' => $worker->salary_rate,
        ]);
        return new ReturnResponseResource([
            'code' => 200,
            'message' => "Worker start work successfuly!"
        ] , 200);
    }

    public function finish_work(Request $request){
        $this->validate($request, [
            'worker_account_id' => 'required|exists:worker_accounts,id',
            'finished_date' => 'required|date_format:Y-m-d',
            'status' => 'required|in:working,finished,payed',
        ]);
        WorkerAccount::where('id',$request->worker_account_id)->update([
            'finished_date' => $request->finished_date,
            'status' => $request->status,
        ]);
        return new ReturnResponseResource([
            'code' => 200,
            'message' => "Worker Account status changed successfuly!"
        ] , 200);
    }
    public function dayoff(Request $request){
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

    public function payment(Request $request){
        $this->validate($request, [
            'amount' => 'required',
            'date' => 'required|date_format:Y-m-d',
            'type' => 'required|in:advance,salary',
            'worker_id' => 'required|exists:workers,id',
        ]);

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

    public function filterData(Request $request){
        $projectId = $request->project_id;
        $position = $request->position;
        $search = $request->search;
        if(empty($projectId) && empty($position) && empty($search)){
            $workers = Worker::orderByDesc('created_at')->paginate(10);
        }else{
            $workers = Worker::when($projectId, function ($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })
                ->when($position, function ($query) use ($position) {
                    $query->where('position', '>=', $position);
                })
                ->when($search, function ($query) use ($search) {
                    $query->where('date', 'LIKE', '%' . $search . '%');
                })
                ->get();
        }

        return new WorkerCollection($workers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkerRequest $request)
    {
        $worker = Worker::create([
            'name' => $request->name ,
            'phone_number' => $request->phone_number ,
            'position' => $request->position ,
            'salary_rate' => $request->salary_rate ,
            'project_id' => $request->project_id ,
            'is_active' => 1 ,
        ]);

        return $this->show($worker->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $worker = Worker::where('id',$id)
            ->with('workerAccounts')->first();
        if(!$worker){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => "Record not found!"
            ] , 404);
        }

        return new ShowWorkerResource($worker);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkerRequest $request, string $id)
    {
        $worker = Worker::find($id);
        if(!$worker){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => "Record not found!"
            ] , 404);
        }
        $keldi_ketdi = $request->is_active;
        if($keldi_ketdi){
            $workerAccount = WorkerAccount::where('worker_id' , $worker->id)->whereNull('finished_date')->latest()->first();
            if($workerAccount){
                $workerAccount->update([
                    'started_date' => $request->start_date ,
                ]);
            }else{
                $workerAccount = WorkerAccount::create([
                    'worker_id' => $worker->id ,
                    'started_date' => $request->start_date
                ]);
            }
        }else{
            $workerAccount = WorkerAccount::where('worker_id' , $worker->id)->whereNull('finished_date')->latest()->first();
            if($workerAccount){
                $workerAccount->update([
                    'finished_date' => $request->finished_date
                ]);
            }else{
                return new ReturnResponseResource([
                    'code' => 404 ,
                    'message' => "Bu ishchi xali ish boshlamagan oldin ish boshlash sanasini kiriting!"
                ] , 404);
            }
        }
        $worker->update([
            'name' => $request->name ,
            'phone_number' => $request->phone_number ,
            'position' => $request->position ,
            'salary_rate' => $request->salary_rate ,
            'project_id' => $request->project_id ,
            'is_active' => $keldi_ketdi ,
        ]);

        return new ShowWorkerResource($worker);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $worker = Worker::find($id);
        if(!$worker){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ]);
        }
        if($worker->dayOffs()->count() > 0 || $worker-advancePayments()->count() > 0 || $worker->workerAccounts()->count() > 0){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'You can not delete this worker!'
            ]);
        }
        $worker->delete();
        return new ReturnResponseResource([
            'code' => 201 ,
            'message' => 'Record has been deleted successfully!'
        ]);
    }
}
