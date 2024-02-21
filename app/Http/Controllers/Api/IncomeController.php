<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIncomeRequest;
use App\Http\Resources\IncomeCollection;
use App\Http\Resources\ReturnResponseResource;
use App\Http\Resources\ShowIncomeResource;
use App\Models\HouseholdExpense;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function filterData(Request $request){
        $projectId = $request->project_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if(empty($projectId) && empty($startDate) && empty($endDate)){
            $incomes = Income::orderByDesc('date')->paginate(10);
        }else{
            $incomes = Income::when($projectId, function ($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })
                ->when($startDate, function ($query) use ($startDate) {
                    $query->where('date_column', '>=', $startDate);
                })
                ->when($endDate, function ($query) use ($endDate) {
                    $query->where('date_column', '<=', $endDate);
                })
                ->get();
        }

        return new IncomeCollection($incomes);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIncomeRequest $request)
    {
        return new ShowIncomeResource(Income::create([
            'project_id' => $request->project_id ,
            'summa' => $request->summa * $request->currency_rate,
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
            'summa' => $request->summa * $request->currency_rate,
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
