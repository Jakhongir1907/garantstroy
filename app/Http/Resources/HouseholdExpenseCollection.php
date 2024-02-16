<?php

namespace App\Http\Resources;

use App\Models\HouseholdExpense;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HouseholdExpenseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => "All Household Expenses List" ,
            'total_amount' => HouseholdExpense::sum('summa') ,
            'data' => $this->collection->map(function ($householdExpense){
                return new ShowHouseholdExpenseResource($householdExpense);
            }) ,
        ];
    }
}
