<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowWorkerAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            //'worker' => $this->worker,
            'started_date' => $this->started_date,
            'finished_date' => $this->finished_date,
            'status' => $this->status,
            'salary_rate' => $this->salary_rate,
            //'day_offs' => $this->dayOffs ?? [],
            //'advance_payment' => $this->advancePayment ?? []
        ];
    }
}
