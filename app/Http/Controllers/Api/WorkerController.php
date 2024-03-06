<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkerRequest;
use App\Http\Requests\UpdateWorkerRequest;
use App\Http\Resources\ReturnResponseResource;
use App\Http\Resources\ShowWorkerResource;
use App\Http\Resources\WorkerCollection;
use App\Models\Worker;
use App\Models\WorkerAccount;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

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

        return new ShowWorkerResource($worker);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $worker = Worker::find($id);
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