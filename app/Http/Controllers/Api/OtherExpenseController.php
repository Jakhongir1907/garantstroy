<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOtherExpenseRequest;
use App\Http\Requests\UpdateOtherExpenseRequest;
use App\Http\Resources\OtherExpenseCollection;
use App\Http\Resources\ReturnResponseResource;
use App\Http\Resources\ShowOtherExpenseResource;
use App\Models\OtherExpense;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OtherExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

    }

    public function lastDays(string $days){
        $startDate = Carbon::now()->subDays($days)->toDateString();
        $otherExpenses = OtherExpense::where('date', '>=', $startDate)
            ->orderByDesc('date')->get();
        $totalAmount =  OtherExpense::where('date', '>=', $startDate)->sum('summa');

        return response()->json([
            'message' => "Other Expenses , Last 30 days " ,
            'totalAmount' => $totalAmount ,
            'data' => $otherExpenses ,
        ]);
    }

    public function filterData(Request $request){

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if(!empty($endDate) && !empty($startDate)){
            $startDateTime = Carbon::parse($request->start_date)->startOfDay();
            $endDateTime = Carbon::parse($request->end_date)->endOfDay();

            // Get the total amount for the specified date range
            $totalAmount = OtherExpense::whereBetween('date', [$startDateTime, $endDateTime])
                ->sum('summa');
            $otherExpenses = OtherExpense::whereBetween('date', [$startDateTime, $endDateTime])->paginate(12);

            return response()->json([
                'message' => "Filtered Other Expenses" ,
                'total_mount' => $totalAmount ,
                'data' => $otherExpenses ,
            ]);
        }elseif(!empty($startDate) && empty($endDate)){
            $startDateTime = Carbon::parse($request->start_date)->startOfDay();
            $endDateTime = Carbon::now()->endOfDay();

            // Get the total amount for the specified date range
            $totalAmount = OtherExpense::whereBetween('date', [$startDateTime, $endDateTime])
                ->sum('summa');
            $otherExpenses = OtherExpense::whereBetween('date', [$startDateTime, $endDateTime])->paginate(12);

            return response()->json([
                'message' => "Filtered Other Expenses" ,
                'totalAmount' => $totalAmount ,
                'data' => $otherExpenses ,
            ]);
        }elseif(empty($startDate) && !empty($endDate)){
            $startDateTime = Carbon::parse('2000-01-01')->startOfDay();
            $endDateTime = Carbon::parse($request->end_date)->endOfDay();

            // Get the total amount for the specified date range
            $totalAmount = OtherExpense::whereBetween('date', [$startDateTime, $endDateTime])
                ->sum('summa');
            $otherExpenses = OtherExpense::whereBetween('date', [$startDateTime, $endDateTime])->paginate(12);

            return response()->json([
                'message' => "Filtered Other Expenses" ,
                'totalAmount' => $totalAmount ,
                'data' => $otherExpenses ,
            ]);
        }else{
            $otherExpenses = OtherExpense::orderByDesc('date')->paginate(12);
            $totalAmount = OtherExpense::sum('summa');
            return response()->json([
                'message' => "Filtered Other Expenses" ,
                'totalAmount' => $totalAmount ,
                'data' => $otherExpenses ,
            ]);
        }

    }

    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOtherExpenseRequest $request)
    {
        return new ShowOtherExpenseResource(OtherExpense::create([
            'summa' => $request->summa ,
            'date' => $request->date ,
            'comment' => $request->comment ,
            'currency' => $request->currency ,
            'currency_rate' => $request->currency_rate ,
            'amount' => $request->summa * $request->currency_rate,
        ]));

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $otherExpense = OtherExpense::find($id);
        if(!$otherExpense){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ]  , 404);
        }
        return new ShowOtherExpenseResource($otherExpense);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOtherExpenseRequest $request, string $id)
    {
        $otherExpense = OtherExpense::find($id);
        if(!$otherExpense){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ]  , 404);
        }
        $otherExpense->update([
            'summa' => $request->summa ,
            'date' => $request->date ,
            'comment' => $request->comment,
            'currency' => $request->currency ,
            'currency_rate' => $request->currency_rate ,
            'amount' => $request->summa * $request->currency_rate,
        ]);

        return new ShowOtherExpenseResource($otherExpense);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $otherExpense = OtherExpense::find($id);
        if(!$otherExpense){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ]  , 404);
        }
        $otherExpense->delete();

        return new ReturnResponseResource([
            'code' => 201 ,
            'message' => 'Record has been deleted successfully!'
        ]);
    }
}
