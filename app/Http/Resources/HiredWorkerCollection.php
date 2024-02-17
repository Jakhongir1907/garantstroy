<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HiredWorkerCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => "All Hired Workers List" ,
            'data' => $this->collection->map(function ($hiredWorker){
                return new ShowHiredWorkerResource($hiredWorker);
            }) ,
        ];
    }
}
