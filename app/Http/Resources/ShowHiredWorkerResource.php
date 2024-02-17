<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowHiredWorkerResource extends JsonResource
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
            'name' => $this->name ,
            'phone_number' => $this->phone_number ,
            'comment' => $this->comment ,
            'project_name' => ($this->project) ? $this->project->name : "",
            'total_amount' => ($this->expenses) ? $this->expenses->sum('summa') : 0 ,
        ];
    }
}
