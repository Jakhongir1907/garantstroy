<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ContractFloorCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => "Filtered Floors List" ,
            'data' => $this->collection->map(function ($floor){
                return new ShowContractFloorResource($floor);
            }) ,
        ];
    }
}
