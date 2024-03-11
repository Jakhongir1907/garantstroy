<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name' , 'image_name' , 'image_url' , 'state'
    ];

    public function hiredWorkers(){
        return $this->hasMany(HiredWorker::class);
    }

    public function tools(){
        return $this->hasMany(Tool::class);
    }

    public function contracts(){
        return$this->hasMany(Contract::class);
    }

    public function incomes(){
        return $this->hasMany(Income::class);
    }

    public function workers(){
        return $this->hasMany(Worker::class);
    }

    public function expenses(){
        return $this->hasMany(Expense::class);
    }

}
