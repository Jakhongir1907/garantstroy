<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowContractFloorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'contract_id' => $this->contract_id ,
            'contract_name' => ($this->contract) ? $this->contract->block : '',
            'price' => $this->price ,
            'square' => $this->square ,
            'floor' => $this->floor ,
        ];
    }
}
