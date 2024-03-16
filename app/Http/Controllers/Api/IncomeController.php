<?php

namespace App\Http\Controllers\Api;

use App\Exports\IncomesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIncomeRequest;
use App\Http\Resources\FilterIncomeCollection;
use App\Http\Resources\IncomeCollection;
use App\Http\Resources\ReturnResponseResource;
use App\Http\Resources\ShowIncomeResource;
use App\Models\HouseholdExpense;
use App\Models\Income;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function exportExcel($project_id){
        $project = Project::find($project_id);

        if(!$project){
           $incomesData = Income::all();
        }else{
            $incomesData = Income::where('project_id' , $project->id)->get();
        }
        $totalAmount = 0;
        $data = [];
        if (!empty($incomesData)){
            foreach ($incomesData as $income){
                $totalAmount += $income->amount;
             $data[] = [
                 'project' => $income->project->name ?? "" ,
                 'date' => $income->date ,
                 'comment' => $income->comment ,
                 'income_type' => ($income->income_type=='transfer')?"O'kazma":"Naqd" ,
                 'currency' => $income->currency ,
                 'currency_rate' => $income->currency_rate ,
                 'summa' => $income->summa ,
                 'amount' => $income->amount ,
             ];
            }
            $data [] = [
                'project' =>  " " ,
                'date' => " " ,
                'comment' => " ",
                'income_type' => " " ,
                'currency' => " " ,
                'currency_rate' => " " ,
                'summa' => "JAMI SUMMA:" ,
                'amount' => $totalAmount ,
            ];

        }
        return Excel::download(new IncomesExport($data)  , "Daromad.xlsx");
    }

    public function filterData(Request $request){
        $projectId = $request->project_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        if(empty($projectId) && empty($startDate) && empty($endDate)){
            $incomes = Income::orderByDesc('date')->paginate(10);
            return new IncomeCollection($incomes);
        }else{
            $incomes = Income::when($projectId, function ($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })
                ->when($startDate, function ($query) use ($startDate) {
                    $query->where('date', '>=', $startDate);
                })
                ->when($endDate, function ($query) use ($endDate) {
                    $query->where('date', '<=', $endDate);
                })
                ->get();
        }

        return new FilterIncomeCollection($incomes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIncomeRequest $request)
    {
        return new ShowIncomeResource(Income::create([
            'project_id' => $request->project_id ,
            'summa' => $request->summa,
            'amount' => $request->summa * $request->currency_rate,
            'comment' => $request->comment ,
            'date' => $request->date ,
            'income_type' => $request->income_type ,
            'currency' => $request->currency ,
            'currency_rate' => $request->currency_rate ,
        ]));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $income = Income::find($id);
        if(!$income){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ]);
        }

        return new ShowIncomeResource($income);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $income = Income::find($id);
        if(!$income){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ]);
        }

        $income->update([
            'project_id' => $request->project_id ,
            'summa' => $request->summa ,
            'amount' => $request->summa * $request->currency_rate,
            'comment' => $request->comment ,
            'date' => $request->date ,
            'income_type' => $request->income_type ,
            'currency' => $request->currency ,
            'currency_rate' => $request->currency_rate ,
        ]);

        return new ShowIncomeResource($income);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $income = Income::find($id);
        if(!$income){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ]);
        }
        $income->delete();
        return new ReturnResponseResource([
            'code' => 201 ,
            'message' => 'Record has been deleted successfully!'
        ]);
    }
}
