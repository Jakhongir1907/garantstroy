<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowContractResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project_id ,
            'project_name' => ($this->project) ? $this->project->name :"" ,
            'block' => $this->block ,
            'currency' => $this->currency ,
            'total_amount' => ($this->floors) ? $this->floors->sum('price'*'square') : 0 ,
        ];
    }
}
