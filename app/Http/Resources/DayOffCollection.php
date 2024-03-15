<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DayOffCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => "Day Offs List" ,
            'total_quantity' => $this->collection->sum('quantity'),
            'data' => $this->collection->map(function ($dayOff){
                return new ShowDayOffResource($dayOff);
            }) ,
        ];
    }
}
