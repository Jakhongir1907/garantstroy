<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'summa' , 'comment' , 'income_type' , 'date' , 'currency' , 'currency_rate'
    ];

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function filterByDate($query , $projectId , $startDate , $endDate){
        if($projectId) {
            $query->orderByDesc('date')->where('project_id', $projectId);
        }
        if($startDate) {
            $query->orderByDesc('date')->whereDate('date', '>=', $startDate);
        }
        if($endDate) {
            $query->orderByDesc('date')->whereDate('date', '<=', $endDate);
        }
        return $query->get();
    }

}
