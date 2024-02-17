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
            'name' => $this->name ,
            'phone_number' => $this->phone_number ,
            'comment' => $this->comment ,
            'project_name' => ($this->project) ? $this->project->name : "",
        ];
    }
}
