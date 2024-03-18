<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name' , 'phone_number' , 'salary_rate' , 'position' , 'project_id'
    ];

    /*public function dayOffs(){
        return $this->hasMany(DayOff::class);
    }*/

//    public function advancePayments(){
//        return $this->hasMany(AdvancePayment::class);
//    }

    public function workerAccounts(){
        return $this->hasMany(WorkerAccount::class)->orderByDesc('id');
    }
    public function project(){
        return $this->belongsTo(Project::class);
    }
    /*public function dayOffs()
    {
        return $this->hasManyThrough(DayOff::class, WorkerAccount::class);
    }*/
}
