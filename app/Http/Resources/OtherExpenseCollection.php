<?php

namespace App\Http\Resources;

use App\Models\OtherExpense;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OtherExpenseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => "All Other Expenses List" ,
            'total_amount' => OtherExpense::sum('summa') ,
            'data' => $this->collection->map(function ($otherExpense){
                 return new ShowOtherExpenseResource($otherExpense);
            }) ,
        ];
    }
}
