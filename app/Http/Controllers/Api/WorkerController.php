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

   public function calculateSalary(Request $request){
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $workers = Worker::all();
        $monthlySalaries = [];
        foreach($workers as $worker){
            $workerAccount = WorkerAccount::where('worker_id' , $worker->id)->where('status','!=','payed')->latest()->first();
            if($workerAccount){
                if($workerAccount->started_date > $startDate){
                    $startDate = $workerAccount->start_date;
                }
                if(!is_null($workerAccount->finished_date)){
                    if($endDate > $workerAccount->finished_date){
                    $endDate = $workerAccount->finished_date;
                    }
                }
                $dayOffs = $workerAccount->dayOffs()->whereBetween('date' , [$startDate , $endDate])->count();
                $totalDaysInMonth = $startDate->diffInDays($endDate) + 1;
                $effectiveWorkDays = $totalDaysInMonth-$dayOffs;
                $advancePayments = $workerAccount->advancePayments()->whereBetween('date' , [$startDate , $endDate])->where('type' , 'advance')->sum('amount');
                $monthlySalaries [] = [
                    'name' => $worker->name ,
                    'work_days' => $effectiveWorkDays ,
                    'day_offs' => $dayOffs ,
                    'salary_rate' => $workerAccount->salary_rate ,
                    'advance_payments' => $advancePayments ,
                    'amount' => ($effectiveWorkDays*$workerAccount->salary_rate) - $advancePayments ,
                    'from' => $startDate ,
                    'to' => $endDate ,
                ];
            }
        }
        return response()->json([
            'salaries' => $monthlySalaries
        ]);
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
