<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHouseholdExpense;
use App\Http\Requests\UpdateHouseholdExpense;
use App\Http\Resources\ReturnResponseResource;
use App\Http\Resources\ShowCarExpenseResource;
use App\Http\Resources\ShowHouseholdExpenseResource;
use App\Models\HouseholdExpense;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HouseholdExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function lastDays(string $days){

        $startDate = Carbon::now()->subDays($days)->toDateString();
        $householdExpenses = HouseholdExpense::where('date', '>=', $startDate)
            ->orderByDesc('date')->get();
        $totalAmount =  HouseholdExpense::where('date', '>=', $startDate)->sum('amount');

        return response()->json([
            'message' => "Household Expenses , Last 30 days " ,
            'totalAmount' => $totalAmount ,
            'data' => $householdExpenses ,
        ]);
    }
    public function filterData(Request $request){

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if(!empty($endDate) && !empty($startDate)){
            $startDateTime = Carbon::parse($request->start_date)->startOfDay();
            $endDateTime = Carbon::parse($request->end_date)->endOfDay();

            // Get the total amount for the specified date range
            $totalAmount = HouseholdExpense::whereBetween('date', [$startDateTime, $endDateTime])
                ->sum('amount');
            $householdExpenses = HouseholdExpense::whereBetween('date', [$startDateTime, $endDateTime])->paginate(12);

            return response()->json([
                'message' => "Filtered Household Expenses" ,
                'totalAmount' => $totalAmount ,
                'data' => $householdExpenses ,
            ]);
        }elseif(!empty($startDate) && empty($endDate)){
            $startDateTime = Carbon::parse($request->start_date)->startOfDay();
            $endDateTime = Carbon::now()->endOfDay();

            // Get the total amount for the specified date range
            $totalAmount = HouseholdExpense::whereBetween('date', [$startDateTime, $endDateTime])
                ->sum('amount');
            $householdExpenses = HouseholdExpense::whereBetween('date', [$startDateTime, $endDateTime])->paginate(12);

            return response()->json([
                'message' => "Filtered Household Expenses" ,
                'totalAmount' => $totalAmount ,
                'data' => $householdExpenses ,
            ]);
        }elseif(empty($startDate) && !empty($endDate)){
            $startDateTime = Carbon::parse('2000-01-01')->startOfDay();
            $endDateTime = Carbon::parse($request->end_date)->endOfDay();

            // Get the total amount for the specified date range
            $totalAmount = HouseholdExpense::whereBetween('date', [$startDateTime, $endDateTime])
                ->sum('amount');
            $householdExpenses = HouseholdExpense::whereBetween('date', [$startDateTime, $endDateTime])->paginate(12);

            return response()->json([
                'message' => "Filtered Household Expenses" ,
                'totalAmount' => $totalAmount ,
                'data' => $householdExpenses ,
            ]);
        }else{
            $householdExpenses = HouseholdExpense::orderByDesc('date')->paginate(12);
            $totalAmount = HouseholdExpense::sum('amount');
            return response()->json([
                'message' => "All Household Expenses" ,
                'totalAmount' => $totalAmount ,
                'data' => $householdExpenses ,
            ]);
        }

    }
    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHouseholdExpense $request)
    {
        return new ShowHouseholdExpenseResource(HouseholdExpense::create([
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
        $householdExpense = HouseholdExpense::find($id);
        if(!$householdExpense){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ]  , 404);
        }
        return new ShowHouseholdExpenseResource($householdExpense);
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHouseholdExpense $request, string $id)
    {
        $householdExpense = HouseholdExpense::find($id);
        if(!$householdExpense){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ]  , 404);
        }

        $householdExpense->update([
            'summa' => $request->summa ,
            'date' => $request->date ,
            'comment' => $request->comment ,
            'currency' => $request->currency ,
            'currency_rate' => $request->currency_rate ,
            'amount' => $request->summa * $request->currency_rate,
        ]);
        return new ShowHouseholdExpenseResource($householdExpense);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $householdExpense = HouseholdExpense::find($id);
        if(!$householdExpense){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ]  , 404);
        }
        $householdExpense->delete();
        return new ReturnResponseResource([
            'code' => 201 ,
            'message' => 'Record has been deleted successfully!'
        ]);
    }
}
