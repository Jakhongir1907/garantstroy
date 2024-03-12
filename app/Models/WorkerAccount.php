<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'worker_id' , 'started_date' , 'finished_date','status','salary_rate',
    ];

    public function worker(){
        return $this->belongsTo(Worker::class);
    }

    public function dayOffs()
    {
        return $this->hasMany(DayOff::class, 'worker_account_id')->orderByDesc('id');
    }

    public function advancePayment(){
        return $this->hasMany(AdvancePayment::class,'worker_account_id')->orderByDesc('id');
    }
}
