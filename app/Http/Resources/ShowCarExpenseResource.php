<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowCarExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'  => $this->id ,
            'summa' => $this->summa ,
            'date' => $this->date ,
            'comment' => $this->comment ,
            'currency' => $this->currency ,
            'currency_rate' => $this->currency_rate ,
            'amount' => $this->amount ,
        ];
    }
}
