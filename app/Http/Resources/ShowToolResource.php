<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowToolResource extends JsonResource
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
            'name' => $this->name ,
            'price' => $this->price ,
            'image_name' => $this->image_name ,
            'image_url' => $this->image_url ,
            'state' => $this->state ,
            'project_name' => ($this->project) ? $this->project->name : "" ,
        ];
    }
}
