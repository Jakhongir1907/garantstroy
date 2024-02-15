<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarExpenseRequest;
use App\Http\Requests\UpdateCarExpenseRequest;
use App\Http\Resources\CarExpenseCollection;
use App\Http\Resources\ReturnResponseResource;
use App\Http\Resources\ShowCarExpenseResource;
use App\Models\CarExpense;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CarExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carExpenses = CarExpense::orderByDesc('date')->paginate(12);
        return new CarExpenseCollection($carExpenses);
    }
    public function lastDays(string $days){

        $startDate = Carbon::now()->subDays($days)->toDateString();
        $carExpenses = CarExpense::where('date', '>=', $startDate)
            ->orderByDesc('date')->get();
        $totalAmount =  CarExpense::where('date', '>=', $startDate)->sum('summa');

        return response()->json([
            'message' => "Car Expenses , Last 30 days " ,
            'totalAmount' => $totalAmount ,
            'data' => $carExpenses ,
        ]);
    }

    public function filterData(Request $request){
        $request->validate([
            'start_date' => ['required' , 'date'] ,
            'end_date' => ['required' , 'date']
        ]);
        $startDateTime = Carbon::parse($request->start_date)->startOfDay();
        $endDateTime = Carbon::parse($request->end_date)->endOfDay();

        // Get the total amount for the specified date range
        $totalAmount = CarExpense::whereBetween('date', [$startDateTime, $endDateTime])
            ->sum('summa');
        $carExpenses = CarExpense::whereBetween('date', [$startDateTime, $endDateTime])->paginate(12);

        return response()->json([
            'message' => "Filtered Car Expenses" ,
            'totalAmount' => $totalAmount ,
            'data' => $carExpenses ,
        ]);

    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarExpenseRequest $request)
    {
        return new ShowCarExpenseResource(CarExpense::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $carExpense = CarExpense::find($id);
        if(!$carExpense){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ]  , 404);
        }
        return new ShowCarExpenseResource($carExpense);
    }

    /**
     * Show the form for editing the specified resource.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCarExpenseRequest $request, string $id)
    {
        $carExpense = CarExpense::find($id);
        if(!$carExpense){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ]  , 404);
        }
        $carExpense->update([
            'summa' => $request->summa ,
            'date' => $request->date ,
            'comment' => $request->comment ,
        ]);

        return new ShowCarExpenseResource($carExpense);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $carExpense = CarExpense::find($id);
        if(!$carExpense){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ]  , 404);
        }
        $carExpense->delete();
        return new ReturnResponseResource([
            'code' => 201 ,
            'message' => 'Record has been deleted successfully!'
        ]);
    }
}
