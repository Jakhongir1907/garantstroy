<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DayOff extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity' ,'date' , 'worker_account_id'
    ];

    public function workerAccount(){
        return $this->belongsTo(WorkerAccount::class);
    }

}
