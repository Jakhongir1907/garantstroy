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
use Illuminate\Support\Facades\Auth;

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
                    $query->where('position', $position);
                })
                ->when($search, function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })->orderByDesc('created_at')->get();
        }

        return new WorkerCollection($workers);
    }

    public function salary(Request $request){
        // Validate the incoming request data
        $request->validate([
            'workers' => 'required|array',
            'workers.*' => 'exists:workers,id',
            'started_date' => 'required|date',
            'finished_date' => 'required|date|after_or_equal:started_date',
        ]);
        $workers = $request->input('workers');
        $startedDate = $request->input('started_date');
        $finishedDate = $request->input('finished_date');
        $workerAccounts = WorkerAccount::whereIn('worker_id', $workers)
            ->whereBetween('started_date', [$startedDate, $finishedDate])
            ->orWhereBetween('finished_date', [$startedDate, $finishedDate])
            ->get();
        $salaries = [];
        foreach ($workerAccounts as $workerAccount) {
            $info = $this->calculateWorkerSalary($workerAccount, $startedDate, $finishedDate);
            $arr = [
                'worker_id' => $workerAccount->id,
                'day_offs' => $info['day_offs'],
                'payed' => $info['payed'],
                'days' => $info['days'],
                'salary_rate' => $workerAccount->salary_rate,
                'total' => $info['total']
            ];
        }
        $salaries[]=$arr;
        return response()->json($salaries);
    }

    private function calculateWorkerSalary($workerAccount, $startedDate, $finishedDate)
    {
        $dayOffs = DayOff::where('worker_account_id',$workerAccount->id)->sum('quantity');
        $payed = AdvancePayment::where('worker_account_id',$workerAccount->id)->sum('amount');
        $startDate = max($workerAccount->started_date, $startedDate);
        $endDate = min($workerAccount->finished_date, $finishedDate);
        $daysWorked = max(0, min(1, ($endDate - $startDate) / (60 * 60 * 24)));
        $total = ($daysWorked - $dayOffs) * $workerAccount->salary_rate - $payed;
        return [
            'day_offs' => $dayOffs,
            'payed' => $payed,
            'days' => $daysWorked,
            'total' => $total,
        ];
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
        $worker->update([
            'name' => $request->name ,
            'phone_number' => $request->phone_number ,
            'position' => $request->position ,
            'salary_rate' => $request->salary_rate ,
            'project_id' => $request->project_id ,
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
//        if($worker->dayOffs()->count() > 0 || $worker->advancePayments()->count() > 0 || $worker->workerAccounts()->count() > 0){
//            return new ReturnResponseResource([
//                'code' => 404 ,
//                'message' => 'You can not delete this worker!'
//            ]);
//        }
        $worker->delete();
        return new ReturnResponseResource([
            'code' => 201 ,
            'message' => 'Record has been deleted successfully!'
        ]);
    }
}
