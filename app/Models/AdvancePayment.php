<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvancePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount' , 'date' , 'worker_id'
    ];

    public function worker(){
        return $this->belongsTo(Worker::class);
    }
}
