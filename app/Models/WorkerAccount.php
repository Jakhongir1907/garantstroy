<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'worker_id' , 'finished_date','status','salary_rate', 'started_date'
    ];

    public function worker(){
        return $this->belongsTo(Worker::class);
    }

    public function dayOffs()
    {
        return $this->hasMany(DayOff::class, 'worker_account_id')->orderByDesc('id');
    }

    public function advancePayments(){
        return $this->hasMany(AdvancePayment::class,'worker_account_id')->orderByDesc('id');
    }
}
