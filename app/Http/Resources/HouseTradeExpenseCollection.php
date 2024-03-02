<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HouseTradeExpenseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => "All House Trade Expenses List" ,
            'total_amount' => $this->collection->sum('amount') ,
            'data' => $this->collection->map(function ($houseTradeExpense){
                return new ShowTradeHouseExpenseResource($houseTradeExpense);
            }) ,
        ];
    }
}
