<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WorkerAccountCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => "All Worker Accounts List" ,
            'data' => $this->collection->map(function ($workerAccount){
                return new ShowWorkerAccountResource($workerAccount);
            }) ,
        ];
    }
}
