<?php

namespace App\Http\Resources;

use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FilterToolCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => "Filtered Tools List" ,
            'total_amount' => $this->collection->sum('price'),
            'data' => $this->collection->map(function ($tool){
                return new ShowToolResource($tool);
            }) ,
        ];
    }
}
