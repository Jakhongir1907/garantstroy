<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HiredWorker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name' , 'phone_number' , 'comment' , 'project_id'
    ];

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function expenses(){
        return $this->hasMany(HiredWorkerExpense::class);
    }
}
