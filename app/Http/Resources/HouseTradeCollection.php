<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HouseTradeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => "All House Trades List" ,
            'data' => $this->collection->map(function ($houseTrade){
                return new ShowHouseTradeResource($houseTrade);
            }) ,
        ];
    }
}
