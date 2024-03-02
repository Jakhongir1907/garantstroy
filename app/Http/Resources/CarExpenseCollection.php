<?php

namespace App\Http\Resources;

use App\Models\CarExpense;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CarExpenseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => "All Car Expenses List" ,
            'total_amount' => CarExpense::sum('amount') ,
            'data' => $this->collection->map(function ($carExpense){
                return new ShowCarExpenseResource($carExpense);
            }) ,
        ];
    }
}
