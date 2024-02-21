<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowIncomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ,
            'project_id' => $this->project_id ,
            'project_name' => ($this->project) ? $this->project->name : '' ,
            'summa' => $this->summa ,
            'date' => $this->date ,
            'income_type' => $this->income_type ,
            'currency' => $this->currency ,
            'currency_rate' => $this->currency_rate ,
            'comment' => $this->comment ,
        ];

    }
}
