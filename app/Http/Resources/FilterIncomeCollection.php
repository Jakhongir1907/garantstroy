<?php

namespace App\Http\Resources;

use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FilterIncomeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => "All Incomes List" ,
            'total_amount' => $this->collection->sum('amount') ,
            'data' => $this->collection->map(function ($income){
                return new ShowIncomeResource($income);
            }) ,
        ];
    }
}
