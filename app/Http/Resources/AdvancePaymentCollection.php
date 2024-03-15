<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AdvancePaymentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => "Advance Payments List" ,
            'total_amount' => $this->collection->sum('amount') ,
            'data' => $this->collection->map(function ($payment){
                return new ShowAdvancePaymentResource($payment);
            }) ,
        ];
    }
}
