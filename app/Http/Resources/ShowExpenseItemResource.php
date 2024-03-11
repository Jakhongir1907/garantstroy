<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowExpenseItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'comment' => $this->comment ,
            'summa' => $this->summa ,
            'date' => $this->date ,
            'expense_id' => $this->expense_id ,
            'user' => $this->expense->user->name ?? "" ,
        ];
    }
}
