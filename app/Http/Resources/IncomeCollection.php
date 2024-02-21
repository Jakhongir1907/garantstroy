<?php

namespace App\Http\Resources;

use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class IncomeCollection extends ResourceCollection
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
            'total_amount' => Income::sum('summa') ,
            'data' => $this->collection->map(function ($income){
                return new ShowIncomeResource($income);
            }) ,
        ];
    }
}
