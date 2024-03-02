<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;
    protected $fillable = ['block' , 'project_id' ,'currency'];

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function floors(){
        return $this->hasMany(ContractFloor::class);
    }
}
