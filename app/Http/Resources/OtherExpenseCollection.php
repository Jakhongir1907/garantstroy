<?php

namespace App\Http\Resources;

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
            'message' => "" ,
            'data' => $this->collection->map(function ($otherExpense){
                 return new ShowOtherExpenseResource($otherExpense);
            }) ,
//            'total_amount' => $this->collection->sum('summa') ,
        ];
    }
}
