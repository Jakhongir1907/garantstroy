<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //'comment' , 'project_id' , 'user_id' , 'date' ,
        //        'amount' ,'currency' , 'currency_rate' , 'summa' , 'category' ,'income_type'
        return [
            'id' => $this->id ,
            'comment' => $this->comment ,
            'project_id' => $this->project_id ?? "",
            'project_name' => $this->project->name ?? "",
            'user_id' => $this->user_id ,
            'user_name' => $this->user->name ,
            'date' => $this->date ,
            'expense_type' => $this->expense_type,
            'amount' => $this->amount ,
            'currency' => $this->currency ,
            'currency_rate' => $this->currency_rate ,
            'summa' => $this->summa ,
            'category' => $this->category ,
        ];
    }
}
