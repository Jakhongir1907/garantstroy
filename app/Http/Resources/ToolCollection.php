<?php

namespace App\Http\Resources;

use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ToolCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => "All Tools List" ,
            'total_amount' => Tool::sum('price') ,
            'data' => $this->collection->map(function ($tool){
                return new ShowToolResource($tool);
            }) ,
        ];
    }
}
