<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HiredWorkerExpense extends Model
{
    use HasFactory;
    protected $fillable = [
        'summa' , 'date' , 'comment' , 'hired_worker_id'
    ];

    public function hiredWorker(){
        return $this->belongsTo(HiredWorker::class);
    }
}
