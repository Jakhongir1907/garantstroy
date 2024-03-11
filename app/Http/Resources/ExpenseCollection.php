<?php

namespace App\Http\Resources;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExpenseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => "All Expenses List" ,
            'total_amount' => Expense::sum('amount'),
            'data' => $this->collection->map(function ($expense){
                return new ShowExpenseResource($expense);
            }) ,
        ];
    }
}
