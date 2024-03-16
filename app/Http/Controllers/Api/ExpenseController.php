<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Http\Resources\ExpenseCollection;
use App\Http\Resources\FilteredExpenseCollection;
use App\Http\Resources\ReturnResponseResource;
use App\Http\Resources\ShowExpenseResource;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $projectId = $request->project_id;
        $category = $request->category;
        $user_id = $request->user_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $user = auth()->user();
        if(empty($projectId) && empty($startDate) && empty($endDate) && empty($category) && empty($user_id)){
            if($user->is_admin){
            $expenses = Expense::orderByDesc('date')->paginate(10);
                return new ExpenseCollection($expenses);
            }else{
                $expenses = Expense::where('user_id' , $user->id)->orderByDesc('date')->paginate(10);
                return new FilteredExpenseCollection($expenses);
            }

        }else{
            if($user->is_admin){
                $expenses = Expense::when($projectId, function ($query) use ($projectId) {
                    $query->where('project_id', $projectId);
                })->when($category, function ($query) use ($category) {
                    $query->where('category', $category);
                })->when($user_id, function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                })->when($startDate, function ($query) use ($startDate) {
                    $query->where('date', '>=', $startDate);
                })
                    ->when($endDate, function ($query) use ($endDate){
                        $query->where('date', '<=', $endDate);
                    })->orderByDesc('date')->get();
            }else{
                $expenses = Expense::where('user_id' , $user->id)->when($projectId, function ($query) use ($projectId) {
                    $query->where('project_id', $projectId);
                })->when($category, function ($query) use ($category) {
                    $query->where('category', $category);
                })->when($user_id, function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                })->when($startDate, function ($query) use ($startDate) {
                    $query->where('date', '>=', $startDate);
                })->when($endDate, function ($query) use ($endDate){
                        $query->where('date', '<=', $endDate);
                    })->orderByDesc('date')->get();
            }

            return new FilteredExpenseCollection($expenses);
        }
    }

    public function exportExcel(Request $request){
        $projectId = $request->project_id;
        $category = $request->category;
        $user_id = $request->user_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        if(empty($projectId) && empty($startDate) && empty($endDate) && empty($category) && empty($user_id)){
                $expenses = Expense::all();
        }else{
            $expenses = Expense::when($projectId, function ($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })->when($category, function ($query) use ($category) {
                $query->where('category', $category);
            })->when($user_id, function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->when($startDate, function ($query) use ($startDate) {
                $query->where('date', '>=', $startDate);
            })->when($endDate, function ($query) use ($endDate){
                    $query->where('date', '<=', $endDate);
                })->orderByDesc('date')->get();
        }
        if(!empty($expenses)){
            $totalAmount = 0;
          $data = [];
          foreach ($expenses as $expense){
              $totalAmount += $expense->amount;
              $data[] = [
                  'project' => $expense->project->name ?? " " ,
                  'brigadier' => $expense->user->name ?? " " ,
                  'comment' => $expense->comment ?? " " ,
                  'date' => $expense->date ,
                  'currency' => ($expense->currency=='dollar')? "$":"SO'M" ,
                  'currency_rate' => $expense->currency_rate,
                  'summa' => $expense->summa ,
                  'amount' => $expense->amount ,
              ];
          }
            $data[] = [
                'project' =>  " " ,
                'brigadier' => " " ,
                'comment' => " " ,
                'date' => " " ,
                'currency' => " " ,
                'currency_rate' => " ",
                'summa' => "UMUMIY SUMMA:" ,
                'amount' => $totalAmount ,
            ];

        }

    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request)
    {
        $expense = Expense::create([
            'category' => $request->category ,
            'comment' => $request->comment ,
            'project_id' => $request->project_id ,
            'user_id' => $request->user_id ,
            'expense_type' => $request->expense_type ,
            'currency' => $request->currency ,
            'currency_rate' => $request->currency_rate ,
            'amount' => $request->summa*$request->currency_rate ,
            'summa' => $request->summa ,
            'date' => $request->date ,
        ]);

        return $this->show($expense->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $expense = Expense::find($id);
        if(!$expense){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }
        return new ShowExpenseResource($expense);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, string $id)
    {
        $expense = Expense::find($id);
        if(!$expense){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }
        $expense->update([
            'category' => $request->category ,
            'comment' => $request->comment ,
            'project_id' => $request->project_id ,
            'user_id' => $request->user_id ,
            'expense_type' => $request->expense_type ,
            'currency' => $request->currency ,
            'currency_rate' => $request->currency_rate ,
            'amount' => $request->summa*$request->currency_rate ,
            'summa' => $request->summa ,
            'date' => $request->date ,
        ]);
        return new ShowExpenseResource($expense);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expense = Expense::find($id);
        if(!$expense){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => 'Record not found!'
            ] , 404);
        }
        $expense->delete();
        return new ReturnResponseResource([
            'code' => 201 ,
            'message' => 'Record has been deleted successfully!'
        ]);
    }
}
