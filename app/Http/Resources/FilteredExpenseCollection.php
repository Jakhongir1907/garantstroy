<?php

namespace App\Http\Resources;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FilteredExpenseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => "Filtered Expenses List" ,
            'total_amount' => $this->collection->sum('amount'),
            'data' => $this->collection->map(function ($expense){
                return new ShowExpenseResource($expense);
            }) ,
        ];
    }
}
