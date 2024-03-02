<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowHiredWorkerExpenseResource extends JsonResource
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
            'summa' => $this->summa ,
            'comment' => $this->comment ,
            'date' => $this->date ,
            'currency' => $this->currency ,
            'currency_rate' => $this->currency_rate ,
            'amount' => $this->amount ,
        ];
    }
}
