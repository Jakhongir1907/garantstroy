<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowWorkerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name ,
            'phone_number' => $this->phone_number ,
            'salary_rate' => $this->salary_rate ,
            'position' => $this->position ,
            'is_active' => $this->is_active ,
            'project_id' => ($this->project) ? $this->project_id : "" ,
            'project_name' => ($this->project) ? $this->project->name : "" ,
        ];
    }
}
