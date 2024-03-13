<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExpenseItemcollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => "Expense Items" ,
            'total_amount' => $this->collection->sum('summa'),
            'data' => $this->collection->map(function ($expenseItem){
                return new ShowExpenseItemResource($expenseItem);
            }) ,
        ];
    }
}
